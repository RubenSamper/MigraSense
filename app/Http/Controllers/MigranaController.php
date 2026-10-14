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

        return view('usuarios.registrarMigrana', compact('fechaSugerida', 'finSugerido'));
    }

    public function seguimientoMigrana(Request $request)
    {
        $todasLasMigranas = TipoMigrana::query()
            ->where('usuario_id', Auth::id())
            ->orderBy('hora_inicio')
            ->get();

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

        foreach ($migranasDelMes as $migrana) {
            $inicio = Carbon::parse($migrana->hora_inicio)->startOfDay();
            $fin = $migrana->hora_termina
                ? Carbon::parse($migrana->hora_termina)->startOfDay()
                : $inicio;

            foreach (CarbonPeriod::create($inicio, $fin) as $dia) {
                // Si el episodio se alarga a otro mes, solo contamos los días
                // que caen dentro del mes que se está mostrando.
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
            'todasLasMigranas'
        ));
    }

    public function guardarMigrana(Request $request)
    {
        $datos = $request->validate([
            'tipo_migrana' => ['required', 'string'],
            'intensidad' => ['required', 'string'],
            'hora_inicio' => ['required', 'date'],
            'hora_termina' => ['required', 'date', 'after_or_equal:hora_inicio'],
            'notas' => ['nullable', 'string', 'max:250'],
        ], [
            'tipo_migrana.required' => 'Selecciona un tipo de migraña.',
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

        return redirect()
            ->route('usuarios.seguimientoMigrana')
            ->with('success', 'Migraña registrada correctamente.');
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
}