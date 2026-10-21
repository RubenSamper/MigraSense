<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


    <!-- Datatables -->
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>
    <title>@yield('title')</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            height: 100%;
        }

        body {
            margin: 0;
            min-width: 320px;
            min-height: 100%;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        img,
        canvas,
        iframe {
            max-width: 100%;
        }

        button,
        input,
        select,
        textarea {
            max-width: 100%;
            font: inherit;
        }

        main,
        section,
        article,
        form {
            min-width: 0;
        }

        :focus-visible {
            outline: 3px solid #f5b942;
            outline-offset: 3px;
        }

        .skip-link {
            position: fixed;
            top: 8px;
            left: 8px;
            z-index: 2000;
            padding: 10px 14px;
            border-radius: 6px;
            background: #173042;
            color: #fff;
            transform: translateY(-150%);
        }

        .skip-link:focus {
            transform: translateY(0);
        }

        .page-content {
            flex: 1 0 auto;
        }

        .page-content:focus {
            outline: none;
        }

        .page-shell {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        footer {
            flex-shrink: 0;
        }

    </style>
    @yield('scripts')
</head>

<body>
    <a class="skip-link" href="#main-content">Saltar al contenido principal</a>
    <x-layouts.menu />
    <div class="notification-stack" aria-label="Notificaciones">
        @foreach (['success', 'error', 'warning', 'info'] as $tipo)
            <x-notification :type="$tipo" :message="session($tipo)" />
        @endforeach
        {{-- Aviso de "tienes un diagnóstico nuevo del médico" (ver
             UsuariosController::validarLogin()): se queda un poco más de
             tiempo en pantalla que el resto de avisos, porque es un mensaje
             más largo y más importante de no perderse de un vistazo. --}}
        <x-notification type="info" :message="session('diagnostico_nuevo')" :duration="9" />
    </div>

    <style>
        .notification-stack {
            position: fixed;
            top: 82px;
            right: 24px;
            z-index: 1000;
            display: grid;
            gap: 12px;
            pointer-events: none;
        }

        .notification-stack .notification-item {
            pointer-events: auto;
        }

        /* En móvil, los avisos (success/error/warning/info) se anclan abajo a
           la derecha en vez de arriba a la derecha; env(safe-area-inset-bottom)
           evita que queden pegados a la barra/indicador inferior del móvil.
           Esta regla tiene que ir DESPUÉS de ".notification-stack" de arriba
           (mismo bloque): con la misma prioridad CSS, al tener la misma
           especificidad, gana la que aparece más tarde en el documento. */
        @media (max-width: 600px) {
            .notification-stack {
                top: auto;
                right: 12px;
                left: auto;
                bottom: calc(16px + env(safe-area-inset-bottom));
            }

            .notification-stack .notification-item {
                width: auto;
                max-width: calc(100vw - 24px);
            }
        }
    </style>

    <div id="main-content" class="page-content" tabindex="-1">
        @yield('contenido')
    </div>

    <footer aria-label="Pie de página"
        style="background: #004b6b; color: white; padding: 30px 20px 15px; margin-top: 40px;">
        <div
            style="max-width: 1000px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">MigraSense</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">Seguimiento inteligente de migraña</p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Enlaces</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">
                    <a href="{{ route('landingPage') }}" style="color: white; text-decoration: none;">Inicio</a> ·
                    <a href="{{ route('usuarios.createUsuario') }}"
                        style="color: white; text-decoration: none;">Registro</a>
                </p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Contacto</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">info@migrasense.com</p>
            </div>
        </div>
        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 20px 0 10px;">
        <p style="text-align: center; margin: 0; font-size: 0.8em; opacity: 0.6;">Copyright &copy; 2026 MigraSense.
            Todos los derechos reservados.</p>
    </footer>
</body>

</html>