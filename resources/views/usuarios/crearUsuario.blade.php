@extends("layouts.default")
@section('title', 'Nuevo Usuario')
@section('scripts')
    <style>
        .registro-wrapper {
            width: min(520px, calc(100% - 32px));
            box-sizing: border-box;
            margin: 48px auto;
            padding: 34px;
            border: 1px solid #d8e4e8;
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(23, 48, 66, 0.1);
            background: #ffffff;
        }

        .registro-wrapper h3 {
            color: #173042;
            margin: 0 0 8px;
            text-align: center;
            font-size: 1.65em;
        }

        .registro-intro {
            margin: 0 0 28px;
            color: #60747d;
            text-align: center;
        }

        .campo {
            margin-bottom: 22px;
        }

        .registro-wrapper label {
            display: block;
            margin-bottom: 7px;
            color: #173042;
            font-weight: bold;
        }

        .campo-control {
            position: relative;
        }

        .registro-wrapper input {
            width: 100%;
            padding: 12px 42px 12px 13px;
            border: 1px solid #b7c8ce;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            color: #173042;
            transition: border-color .2s, box-shadow .2s, background-color .2s;
        }

        .registro-wrapper input:focus {
            outline: none;
            border-color: #007f86;
            box-shadow: 0 0 0 3px rgba(0, 127, 134, .14);
        }

        .registro-wrapper input.is-valid {
            border-color: #198754;
            background: #f4fbf7;
        }

        .registro-wrapper input.is-invalid {
            border-color: #c0392b;
            background: #fff8f7;
        }

        .campo-icon {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            font-size: 1.1em;
            font-weight: bold;
        }

        .campo-icon.valid {
            color: #198754;
        }

        .campo-icon.invalid {
            color: #c0392b;
        }

        .campo-mensaje {
            min-height: 20px;
            margin: 5px 0 0;
            font-size: .88em;
        }

        .campo-mensaje.error {
            color: #a93226;
        }

        .campo-mensaje.success {
            color: #19703f;
        }

        .campo-mensaje .server-error {
            display: block;
        }

        .registro-wrapper button {
            width: 100%;
            padding: 13px;
            background: #007f86;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
        }

        .registro-wrapper button:hover {
            background: #00666c;
        }

        .registro-wrapper .google-link {
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            background: white;
            color: #3c4043;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
        }

        .registro-wrapper .google-link:hover {
            background: #f8f9fa;
            border-color: #d2d3d4;
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        @media (max-width: 480px) {
            .registro-wrapper {
                margin: 24px auto;
                padding: 20px 16px;
            }
        }
    </style>
    <script>
        function mostrarEstado(campo, valido, mensaje) {
            const input = $('#' + campo);
            const icono = $('#' + campo + 'Icon');
            const ayuda = $('#' + campo + 'Mensaje');
            input.toggleClass('is-valid', valido).toggleClass('is-invalid', !valido).attr('aria-invalid', !valido);
            icono.html(valido ? '&#10003;' : '!').removeAttr('hidden').toggleClass('valid', valido).toggleClass('invalid', !valido).show();
            ayuda.text(mensaje).toggleClass('success', valido).toggleClass('error', !valido);
        }

        function validarNombreUsuario() {
            $.ajax({
                url: "{{ route('usuarios.validarNombreUsuario') }}",
                type: 'POST',
                data: {
                    nombre: $('#nombre').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.errors != null) {
                        mostrarEstado('nombre', false, response.errors[0]);
                    }
                    else {
                        mostrarEstado('nombre', true, 'Nombre válido');
                    }
                },

                error: function (response) {
                    console.error("Error", response);
                    mostrarEstado('nombre', false, 'No se pudo validar el nombre.');
                }
            });
        };

        function validarApellidosUsuario() {
            $.ajax({
                url: "{{ route('usuarios.validarApellidosUsuario') }}",
                type: 'POST',
                data: {
                    apellidos: $('#apellidos').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.errors != null) {
                        mostrarEstado('apellidos', false, response.errors[0]);
                    }
                    else {
                        mostrarEstado('apellidos', true, 'Apellidos válidos');
                    }
                },

                error: function (response) {
                    console.error("Error", response);
                    mostrarEstado('apellidos', false, 'No se pudieron validar los apellidos.');
                }
            });
        };

        function validarCorreoUsuario() {
            $.ajax({
                url: "{{ route('usuarios.validarCorreoUsuario') }}",
                type: 'POST',
                data: {
                    correo: $('#correo').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.errors != null) {
                        mostrarEstado('correo', false, response.errors[0]);
                    }
                    else {
                        mostrarEstado('correo', true, 'Correo disponible');
                    }
                },

                error: function (response) {
                    console.error("Error", response);
                    mostrarEstado('correo', false, 'No se pudo validar el correo.');
                }
            });
        };

        function validarPasswordUsuario() {
            $.ajax({
                url: "{{ route('usuarios.validarPasswordUsuario') }}",
                type: 'POST',
                data: {
                    password: $('#password').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.errors != null) {
                        mostrarEstado('password', false, response.errors[0]);
                    }
                    else {
                        mostrarEstado('password', true, 'Contraseña válida');
                    }
                },

                error: function (response) {
                    console.error("Error", response);
                    mostrarEstado('password', false, 'No se pudo validar la contraseña.');
                }
            });
        };

    </script>
