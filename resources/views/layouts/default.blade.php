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

    <footer class="footer">
        <p>Copyright © 2026 - Todos los derechos reservados</p>
    </footer>
</footer>
</body>
</html>