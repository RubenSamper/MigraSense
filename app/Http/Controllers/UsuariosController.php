<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{

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

        $usuarios = DB::select('SELECT * FROM usuarios ' . $where . ' ' . $orderBy . ' ' . $paginacion);

        $recordsFiltered = DB::select('SELECT COUNT(id) as recordsNum FROM usuarios ' . $where)[0]->recordsNum;
        $recordsTotal = DB::select('SELECT COUNT(id) as recordsNum FROM usuarios')[0]->recordsNum;

        $datos = array();

        foreach ($usuarios as $usuario) {
            $item = array();

            $item['id'] = $usuario->id;
            $item['nombre'] = $usuario->nombre;
            $item['apellidos'] = $usuario->apellidos;
            $item['correo'] = $usuario->correo;
            $item['rol_id'] = $usuario->rol_id;

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
}
