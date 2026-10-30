<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\TipoMigrana;
use App\Support\MigranaVisual;

class MigranaController extends Controller
{
    public function registrarMigrana(Request $request)
    {
        // Si venimos del botón "+ Registrar nueva migraña" estando en un mes
        // pasado dentro de Seguimiento, sugerimos el día 1 de ese mes en vez
        // de la fecha de hoy. Si seguimos en el mes actual, no cambia nada.
        $mesSolicitado = $request->query('mes');
        $fechaSugerida = now();
        $finSugerido = null;

        if ($mesSolicitado && preg_match('/^\d{4}-\d{2}$/', $mesSolicitado)) {
            try {
                $mesElegido = Carbon::createFromFormat('Y-m-d', $mesSolicitado . '-01');

                if ($mesElegido->format('Y-m') !== now()->format('Y-m')) {
                    $fechaSugerida = $mesElegido->setTime(now()->hour, now()->minute);
                    // La hora de fin se sugiere igual a la de inicio (mismo día del
                    // mes pasado) para que el selector de fecha se abra ya en ese mes;
                    // el usuario solo tiene que ajustar la hora exacta.
                    $finSugerido = $fechaSugerida->copy();
                }
            } catch (\Exception $e) {
                // Si el mes no es válido, nos quedamos con la fecha de hoy.
            }
        }

        // El selector de tipo de migraña (componente x-select-tipo-migrana)
        // ya lee el catálogo él mismo desde la base de datos, así que aquí
        // no hace falta prepararle nada más.
        return view('usuarios.registrarMigrana', compact('fechaSugerida', 'finSugerido'));
    }

