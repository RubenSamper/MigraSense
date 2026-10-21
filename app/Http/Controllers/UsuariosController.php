<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{

    public function landingPage()
    {
        return view('landingPage');
    }

    public function obtenerListadoUsuarios()
    {
        $this->requiereAdmin();
        return view('usuarios.listarUsuario');
    }

    public function getListadoUsuario(Request $request)
    {
        $this->requiereAdmin();
        return $this->listadoPorRol($request, 2);
    }

    public function listarMedicos()
    {
        $this->requiereAdmin();
        return view('usuarios.listarMedico');
    }

    public function getListadoMedico(Request $request)
    {
        $this->requiereAdmin();
        return $this->listadoPorRol($request, 3);
    }

    // Listado paginado/buscable (DataTables server-side) de usuarios de un
    // rol concreto. Lo usan tanto la tabla de pacientes (rol 2) como la de
    // médicos (rol 3), para no repetir la misma construcción de SQL dos veces.
    private function listadoPorRol(Request $request, int $rolId)
    {
        $condiciones = ['u.rol_id = ' . $rolId];

        if (!empty($request->search['value'])) {
            $busqueda = [];
            for ($i = 0; $i < count($request->columns); $i++) {
                $searchable = json_decode($request->columns[$i]['searchable']);

                if ($searchable) {
                    $busqueda[] = 'u.' . $request->columns[$i]['name'] . ' LIKE \'%' . $request->search['value'] . '%\'';
                }
            }

            if (!empty($busqueda)) {
                $condiciones[] = '(' . implode(' OR ', $busqueda) . ')';
            }
        }

        $where = 'WHERE ' . implode(' AND ', $condiciones);

        $orderBy = 'ORDER BY ' . $request->columns[$request->order[0]['column']]['name'] . ' ' . $request->order[0]['dir'];

        $paginacion = '';
        if ($request->length != -1) {
            $paginacion .= 'LIMIT ' . $request->length . ' OFFSET ' . $request->start;
        }

        $resultados = DB::select('SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id ' . $where . ' ' . $orderBy . ' ' . $paginacion);

        $recordsFiltered = DB::select('SELECT COUNT(u.id) as recordsNum FROM usuarios u JOIN roles r ON u.rol_id = r.id ' . $where)[0]->recordsNum;
        $recordsTotal = DB::select('SELECT COUNT(id) as recordsNum FROM usuarios WHERE rol_id = ' . $rolId)[0]->recordsNum;

        $datos = array();

        foreach ($resultados as $usuario) {
            $item = array();

            $item['id'] = $usuario->id;
            $item['nombre'] = $usuario->nombre;
            $item['apellidos'] = $usuario->apellidos;
            $item['correo'] = $usuario->correo;
            $item['rol_nombre'] = $usuario->rol_nombre;
            $item['puede_editar'] = $this->puedeGestionarUsuario((string) $usuario->id, (int) $usuario->rol_id);
            $item['puede_eliminar'] = $item['puede_editar'];

            $datos[] = $item;
        }

        return response()->json(['draw' => $request->draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'data' => $datos]);
    }

    public function eliminarUsuario(Request $request)
    {
        $this->requiereAdmin();

        $request->validate([
            'id' => [
                'required',
                'exists:usuarios,id'
            ]
        ]);

        $usuario = Usuario::find($request->id);

        if (!$usuario || !$this->puedeGestionarUsuario($usuario->id, $usuario->rol_id)) {
            return response()->json(['message' => 'No tienes permiso para eliminar este usuario.'], 403);
        }

        // Guardamos el rol antes de borrar, para poder decir "Paciente" o
        // "Médico" en el mensaje en vez del genérico "Usuario".
        $tipo = ((int) $usuario->rol_id === 3) ? 'Médico' : 'Paciente';

        $usuario->delete();

        return response()->json(['success' => true, 'message' => "{$tipo} eliminado correctamente"]);
    }

    public function createUsuario()
    {
        $medicos = DB::select("SELECT id, nombre, apellidos FROM usuarios WHERE rol_id = 3 ORDER BY nombre, apellidos");
        return view('usuarios.crearUsuario', compact('medicos'));
    }

    public function crearMedico()
    {
        $this->requiereAdmin();
        return view('usuarios.crearMedico');
    }

    public function almacenarMedico(Request $request)
    {
        $this->requiereAdmin();

        $request->validate([
            'nombre' => [
                'required',
                'regex:/^[\pL\s]+$/u',
                'max:255'
            ],
            'apellidos' => [
                'required',
                'regex:/^[\pL\s]+$/u',
                'max:255'
            ],
            'correo' => [
                'required',
                'email',
                'unique:usuarios,correo'
            ],
            'password' => [
                'required',
                'min:8'
            ]
        ], self::mensajesValidacionUsuario());

        Usuario::create([
            'id' => self::generarUUID(),
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => 3
        ]);

        return redirect()->route('usuarios.listarMedicos')->with('success', 'Médico creado correctamente.');
    }

    public function validarNombreUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'regex:/^[\pL\s]+$/u',
            ]
        ], self::mensajesValidacionUsuario());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]/*, 500*/);
        } else {
            return response()->json(['success' => true]);
        }
    }

    public function validarApellidosUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'apellidos' => [
                'required',
                'regex:/^[\pL\s]+$/u',
            ]
        ], self::mensajesValidacionUsuario());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]/*, 500*/);
        } else {
            return response()->json(['success' => true]);
        }
    }

    public function validarCorreoUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => [
                'required',
                'email',
                'unique:usuarios,correo'
            ]
        ], self::mensajesValidacionUsuario());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]/*, 500*/);
        } else {
            return response()->json(['success' => true]);
        }
    }

    private static function generarUUID()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function almacenarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'regex:/^[\pL\s]+$/u',
                'max:255'
            ],
            'apellidos' => [
                'required',
                'regex:/^[\pL\s]+$/u',
                'max:255'
            ],
            'correo' => [
                'required',
                'email',
                'unique:usuarios,correo'
            ],
            'password' => [
                'required',
                'min:8'
            ],
            'medico_id' => [
                'required',
                'exists:usuarios,id,rol_id,3'
            ]
        ], self::mensajesValidacionUsuario() + [
            'medico_id.required' => 'Tienes que elegir un médico.',
            'medico_id.exists' => 'El médico elegido no es válido.',
        ]);

        Usuario::create([
            'id' => self::generarUUID(),
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => 2,
            'medico_id' => $request->medico_id,
        ]);

        return redirect()
            ->route('usuarios.login')
            ->with('success', 'Paciente creado correctamente. Ya puedes iniciar sesión.');
    }

    public function dashboard()
    {
        // El dashboard es de seguimiento de presión/migrañas del propio
        // paciente; un médico no tiene nada que ver ahí, así que si entra
        // por URL directa lo mandamos a su listado de pacientes.
        if ((int) Auth::user()->rol_id === 3) {
            return redirect()->route('usuarios.medico.misPacientes');
        }

        $usuario = Auth::user();
        $aemet = new \App\Services\AemetService();

        $datosClima = null;
        $riesgo = ['nivel' => 'desconocido', 'variacion_hpa' => null];
        $observaciones = [];
        $previsionTemperatura = [];
        $previsionPresion = [];

        if ($usuario->latitud && $usuario->longitud) {
            $estacion = $aemet->estacionConDatosMasCercana((float) $usuario->latitud, (float) $usuario->longitud);
            if ($estacion) {
                $observaciones = $aemet->obtenerObservacionEstacion($estacion['idema']);
                if (!empty($observaciones)) {
                    $riesgo = $aemet->calcularRiesgoMigrana($observaciones, $usuario->sensibilidad_presion);

                    // Si no hay datos suficientes para calcular un nivel real, no lo
                    // guardamos en el historial: la columna 'nivel' solo admite
                    // verde/amarillo/rojo, y "desconocido" solo es un estado de la
                    // vista para ese día, no un dato histórico real.
                    if ($riesgo['nivel'] !== 'desconocido') {
                        \App\Models\HistorialRiesgo::updateOrCreate(
                            [
                                'usuario_id' => $usuario->id,
                                'fecha' => now()->toDateString(),
                            ],
                            [
                                'id' => (string) \Illuminate\Support\Str::uuid(),
                                'nivel' => $riesgo['nivel'],
                                'variacion_hpa' => $riesgo['variacion_hpa'],
                            ]
                        );
                    }

                    $ultimaObservacion = end($observaciones);

                    $datosClima = [
                        'estacion_nombre' => $estacion['ubi'] ?? $estacion['nombre'] ?? 'Desconocida',
                        'temperatura_actual' => $ultimaObservacion['ta'] ?? null,
                        'presion_actual' => $ultimaObservacion['pres_nmar'] ?? null,
                        // Se pone a true más abajo si el dato de la estación está
                        // desfasado y lo sustituimos por la estimación de la hora
                        // actual, para poder avisar de ello en la vista.
                        'actual_es_estimado' => false,
                        'observaciones' => array_map(fn($obs) => [
                            'hora' => substr($obs['fint'] ?? '', 11, 5),
                            'temperatura' => $obs['ta'] ?? null,
                            'presion' => $obs['pres_nmar'] ?? null,
                        ], $observaciones),
                    ];

                    // Previsión para el resto del día. La temperatura viene de la
                    // predicción horaria oficial de AEMET (por municipio); AEMET no
                    // da predicción de presión, así que esa se estima con una
                    // tendencia lineal propia a partir de las últimas observaciones
                    // reales. Todo esto es "best effort": si algo falla, no se
                    // muestra previsión, pero el resto del dashboard sigue
                    // funcionando con datos reales.
                    try {
                        $ahora = \Carbon\Carbon::now('Europe/Madrid');

                        // La previsión tiene que empezar justo donde terminan los
                        // datos REALES, no a partir de la hora actual del
                        // servidor: la estación de AEMET suele ir unas horas por
                        // detrás (su última observación puede ser, por ejemplo,
                        // de las 12:00 aunque ya sean las 15:00). Si usáramos la
                        // hora actual, quedaría un hueco sin datos entre la
                        // última observación real y el principio de la
                        // previsión. Por eso la previsión arranca en la hora
                        // siguiente a la ÚLTIMA observación real disponible.
                        $horaInicioPrevision = !empty($ultimaObservacion['fint'])
                            ? ((int) substr($ultimaObservacion['fint'], 11, 2)) + 1
                            : ((int) $ahora->format('H')) + 1;
                        $horasRestantes = $horaInicioPrevision <= 23 ? range($horaInicioPrevision, 23) : [];

                        if (!empty($horasRestantes)) {
                            // --- Temperatura: predicción horaria oficial de AEMET ---
                            $municipio = $aemet->municipioMasCercano((float) $usuario->latitud, (float) $usuario->longitud);

                            if ($municipio && !empty($municipio['id'])) {
                                $prediccionAemet = $aemet->obtenerPrevisionHoraria($municipio['id']);
                                $hoyStr = $ahora->toDateString();

                                foreach ($horasRestantes as $h) {
                                    $horaEtiqueta = sprintf('%02d:00', $h);
                                    $punto = collect($prediccionAemet)->first(
                                        fn($p) => ($p['fecha'] ?? null) === $hoyStr && ($p['hora'] ?? null) === $horaEtiqueta
                                    );
                                    if ($punto) {
                                        $previsionTemperatura[] = [
                                            'hora' => $horaEtiqueta,
                                            'valor' => $punto['valor'],
                                        ];
                                    }
                                }
                            }

                            // --- Presión: estimación propia por tendencia lineal ---
                            $ultimasPresiones = array_values(array_filter(array_map(
                                fn($obs) => isset($obs['pres_nmar']) ? (float) $obs['pres_nmar'] : null,
                                array_slice($observaciones, -4)
                            ), fn($valor) => $valor !== null));

                            if (count($ultimasPresiones) >= 2) {
                                $n = count($ultimasPresiones);
                                $tendenciaHora = ($ultimasPresiones[$n - 1] - $ultimasPresiones[0]) / ($n - 1);
                                $presionBase = $ultimasPresiones[$n - 1];

                                $paso = 1;
                                foreach ($horasRestantes as $h) {
                                    $previsionPresion[] = [
                                        'hora' => sprintf('%02d:00', $h),
                                        'valor' => round($presionBase + ($tendenciaHora * $paso), 1),
                                    ];
                                    $paso++;
                                }
                            }
                        }

                        // Si la última observación real de la estación es de
                        // una hora anterior a la actual (va con retraso),
                        // los recuadros de "Temperatura actual" / "Presión
                        // actual" no deben quedarse enseñando ese dato viejo
                        // como si fuera el de ahora: los sustituimos por la
                        // estimación de la hora en la que se está
                        // consultando el dashboard, que es lo que de verdad
                        // se quiere ver al entrar.
                        $horaUltimaObservacion = !empty($ultimaObservacion['fint'])
                            ? (int) substr($ultimaObservacion['fint'], 11, 2)
                            : null;
                        $horaActual = (int) $ahora->format('H');

                        if ($horaUltimaObservacion !== null && $horaUltimaObservacion < $horaActual) {
                            $etiquetaHoraActual = sprintf('%02d:00', $horaActual);

                            $temperaturaActualEstimada = collect($previsionTemperatura)->firstWhere('hora', $etiquetaHoraActual);
                            if ($temperaturaActualEstimada) {
                                $datosClima['temperatura_actual'] = $temperaturaActualEstimada['valor'];
                                $datosClima['actual_es_estimado'] = true;
                            }

                            $presionActualEstimada = collect($previsionPresion)->firstWhere('hora', $etiquetaHoraActual);
                            if ($presionActualEstimada) {
                                $datosClima['presion_actual'] = $presionActualEstimada['valor'];
                                $datosClima['actual_es_estimado'] = true;
                            }
                        }
                    } catch (\Throwable $e) {
                        $previsionTemperatura = [];
                        $previsionPresion = [];
                    }

                    $datosClima['prevision_temperatura'] = $previsionTemperatura;
                    $datosClima['prevision_presion'] = $previsionPresion;
                }
            }
        }

        return view('usuarios.dashboard', compact('riesgo', 'datosClima'));
    }
    public function login()
    {
        return view('usuarios.login');
    }

    public function validarLogin(Request $request)
    {
        $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt(['correo' => $request->correo, 'password' => $request->password])) {
            $usuario = Auth::user();

            if ((int) $usuario->rol_id === 1) {
                return redirect()
                    ->route('usuarios.obtenerListadoUsuarios')
                    ->with('success', 'Sesión iniciada correctamente.');
            }

            if ((int) $usuario->rol_id === 3) {
                return redirect()
                    ->route('usuarios.medico.misPacientes')
                    ->with('success', 'Sesión iniciada correctamente.');
            }

            return redirect()
                ->route('usuarios.dashboard')
                ->with('success', 'Sesión iniciada correctamente.')
                ->with('diagnostico_nuevo', $this->mensajeDiagnosticoNuevo($usuario));
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }

    // Si el paciente tiene algún diagnóstico que su médico le ha dejado y
    // todavía no ha visto (columna `diagnostico_visto` en `migranas`), le
    // avisamos ya al iniciar sesión, en vez de esperar a que entre por su
    // cuenta en "Seguimiento de migraña". Se sigue avisando en cada login
    // mientras quede al menos un diagnóstico sin ver; en cuanto entra a
    // ver sus episodios se marca como visto (MigranaController::
    // seguimientoMigrana()) y este aviso deja de salir.
    private function mensajeDiagnosticoNuevo(Usuario $usuario): ?string
    {
        if ((int) $usuario->rol_id !== 2) {
            return null;
        }

        $hayDiagnosticoNuevo = \App\Models\TipoMigrana::query()
            ->where('usuario_id', $usuario->id)
            ->whereNotNull('diagnostico')
            ->where('diagnostico_visto', false)
            ->exists();

        return $hayDiagnosticoNuevo
            ? 'Tu médico te ha dejado un nuevo diagnóstico. Entra en "Seguimiento de migraña" para verlo.'
            : null;
    }

    public function editarUsuario($id)
    {
        $this->requiereAdmin();

        $usuario = Usuario::findOrFail($id);

        if (!$this->puedeGestionarUsuario($usuario->id, $usuario->rol_id)) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $roles = DB::select('SELECT * FROM roles');
        return view('usuarios.editarUsuario', compact('usuario', 'roles'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $this->requiereAdmin();

        $usuario = Usuario::findOrFail($id);

        if (!$this->puedeGestionarUsuario($usuario->id, $usuario->rol_id)) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'],
            'correo' => ['required', 'email', 'unique:usuarios,correo,' . $id . ',id'],
            'rol_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'min:8'],
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->correo = $request->correo;
        $usuario->rol_id = $request->rol_id;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        // Si el usuario editado es médico, vuelve al listado de médicos;
        // si es paciente o admin, al listado de pacientes (que es donde
        // estaba la gestión general hasta ahora). El mensaje también dice
        // "Paciente" o "Médico" en vez del genérico "Usuario".
        $esMedico = (int) $usuario->rol_id === 3;
        $destino = $esMedico ? 'usuarios.listarMedicos' : 'usuarios.obtenerListadoUsuarios';
        $tipo = $esMedico ? 'Médico' : 'Paciente';

        return redirect()->route($destino)->with('success', "{$tipo} actualizado correctamente.");
    }

    public function configuracion()
    {
        $usuario = Auth::user();
        return view('usuarios.configuracion', compact('usuario'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'sensibilidad_presion' => 'required|in:poco_sensible,sensible,muy_sensible',
        ]);

        $usuario = Usuario::findOrFail(Auth::id());
        $usuario->latitud = $request->latitud;
        $usuario->longitud = $request->longitud;
        $usuario->sensibilidad_presion = $request->sensibilidad_presion;
        $usuario->save();

        return redirect()
            ->route('usuarios.configuracion')
            ->with('success', 'Configuración guardada correctamente.');
    }

    public function estacionesAemetDisponibles()
    {
        $aemet = new \App\Services\AemetService();
        $observaciones = $aemet->obtenerTodasLasObservacionesActuales();

        $estaciones = collect($observaciones)
            ->filter(fn($obs) => isset($obs['lat'], $obs['lon'], $obs['ubi']))
            ->map(fn($obs) => [
                'nombre' => $obs['ubi'],
                'lat' => (float) $obs['lat'],
                'lon' => (float) $obs['lon'],
            ])
            ->unique('nombre')
            ->sortBy('nombre')
            ->values();

        return response()->json($estaciones);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()
            ->route('landingPage')
            ->with('info', 'Has cerrado sesión correctamente.');
    }

    private function puedeGestionarUsuario(string $usuarioId, int $rolObjetivo): bool
    {
        $rolActual = (int) Auth::user()->rol_id;

        if ((string) Auth::id() === (string) $usuarioId) {
            return false;
        }

        return $rolActual === 1;
    }

    // Corta el paso a cualquiera que no sea admin (incluido un visitante sin
    // sesión) antes de tocar datos de pacientes/médicos. Estas rutas de
    // gestión no tenían ninguna comprobación de esto hasta ahora.
    private function requiereAdmin(): void
    {
        if (!Auth::check() || (int) Auth::user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para acceder aquí.');
        }
    }

    public function validarPasswordUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8'
            ]
        ], self::mensajesValidacionUsuario());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            return response()->json(['success' => true]);
        }
    }

    private static function mensajesValidacionUsuario(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'apellidos.max' => 'Los apellidos no pueden superar los 255 caracteres.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Introduce un correo electrónico válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}