<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/estilos.css?2') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


        <!-- Datatables -->
        <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet"/> 
        <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>
    <title>@yield('title')</title>
    @yield('scripts')
</head>
<body>
    <x-layouts.menu/>
    <div>
        @yield('contenido')
    </div>

    <footer style="background: #004b6b; color: white; padding: 30px 20px 15px; margin-top: 40px;">
        <div style="max-width: 1000px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">AquaSense</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">Monitoreo inteligente de humedad</p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Enlaces</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">
                    <a href="{{ route('landingPage') }}" style="color: white; text-decoration: none;">Inicio</a> ·
                    <a href="{{ route('usuarios.createUsuario') }}" style="color: white; text-decoration: none;">Registro</a>
                </p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Contacto</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">info@aquasense.com</p>
            </div>
        </div>
        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 20px 0 10px;">
        <p style="text-align: center; margin: 0; font-size: 0.8em; opacity: 0.6;">Copyright &copy; 2026 AquaSense. Todos los derechos reservados.</p>
    </footer>
</body>
</html>