@extends("layouts.default")
@section('title', 'Iniciar Sesión')
@section('scripts')
    <style>
        .login-wrapper {
            width: min(450px, calc(100% - 32px));
            box-sizing: border-box;
            margin: 60px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        .login-wrapper h3 {
            color: #006994;
            margin: 0 0 20px;
            text-align: center;
            font-size: 1.5em;
        }

        .login-wrapper label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .login-wrapper input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .login-wrapper input:focus {
            outline: none;
            border-color: #006994;
            box-shadow: 0 0 5px rgba(0, 105, 148, 0.3);
        }

        .login-wrapper button {
            width: 100%;
            padding: 12px;
            background: #006994;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
        }

        .login-wrapper button:hover {
            background: #004b6b;
        }

        .login-wrapper .google-login-btn {
            width: 100%;
            box-sizing: border-box;
            background: white;
            color: #3c4043;
            border: 1px solid #dadce0;
            border-radius: 5px;
            padding: 12px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 1em;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .login-wrapper .google-login-btn:hover {
            background: #f8f9fa;
            border-color: #d2d3d4;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        .login-wrapper .alerta {
            padding: 8px;
            border: 1px solid #ff0000;
            color: #ff0000;
            background-color: #ced3eb;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 0.9em;
        }

        @media (max-width: 480px) {
            .login-wrapper {
                margin: 28px auto;
                padding: 22px 16px;
            }
        }
    </style>
@endsection
@section('contenido')
    <div class="login-wrapper">
        <h3>Iniciar Sesión</h3>
        <form action="{{ route('usuarios.validarLogin') }}" method="post">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="correo">Correo Electrónico</label>
                <input type="text" id="correo" name="correo" value="{{ old('correo') }}" />
                @error('correo')
                    <div class="alerta">{{ $message }}</div>
                @enderror
            </div>
            <div style="margin-bottom: 20px;">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" />
                @error('password')
                    <div class="alerta">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" style="margin-bottom: 12px;">Entrar</button>
        </form>
        <a href="{{ route('google.login') }}" class="google-login-btn">
            <svg class="google-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#FFC107"
                    d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                <path fill="#FF3D00"
                    d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                <path fill="#4CAF50"
                    d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                <path fill="#1976D2"
                    d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
            </svg>
            Iniciar sesión con Google
        </a>
    </div>
@endsection