@endsection
@section('contenido')
    <div class="registro-wrapper">
        <h3>Nuevo usuario</h3>
        <p class="registro-intro">Completa tus datos para crear una cuenta.</p>
        <form action="{{ route('usuarios.almacenarUsuario') }}" method="post">
            @csrf
            <div class="campo">
                <label for="nombre">Nombre</label>
                <div class="campo-control"><input type="text" onInput="validarNombreUsuario()" id="nombre" name="nombre"
                        value="{{ old('nombre') }}" placeholder="Ej: Blanca" maxlength="255" autocomplete="given-name"
                        aria-describedby="nombreMensaje" aria-invalid="false" /><span id="nombreIcon" class="campo-icon"
                        hidden aria-hidden="true"></span></div>
                <p id="nombreMensaje" class="campo-mensaje" aria-live="polite">Solo letras y espacios.</p>
                @error('nombre')
                    <span class="campo-mensaje error server-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="campo">
                <label for="apellidos">Apellidos</label>
                <div class="campo-control"><input type="text" onInput="validarApellidosUsuario()" id="apellidos"
                        name="apellidos" value="{{ old('apellidos') }}" placeholder="Ej: García López" maxlength="255"
                        autocomplete="family-name" aria-describedby="apellidosMensaje" aria-invalid="false" /><span
                        id="apellidosIcon" class="campo-icon" hidden aria-hidden="true"></span></div>
                <p id="apellidosMensaje" class="campo-mensaje" aria-live="polite">Solo letras y espacios.</p>
                @error('apellidos')
                    <span class="campo-mensaje error server-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <div class="campo-control"><input type="email" onInput="validarCorreoUsuario()" id="correo" name="correo"
                        value="{{ old('correo') }}" placeholder="ejemplo@correo.com" maxlength="255" autocomplete="email"
                        aria-describedby="correoMensaje" aria-invalid="false" /><span id="correoIcon" class="campo-icon"
                        hidden aria-hidden="true"></span></div>
                <p id="correoMensaje" class="campo-mensaje" aria-live="polite">Usaremos este correo para iniciar sesión.</p>
                @error('correo')
                    <span class="campo-mensaje error server-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <div class="campo-control"><input type="password" onInput="validarPasswordUsuario()" id="password"
                        name="password" placeholder="Mínimo 8 caracteres" minlength="8" autocomplete="new-password"
                        aria-describedby="passwordMensaje" aria-invalid="false" /><span id="passwordIcon" class="campo-icon"
                        hidden aria-hidden="true"></span></div>
                <p id="passwordMensaje" class="campo-mensaje" aria-live="polite">Debe tener al menos 8 caracteres.</p>
                @error('password')
                    <span class="campo-mensaje error server-error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Crear usuario</button>
        </form>
        <a class="google-link" href="{{ route('google.register') }}">
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
            Crear cuenta con Google
        </a>
    </div>
@endsection