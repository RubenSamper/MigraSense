@extends("layouts.default")
@section('title','Iniciar Sesión')
@section('scripts')
<style>
    .login-wrapper {
        max-width: 450px;
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
        background: white;
        color: #3c4043;
        border: 1px solid #dadce0;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
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
        <a href="{{ route('google.login') }}" style="display:block; text-align:center; padding:12px; background:white; color:#3c4043; border:1px solid #dadce0; border-radius:5px; text-decoration:none; font-weight:bold; margin-top:12px;">Iniciar sesión con Google</a>

        @if(session('error'))
        <div class="alerta" style="margin-top: 15px;">{{ session('error') }}</div>
        @endif
</div>
@endsection