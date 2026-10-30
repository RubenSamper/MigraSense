<header class="header">
    <style>
        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            box-sizing: border-box;
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 8px;
            background: rgba(0, 52, 76, 0.28);
            box-shadow: 0 2px 5px rgba(0, 35, 52, 0.18);
            color: white;
            text-decoration: none;
            font-size: 0.9em;
            padding: 8px 16px;
            font-weight: 700;
            transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 9px rgba(0, 35, 52, 0.25);
            transform: translateY(-1px);
        }

        .nav-link:focus-visible {
            outline: 3px solid #ffd166;
            outline-offset: 3px;
        }

        .nav-link-border {
            background: transparent;
        }

        .nav-link-border:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
        }

        .nav-link-primary {
            background: white;
            color: #006994;
            font-weight: bold;
        }

        .nav-link-primary:hover {
            background: #e6f0f5;
            color: #004b6b;
        }

        .nav-link-active {
            background: #fff;
            border-color: #fff;
            color: #005578;
            box-shadow: 0 4px 10px rgba(0, 35, 52, 0.25);
        }
    </style>
    <nav class="site-nav" aria-label="Navegación principal">
        <a class="site-brand" href="{{ route('landingPage') }}">
            <img src="{{ asset('images/migrasense-logo.svg') }}" alt=""
                style="height: 80px; width: auto; vertical-align: middle; margin-right: 8px;">
        </a>

        <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Abrir menú"
            aria-expanded="false" aria-controls="site-nav-links">
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
        </button>

        <div class="site-nav-links" id="site-nav-links">
            @auth
                @if(Auth::user()->rol_id == 2)
                    <a href="{{ route('usuarios.dashboard') }}"
                        class="nav-link {{ request()->routeIs('usuarios.dashboard') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.dashboard')) aria-current="page" @endif>Inicio</a>
                    <a href="{{ route('usuarios.seguimientoMigrana') }}"
                        class="nav-link {{ request()->routeIs('usuarios.seguimientoMigrana') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.seguimientoMigrana')) aria-current="page" @endif>Seguimiento</a>
                    <a href="{{ route('usuarios.configuracion') }}"
                        class="nav-link {{ request()->routeIs('usuarios.configuracion') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.configuracion')) aria-current="page" @endif>Configuración</a>
                @endif

                @if(Auth::user()->rol_id == 1)
                    <a href="{{ route('usuarios.obtenerListadoUsuarios') }}"
                        class="nav-link {{ request()->routeIs('usuarios.obtenerListadoUsuarios') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.obtenerListadoUsuarios')) aria-current="page" @endif>Pacientes</a>
                    <a href="{{ route('usuarios.listarMedicos') }}"
                        class="nav-link {{ request()->routeIs('usuarios.listarMedicos') || request()->routeIs('usuarios.crearMedico') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.listarMedicos') || request()->routeIs('usuarios.crearMedico')) aria-current="page" @endif>Médicos</a>
                @endif

                @if(Auth::user()->rol_id == 3)
                    <a href="{{ route('usuarios.medico.misPacientes') }}"
                        class="nav-link {{ request()->routeIs('usuarios.medico.*') ? 'nav-link-active' : '' }}"
                        @if(request()->routeIs('usuarios.medico.*')) aria-current="page" @endif>Mis pacientes</a>
                @endif

                <span class="site-user">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</span>
                <a href="{{ route('usuarios.logout') }}" class="nav-link nav-link-border">Cerrar sesión</a>
            @else
                <a href="{{ route('usuarios.createUsuario') }}" class="nav-link nav-link-border">Crear usuario</a>
                <a href="{{ route('usuarios.login') }}" class="nav-link nav-link-primary">Iniciar sesión</a>
            @endauth
        </div>
    </nav>

    <style>
        .site-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 68px;
            padding: 12px clamp(16px, 4vw, 30px);
            box-sizing: border-box;
            background: #006994;
            color: white;
        }

        .site-brand {
            flex: 0 0 auto;
            color: white;
            text-decoration: none;
            font-size: 1.4em;
            font-weight: bold;
        }

        .site-nav-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .site-user {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .9em;
        }

        /* Botón de hamburguesa: oculto en escritorio, visible solo en móvil */
        .nav-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 46px;
            height: 46px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            background: rgba(0, 52, 76, 0.28);
            cursor: pointer;
        }

        .nav-toggle:focus-visible {
            outline: 3px solid #ffd166;
            outline-offset: 3px;
        }

        .nav-toggle-bar {
            display: block;
            width: 22px;
            height: 2px;
            border-radius: 2px;
            background: white;
            transition: transform 0.2s, opacity 0.2s;
        }

        /* Al abrir el menú, las tres rayas se convierten en una X */
        .nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        @media (max-width: 760px) {
            .site-nav {
                flex-wrap: wrap;
                gap: 10px;
            }

            .site-brand img {
                height: 56px !important;
            }

            .nav-toggle {
                display: flex;
            }

            /* El menú queda oculto hasta que se pulsa el botón */
            .site-nav-links {
                display: none;
                flex-direction: column;
                align-items: stretch;
                flex-basis: 100%;
                gap: 8px;
                padding-top: 6px;
            }

            .site-nav-links.is-open {
                display: flex;
            }

            .site-nav-links .nav-link {
                width: 100%;
                text-align: center;
            }

            .site-user {
                max-width: 100%;
                text-align: center;
            }
        }
    </style>

    <script>
        (function () {
            var boton = document.getElementById('nav-toggle');
            var menu = document.getElementById('site-nav-links');
            if (!boton || !menu) return;

            function cerrar() {
                menu.classList.remove('is-open');
                boton.setAttribute('aria-expanded', 'false');
                boton.setAttribute('aria-label', 'Abrir menú');
            }

            boton.addEventListener('click', function () {
                var abierto = menu.classList.toggle('is-open');
                boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
                boton.setAttribute('aria-label', abierto ? 'Cerrar menú' : 'Abrir menú');
            });

            // Escape cierra el menú, y al volver a pantalla ancha se restablece
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrar();
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 760) cerrar();
            });
        })();
    </script>
</header>