    public function seguimientoMigrana(Request $request)
    {
        $todasLasMigranas = TipoMigrana::query()
            ->where('usuario_id', Auth::id())
            ->orderBy('hora_inicio')
            ->get();

        // IDs de los episodios cuyo diagnóstico todavía no se había visto
        // antes de cargar esta página: se usan para resaltarlos y para la
        // pestaña "Diagnósticos sin leer" del historial. Hay que calcularlo
        // ANTES de marcarlos como vistos (si no, ya no quedaría ninguno que
        // mostrar en esta misma carga de página).
        $diagnosticosSinLeerIds = $todasLasMigranas
            ->filter(fn ($migrana) => filled($migrana->diagnostico) && !$migrana->diagnostico_visto)
            ->pluck('id')
            ->all();

        // El aviso de "tienes un diagnóstico nuevo" ya se muestra al iniciar
        // sesión (ver UsuariosController::validarLogin()). Aquí, con solo
        // entrar a ver sus episodios, se marcan como vistos para que ese
        // aviso no vuelva a salir en el próximo login. La pestaña de abajo
        // los sigue pudiendo mostrar en ESTA carga de página gracias al
        // array calculado arriba.
        TipoMigrana::query()
            ->where('usuario_id', Auth::id())
            ->whereNotNull('diagnostico')
            ->where('diagnostico_visto', false)
            ->update(['diagnostico_visto' => true]);

        // Meses en los que el usuario tiene al menos un episodio registrado
        // (agrupamos por el mes en el que empezó cada episodio).
        $mesesConRegistros = $todasLasMigranas
            ->map(fn ($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m'))
            ->unique()
            ->sortDesc()
            ->values();

        $mesActual = Carbon::now()->format('Y-m');
        $mesSolicitado = $request->query('mes');
        $mesSeleccionado = $mesActual;

        if ($mesSolicitado && preg_match('/^\d{4}-\d{2}$/', $mesSolicitado)) {
            try {
                $mesSeleccionado = Carbon::createFromFormat('Y-m-d', $mesSolicitado . '-01')->format('Y-m');
            } catch (\Exception $e) {
                $mesSeleccionado = $mesActual;
            }

            // Bloqueamos los meses futuros también aquí, no solo ocultando el
            // botón en la vista: alguien podría escribir ?mes=2026-12 a mano.
            if ($mesSeleccionado > $mesActual) {
                $mesSeleccionado = $mesActual;
            }
        } elseif ($mesesConRegistros->isNotEmpty()) {
            // Sin un mes concreto en la URL, abrimos directamente en el mes
            // más reciente que tenga registros.
            $mesSeleccionado = $mesesConRegistros->first();
        }

        // No limitamos lo lejos que se puede retroceder (para poder ir pasando a
        // limpio episodios antiguos poco a poco), pero nunca se puede avanzar
        // más allá del mes actual.
        $cursorMes = Carbon::createFromFormat('Y-m-d', $mesSeleccionado . '-01');
        $mesAnterior = $cursorMes->copy()->subMonthNoOverflow()->format('Y-m');
        $mesSiguiente = $mesSeleccionado < $mesActual
            ? $cursorMes->copy()->addMonthNoOverflow()->format('Y-m')
            : null;

        $etiquetaMesSeleccionado = ucfirst(Carbon::createFromFormat('Y-m-d', $mesSeleccionado . '-01')->locale('es')->translatedFormat('F \d\e Y'));
        $etiquetaMesAnterior = ucfirst(Carbon::createFromFormat('Y-m-d', $mesAnterior . '-01')->locale('es')->translatedFormat('F \d\e Y'));
        $etiquetaMesSiguiente = $mesSiguiente
            ? ucfirst(Carbon::createFromFormat('Y-m-d', $mesSiguiente . '-01')->locale('es')->translatedFormat('F \d\e Y'))
            : null;

        // El desplegable de acceso rápido solo lista meses con registros reales
        // (más el mes que se está viendo y el mes actual, aunque estén vacíos).
        $mesesParaSelector = collect([$mesSeleccionado, $mesActual])
            ->merge($mesesConRegistros)
            ->unique()
            ->sortDesc()
            ->values()
            ->map(fn ($mes) => [
                'valor' => $mes,
                'etiqueta' => ucfirst(Carbon::createFromFormat('Y-m-d', $mes . '-01')->locale('es')->translatedFormat('F Y')),
            ]);

        $migranasDelMes = $todasLasMigranas
            ->filter(fn ($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m') === $mesSeleccionado)
            ->values();

        $diasMigrana = $migranasDelMes
            ->groupBy(fn ($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m-d'))
            ->sortKeys();

        $conteoResumen = [];

        // Se recorren todos los episodios para que uno que cruza de mes
        // pinte sus días en cada mes que toca: octubre muestra hasta el 31
        // y noviembre muestra desde el 1. Los días de otros meses se
        // descartan para que cada mes enseñe solo los suyos.
        foreach ($todasLasMigranas as $migrana) {
            $inicio = Carbon::parse($migrana->hora_inicio)->startOfDay();
            $fin = $migrana->hora_termina
                ? Carbon::parse($migrana->hora_termina)->startOfDay()
                : $inicio;

            foreach (CarbonPeriod::create($inicio, $fin) as $dia) {
                if ($dia->format('Y-m') !== $mesSeleccionado) {
                    continue;
                }

                $clave = $dia->format('Y-m-d');
                $conteoResumen[$clave][$migrana->intensidad] = ($conteoResumen[$clave][$migrana->intensidad] ?? 0) + 1;
            }
        }

        ksort($conteoResumen);

        $resumenDias = collect($conteoResumen)->map(fn ($niveles, $fecha) => [
            'fecha' => $fecha,
            'leve' => $niveles['leve'] ?? 0,
            'moderada' => $niveles['moderada'] ?? 0,
            'fuerte' => $niveles['fuerte'] ?? 0,
        ])->values();

        // Segunda vista de la gráfica: episodios del mes agrupados por tipo de migraña.
        $resumenPorTipo = $migranasDelMes
            ->groupBy('tipo_migrana')
            ->map(fn ($grupo, $tipo) => [
                'tipo' => $tipo,
                'etiqueta' => MigranaVisual::etiquetaTipo($tipo),
                'total' => $grupo->count(),
            ])
            ->sortByDesc('total')
            ->values();

        return view('usuarios.seguimientoMigrana', compact(
            'diasMigrana',
            'resumenDias',
            'resumenPorTipo',
            'mesSeleccionado',
            'mesAnterior',
            'mesSiguiente',
            'etiquetaMesSeleccionado',
            'etiquetaMesAnterior',
            'etiquetaMesSiguiente',
            'mesesParaSelector',
            'mesesConRegistros',
            'todasLasMigranas',
            'diagnosticosSinLeerIds'
        ));
    }

    // Descarga en PDF del seguimiento de un mes concreto (el mismo que se
    // esté viendo en la página, vía ?mes=). Repite el mismo cálculo de mes
    // seleccionado que seguimientoMigrana() para que el botón "Descargar
    // PDF" exporte justo lo que se está viendo en ese momento.
    public function descargarPdfSeguimiento(Request $request)
    {
        $mesActual = Carbon::now()->format('Y-m');
        $mesSolicitado = $request->query('mes');
        $mesSeleccionado = $mesActual;

        if ($mesSolicitado && preg_match('/^\d{4}-\d{2}$/', $mesSolicitado)) {
            try {
                $mesSeleccionado = Carbon::createFromFormat('Y-m-d', $mesSolicitado . '-01')->format('Y-m');
            } catch (\Exception $e) {
                $mesSeleccionado = $mesActual;
            }

            if ($mesSeleccionado > $mesActual) {
                $mesSeleccionado = $mesActual;
            }
        }

        $migranasDelMes = TipoMigrana::query()
            ->where('usuario_id', Auth::id())
            ->orderBy('hora_inicio')
            ->get()
            ->filter(fn ($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m') === $mesSeleccionado)
            ->values();

        $etiquetaMesSeleccionado = ucfirst(
            Carbon::createFromFormat('Y-m-d', $mesSeleccionado . '-01')->locale('es')->translatedFormat('F \d\e Y')
        );

        $usuario = Auth::user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'usuarios.seguimientoMigranaPdf',
            compact('migranasDelMes', 'etiquetaMesSeleccionado', 'usuario')
        );

        return $pdf->download('seguimiento-migrana-' . $mesSeleccionado . '.pdf');
    }

    public function guardarMigrana(Request $request)
    {
        $datos = $request->validate([
            'tipo_migrana' => ['required', 'string', 'exists:tipos_migrana,clave'],
            'intensidad' => ['required', 'string'],
            'hora_inicio' => ['required', 'date'],
            'hora_termina' => ['required', 'date', 'after_or_equal:hora_inicio'],
            'notas' => ['nullable', 'string', 'max:250'],
        ], [
            'tipo_migrana.required' => 'Selecciona un tipo de migraña.',
            'tipo_migrana.exists' => 'Ese tipo de migraña no existe.',
            'intensidad.required' => 'Selecciona la intensidad.',
            'hora_inicio.required' => 'Indica la hora de inicio.',
            'hora_termina.required' => 'Indica la hora de fin.',
            'hora_termina.after_or_equal' => 'La hora de fin debe ser igual o posterior a la de inicio.',
        ]);

        TipoMigrana::create([
            'id' => (string) Str::uuid(),
            'usuario_id' => Auth::id(),
            ...$datos,
        ]);

        // Dejamos el aviso en la sesión para que lo recoja la siguiente
        // petición, sea cual sea la respuesta que demos aquí debajo.
        session()->flash('success', 'Migraña registrada correctamente.');

        // Este formulario se envía por AJAX (jQuery) y es el propio
        // JavaScript el que hace después la navegación real de vuelta a
        // "Seguimiento". Si aquí devolviéramos un redirect (como antes),
        // jQuery lo seguiría él mismo de forma invisible dentro de la
        // misma llamada AJAX, "gastando" el aviso flash antes de que el
        // navegador llegue a cargar la página de verdad, y el aviso nunca
        // se ve. Por eso para peticiones AJAX respondemos solo con JSON,
        // sin redirigir.
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('usuarios.seguimientoMigrana');
    }

    public function eliminarMigrana(string $id)
    {
        $migrana = TipoMigrana::query()
            ->where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $migrana->delete();

        return redirect()
            ->route('usuarios.seguimientoMigrana')
            ->with('success', 'Registro de migraña eliminado correctamente.');
    }

    public function listarMigranas()
    {
        $tipoMigranas = TipoMigrana::all();
        return view('usuarios.listarMigranas', compact('tipoMigranas'));
    }

    // Corta el paso a cualquiera que no sea médico antes de ver datos de
    // pacientes ajenos.
    private function requiereMedico(): void
    {
        if (!Auth::check() || (int) Auth::user()->rol_id !== 3) {
            abort(403, 'No tienes permiso para acceder aquí.');
        }
    }

    public function misPacientes()
    {
        $this->requiereMedico();

        // Para cada paciente: cuántos episodios tiene sin leer por el
        // médico (para el contador/resaltado) y cuándo fue el último
        // episodio registrado (para poder ordenar por actividad reciente).
        $pacientes = \App\Models\Usuario::where('medico_id', Auth::id())
            ->withCount(['migranas as episodios_sin_leer' => function ($query) {
                $query->where('leido_por_medico', false);
            }])
            ->withMax('migranas', 'hora_inicio')
            ->get()
            // Los pacientes con el episodio más reciente van primero; los
            // que todavía no tienen ningún episodio se quedan al final.
            ->sortByDesc(fn ($paciente) => $paciente->migranas_max_hora_inicio ?? '0000-00-00')
            ->values();

        return view('usuarios.medico.misPacientes', compact('pacientes'));
    }

    public function migranasDePaciente(string $id)
    {
        $this->requiereMedico();

        $paciente = \App\Models\Usuario::findOrFail($id);

        // Solo el médico asignado a este paciente puede ver sus migrañas,
        // nunca otro médico aunque también tenga pacientes propios.
        if ((string) $paciente->medico_id !== (string) Auth::id()) {
            abort(403, 'Este paciente no está asignado a tu cuenta.');
        }

        $migranas = TipoMigrana::query()
            ->where('usuario_id', $paciente->id)
            ->orderByDesc('hora_inicio')
            ->get();

        // Ya no se marca todo como leído solo por entrar aquí: cada
        // episodio se da por leído individualmente cuando el médico le
        // guarda su diagnóstico (ver guardarDiagnostico() más abajo). Así
        // el contador de "sin leer" de la lista de pacientes se mantiene
        // mientras queden episodios sin diagnosticar, en vez de ponerse a
        // cero de golpe nada más abrir la lista del paciente.
        return view('usuarios.medico.migranasPaciente', compact('paciente', 'migranas'));
    }

    public function guardarDiagnostico(Request $request, string $id)
    {
        $this->requiereMedico();

        $migrana = TipoMigrana::findOrFail($id);
        $paciente = \App\Models\Usuario::findOrFail($migrana->usuario_id);

        if ((string) $paciente->medico_id !== (string) Auth::id()) {
            abort(403, 'Este paciente no está asignado a tu cuenta.');
        }

        $request->validate([
            'diagnostico' => ['nullable', 'string', 'max:1000'],
        ], [
            'diagnostico.max' => 'El diagnóstico no puede superar los 1000 caracteres.',
        ]);

        $migrana->diagnostico = $request->diagnostico;
        // Si se acaba de escribir/cambiar un diagnóstico, se marca como "no
        // visto" para que al paciente le salga el aviso de que hay uno nuevo.
        // Si se borra el diagnóstico (lo deja en blanco), no hay nada que
        // notificar.
        $migrana->diagnostico_visto = blank($request->diagnostico);
        // Al guardar el diagnóstico de este episodio se marca como leído
        // solo él, no el resto de episodios del paciente: así el contador
        // de "sin leer" baja de uno en uno a medida que se van
        // diagnosticando, y se mantiene mientras queden episodios
        // pendientes por revisar.
        $migrana->leido_por_medico = true;
        $migrana->save();

        // Volvemos al listado de episodios de este mismo paciente (no al
        // listado general de pacientes), para poder seguir revisando el
        // resto de sus registros sin tener que volver a entrar.
        return redirect()
            ->route('usuarios.medico.migranasPaciente', $paciente->id)
            ->with('success', 'Diagnóstico guardado correctamente.');
    }
}