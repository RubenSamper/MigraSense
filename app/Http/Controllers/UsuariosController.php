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
        return view('usuarios.listarUsuario');
    }

    public function getListadoUsuario(Request $request)
    {
        $where = '';

        if (!empty($request->search['value'])) {
            $stringAdded = false;
            $where .= 'WHERE ';
            for ($i = 0; $i < count($request->columns); $i++) {

                $searchable = json_decode($request->columns[$i]['searchable']);

                if ($searchable) {
                    if ($stringAdded) {
                        $where .= ' OR ';
                    }

                    $where .= $request->columns[$i]['name'] . ' LIKE \'%' . $request->search['value'] . '%\'';
                    $stringAdded = true;
                }
            }
        }

        $orderBy = 'ORDER BY ' . $request->columns[$request->order[0]['column']]['name'] . ' ' . $request->order[0]['dir'];

        $paginacion = '';
        if ($request->length != -1) {
            $paginacion .= 'LIMIT ' . $request->length . ' OFFSET ' . $request->start;
        }

        $resultados = DB::select('SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id ' . $where . ' ' . $orderBy . ' ' . $paginacion);

        $recordsFiltered = DB::select('SELECT COUNT(u.id) as recordsNum FROM usuarios u JOIN roles r ON u.rol_id = r.id ' . $where)[0]->recordsNum;
        $recordsTotal = DB::select('SELECT COUNT(id) as recordsNum FROM usuarios')[0]->recordsNum;

        $datos = array();

        foreach ($resultados as $usuario) {
            $item = array();

            $item['id'] = $usuario->id;
            $item['nombre'] = $usuario->nombre;
            $item['apellidos'] = $usuario->apellidos;
            $item['correo'] = $usuario->correo;
            $item['rol_nombre'] = $usuario->rol_nombre;

            $datos[] = $item;
        }

        return response()->json(['draw' => $request->draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'data' => $datos]);
    }

    public function eliminarUsuario(Request $request)
    {
        $request->validate([
            'id' => [
                'required',
                'exists:usuarios,id'
            ]
        ]);

        $usuario = Usuario::find($request->id);
        $usuario->delete();

        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    }

    public function createUsuario()
    {
        return view('usuarios.crearUsuario');
    }

    public function validarNombreUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'regex:/^[\pL\s]+$/u',
            ]
        ]);

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
        ]);

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
        ]);

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
            ]
        ]);

        Usuario::create([
            'id' => self::generarUUID(),
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => 1
        ]);

        return redirect()->route('usuarios.createUsuario')->with('success', 'Usuario creado correctamente.');
    }

    public function dashboard()
    {
        return view('usuarios.dashboard');
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
            if ($usuario->rol_id == 1) {
                return redirect()->route('usuarios.obtenerListadoUsuarios');
            }
            return redirect()->route('usuarios.dashboard');
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }

    public function editarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = DB::select('SELECT * FROM roles');
        return view('usuarios.editarUsuario', compact('usuario', 'roles'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

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

        return redirect()->route('usuarios.obtenerListadoUsuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function guardarZona(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        $usuario = Auth::user();
        $usuario->latitud = $request->latitud;
        $usuario->longitud = $request->longitud;
        $usuario->save();

        return response()->json(['success' => true]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingPage');
    }

    public function validarPasswordUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            return response()->json(['success' => true]);
        }
    }
}
