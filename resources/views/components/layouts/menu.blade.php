<header class="header">
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: #006994; color: white;">
        <a href="{{ route('landingPage') }}" style="color: white; text-decoration: none; font-size: 1.4em; font-weight: bold;">AquaSense</a>
        <div style="display: flex; gap: 20px;">
            <a href="{{ route('usuarios.createUsuario') }}" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 5px;">Crear usuario</a>
            <a href="{{ route('usuarios.login') }}" style="color: white; text-decoration: none; padding: 8px 16px; background: white; color: #006994; border-radius: 5px; font-weight: bold;">Iniciar sesión</a>
        </div>
    </nav>
</header>