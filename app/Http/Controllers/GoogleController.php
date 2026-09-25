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
                if ($mode === 'register') {
                    return redirect()
                        ->route('usuarios.createUsuario')
                        ->with('error', 'Ya existe una cuenta asociada a esta cuenta de Google.');
                }

                Auth::login($existeUsuario);
                return $this->redirectByRole();
            }

            // si ya existe un usuario con el mismo correo, asociar google_id y loguear
            $usuarioPorCorreo = Usuario::where('correo', $usuarioGoogle->email)->first();
            if ($usuarioPorCorreo) {
                if ($mode === 'register') {
                    return redirect()
                        ->route('usuarios.createUsuario')
                        ->with('error', 'Ya existe una cuenta con este correo electrónico. Inicia sesión.');
                }

                $usuarioPorCorreo->google_id = $usuarioGoogle->id;
                $usuarioPorCorreo->save();
                Auth::login($usuarioPorCorreo);
                return $this->redirectByRole();
            }

            if ($mode !== 'register') {
                return redirect()
                    ->route('usuarios.login')
                    ->with('error', 'No existe una cuenta con este correo. Regístrate primero.');
            }

            // crear nuevo usuario
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
            return $this->redirectByRole();


        } catch (Exception $e) {
            return redirect()
                ->route('usuarios.login')
                ->with('error', 'No se pudo iniciar sesión con Google.');
        }

    }

    protected function redirectByRole()
    {
        if (Auth::user() && (int) Auth::user()->rol_id === 1) {
            return redirect()
                ->route('usuarios.obtenerListadoUsuarios')
                ->with('success', 'Sesión iniciada con Google correctamente.');
        }
        return redirect()
            ->route('usuarios.dashboard')
            ->with('success', 'Sesión iniciada con Google correctamente.');
    }
}
