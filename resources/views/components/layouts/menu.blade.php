<header class="header">
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: #006994; color: white;">
        <a href="{{ route('landingPage') }}" style="color: white; text-decoration: none; font-size: 1.4em; font-weight: bold;">AquaSense</a>
        <div style="display: flex; gap: 15px; align-items: center;">
            @auth
                <span style="font-size: 0.9em;">{{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</span>
                <a href="{{ route('usuarios.logout') }}" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 5px; font-size: 0.9em;">Cerrar sesión</a>
            @else
                <a href="{{ route('usuarios.createUsuario') }}" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 5px; font-size: 0.9em;">Crear usuario</a>
                <a href="{{ route('usuarios.login') }}" style="color: white; text-decoration: none; padding: 8px 16px; background: white; color: #006994; border-radius: 5px; font-weight: bold; font-size: 0.9em;">Iniciar sesión</a>
            @endauth
        </div>
    </nav>
</header>