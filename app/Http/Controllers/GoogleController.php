<?php
namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class GoogleController extends Controller
{
    public function login()
    {
        return $this->redirectToGoogle('login');
    }

    public function register()
    {
        return $this->redirectToGoogle('register');
    }

    protected function redirectToGoogle(string $mode)
    {
        session(['google_auth_mode' => $mode]);

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $mode = session()->pull('google_auth_mode', 'login');
            $usuarioGoogle = Socialite::driver('google')->user();

            $existeUsuario = Usuario::where('google_id', $usuarioGoogle->id)->first();

            if ($existeUsuario) {
                Auth::login($existeUsuario);

                if ($mode === 'register') {
                    return $this->redirectByRole('Ya tenías una cuenta asociada a esta cuenta de Google: hemos iniciado sesión directamente.');
                }

                return $this->redirectByRole();
            }

            // si ya existe un usuario con el mismo correo, asociar google_id y loguear
            $usuarioPorCorreo = Usuario::where('correo', $usuarioGoogle->email)->first();
            if ($usuarioPorCorreo) {
                $usuarioPorCorreo->google_id = $usuarioGoogle->id;
                $usuarioPorCorreo->save();
                Auth::login($usuarioPorCorreo);

                if ($mode === 'register') {
                    return $this->redirectByRole('Ya tenías una cuenta con este correo electrónico: hemos iniciado sesión directamente.');
                }

                return $this->redirectByRole();
            }

            // no existe ninguna cuenta con este Google ni con este correo:
            // se crea una cuenta nueva automáticamente, tanto si venía de "login" como de "register"
            $datos = [];
            $datos['id'] = (string) Str::uuid();
            $datos['correo'] = $usuarioGoogle->email;
            $datos['google_id'] = $usuarioGoogle->id;
            $datos['password'] = Hash::make('1234');
            $datos['rol_id'] = 2;

            // extraer nombre/apellidos desde los datos de Google
            if (!empty($usuarioGoogle->user['given_name'])) {
                $datos['nombre'] = $usuarioGoogle->user['given_name'];
                $datos['apellidos'] = $usuarioGoogle->user['family_name'] ?? $usuarioGoogle->user['given_name'];
            } elseif (!empty($usuarioGoogle->user['name'])) {
                $full = $usuarioGoogle->user['name'];
                $parts = explode(' ', $full, 2);
                $datos['nombre'] = $parts[0];
                $datos['apellidos'] = $parts[1] ?? $parts[0];
            } else {
                $datos['nombre'] = $usuarioGoogle->email;
                $datos['apellidos'] = $usuarioGoogle->email;
            }

            try {
                $nuevoUsuario = Usuario::create($datos);
            } catch (\Exception $e) {
                Log::error('Error creando usuario Google: ' . $e->getMessage(), ['datos' => $datos]);
                return redirect()
                    ->route('usuarios.login')
                    ->with('error', 'No se pudo crear el usuario con Google.');
            }

            Auth::login($nuevoUsuario);
            return $this->redirectByRole('No tenías una cuenta registrada con este correo: te hemos creado una nueva automáticamente con tu cuenta de Google.');


        } catch (Exception $e) {
            Log::error('Error en login con Google: ' . $e->getMessage());
            return redirect()
                ->route('usuarios.login')
                ->with('error', 'No se pudo iniciar sesión con Google.');
        }

    }

    protected function redirectByRole(string $mensaje = 'Sesión iniciada con Google correctamente.')
    {
        if (Auth::user() && (int) Auth::user()->rol_id === 1) {
            return redirect()
                ->route('usuarios.obtenerListadoUsuarios')
                ->with('success', $mensaje);
        }
        return redirect()
            ->route('usuarios.dashboard')
            ->with('success', $mensaje);
    }
}