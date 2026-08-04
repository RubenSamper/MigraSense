<header class="header">
    <style>
        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 0.9em;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        .nav-link-border {
            border: 1px solid rgba(255, 255, 255, 0.6);
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
    </style>
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: #006994; color: white;">
        <a href="{{ route('landingPage') }}" style="color: white; text-decoration: none; font-size: 1.4em; font-weight: bold;">AquaSense</a>
        <div style="display: flex; gap: 15px; align-items: center;">
            @auth
                <a href="{{ route('usuarios.dashboard') }}" class="nav-link">Dashboard</a>
                @if(Auth::user()->rol_id == 1)
                    <a href="{{ route('usuarios.obtenerListadoUsuarios') }}" class="nav-link">Usuarios</a>
                @endif
                <span style="font-size: 0.9em;">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</span>
                <a href="{{ route('usuarios.logout') }}" class="nav-link nav-link-border">Cerrar sesión</a>
            @else
                <a href="{{ route('usuarios.createUsuario') }}" class="nav-link nav-link-border">Crear usuario</a>
                <a href="{{ route('usuarios.login') }}" class="nav-link nav-link-primary">Iniciar sesión</a>
            @endauth
        </div>
    </nav>
</header>