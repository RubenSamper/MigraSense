@extends("layouts.default")
@section('title', 'Configuración')
@section('scripts')
    <style>
        .config-page {
            max-width: 640px;
            margin: 0 auto;
            padding: 42px 24px 56px;
        }

        .config-page h2 {
            margin: 0 0 8px;
            color: #173042;
        }

        .config-page p.subtitle {
            margin: 0 0 28px;
            color: #647783;
        }

        .config-section {
            margin-bottom: 28px;
            padding: 22px;
            border: 1px solid #dce7eb;
            border-radius: 12px;
            background: #fff;
        }

        .config-section h3 {
            margin: 0 0 6px;
            font-size: 1.05rem;
            color: #173042;
        }

        .config-section p.hint {
            margin: 0 0 16px;
            color: #647783;
            font-size: 0.88rem;
        }

        .config-field {
            margin-bottom: 12px;
        }

        .config-field label {
            display: block;
            margin-bottom: 4px;
            color: #647783;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .config-field input {
            width: 100%;
            box-sizing: border-box;
            min-height: 40px;
            padding: 8px 11px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
        }

        .config-radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .config-radio-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px;
            border: 1px solid #dce7eb;
            border-radius: 8px;
            cursor: pointer;
        }

        .config-radio-option:hover {
            border-color: #006994;
            background: #f2f7f8;
        }

        .config-radio-option input {
            margin-top: 3px;
        }

        .config-radio-option strong {
            display: block;
            color: #173042;
        }

        .config-radio-option span {
            display: block;
            margin-top: 2px;
            color: #647783;
            font-size: 0.85rem;
        }

        .config-location-status {
            margin: 12px 0 0;
            color: #647783;
            font-size: 0.85rem;
        }

        .config-button {
            min-height: 42px;
            padding: 10px 22px;
            border: 0;
            border-radius: 7px;
            background: #006994;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .config-button:hover {
            background: #004f70;
        }

        .config-button-secondary {
            min-height: 40px;
            padding: 8px 16px;
            border-radius: 7px;
            background: #f1f8fa;
            color: #006994;
            border: 1px solid #b9d1d9;
            font-weight: 700;
            cursor: pointer;
        }

        .config-button-secondary:hover {
            background: #e1f0f3;
        }

        .config-select-wrap {
            position: relative;
        }

        .config-select-wrap input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            min-height: 40px;
            padding: 8px 11px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
        }

        .config-station-list {
            position: absolute;
            z-index: 10;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            max-height: 220px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #cbd9de;
            border-radius: 7px;
            box-shadow: 0 8px 20px rgba(23, 48, 66, 0.12);
            display: none;
        }

        .config-station-list.is-open {
            display: block;
        }

        .config-station-option {
            padding: 9px 12px;
            cursor: pointer;
            font-size: 0.9rem;
            color: #173042;
        }

        .config-station-option:hover {
            background: #f2f7f8;
        }

        .config-station-empty {
            padding: 9px 12px;
            color: #647783;
            font-size: 0.85rem;
        }
    </style>
    <script>
        let estacionesDisponibles = [];
        let estacionSeleccionada = null;

        function cargarEstacionesAemet() {
            $('#configLocationStatus').text('Cargando lista de estaciones disponibles...');

            $.getJSON("{{ route('usuarios.estacionesAemetDisponibles') }}", function (estaciones) {
                estacionesDisponibles = estaciones;

                const latitudGuardada = '{{ $usuario->latitud }}';
                const longitudGuardada = '{{ $usuario->longitud }}';

                if (latitudGuardada && longitudGuardada) {
                    $('#configLocationStatus').text('Escribe el nombre de tu localidad o provincia para buscar.');

                    const coincidencia = estaciones.find((estacion) =>
                        Math.abs(estacion.lat - parseFloat(latitudGuardada)) < 0.01 &&
                        Math.abs(estacion.lon - parseFloat(longitudGuardada)) < 0.01
                    );

                    if (coincidencia) {
                        seleccionarEstacion(coincidencia);
                    }
                } else {
                    // Todavía no hay ninguna ubicación guardada: la
                    // detectamos nosotros mismos nada más cargar la página,
                    // sin que haga falta pulsar ningún botón. El botón
                    // "Usar mi ubicación actual" se deja igualmente, por si
                    // el navegador se la deniega o quiere repetir la
                    // detección más tarde.
                    usarUbicacionAutomatica();
                }
            }).fail(function () {
                $('#configLocationStatus').text('No se pudo cargar la lista de estaciones. Inténtalo de nuevo más tarde.');
            });
        }

        function filtrarEstaciones() {
            const texto = $('#buscadorEstacion').val().trim().toLowerCase();
            const lista = $('#listaEstaciones');

            if (texto.length < 2) {
                lista.removeClass('is-open').empty();
                return;
            }

            const coincidencias = estacionesDisponibles
                .filter((estacion) => estacion.nombre.toLowerCase().includes(texto))
                .slice(0, 20);

            lista.empty();

            if (coincidencias.length === 0) {
                lista.append('<div class="config-station-empty">No se encontraron estaciones con ese nombre.</div>');
            } else {
                coincidencias.forEach((estacion) => {
                    const opcion = $('<div class="config-station-option"></div>').text(estacion.nombre);
                    opcion.on('click', () => seleccionarEstacion(estacion));
                    lista.append(opcion);
                });
            }

            lista.addClass('is-open');
        }

        function seleccionarEstacion(estacion) {
            estacionSeleccionada = estacion;
            $('#buscadorEstacion').val(estacion.nombre);
            $('#latitud').val(estacion.lat);
            $('#longitud').val(estacion.lon);
            $('#listaEstaciones').removeClass('is-open').empty();
            $('#configLocationStatus').text(`Estación seleccionada: ${estacion.nombre}`);
        }

        function usarUbicacionAutomatica() {
            if (!navigator.geolocation) {
                $('#configLocationStatus').text('Tu navegador no permite obtener la ubicación automáticamente.');
                return;
            }

            if (estacionesDisponibles.length === 0) {
                $('#configLocationStatus').text('Espera a que termine de cargar la lista de estaciones.');
                return;
            }

            $('#configLocationStatus').text('Obteniendo tu ubicación...');

            navigator.geolocation.getCurrentPosition(
                (posicion) => {
                    const lat = posicion.coords.latitude;
                    const lon = posicion.coords.longitude;

                    let masCercana = null;
                    let distanciaMinima = null;

                    estacionesDisponibles.forEach((estacion) => {
                        const distancia = Math.hypot(estacion.lat - lat, estacion.lon - lon);
                        if (distanciaMinima === null || distancia < distanciaMinima) {
                            distanciaMinima = distancia;
                            masCercana = estacion;
                        }
                    });

                    if (masCercana) {
                        seleccionarEstacion(masCercana);
                    }
                },
                () => {
                    $('#configLocationStatus').text('No se pudo obtener tu ubicación automáticamente. Busca tu zona manualmente.');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
            );
        }

        function guardarConfiguracion(evento) {
            evento.preventDefault();

            const sensibilidad = $('input[name="sensibilidad_presion"]:checked').val();

            if (!sensibilidad) {
                alert('Selecciona tu nivel de sensibilidad.');
                return;
            }

            const latitud = $('#latitud').val();
            const longitud = $('#longitud').val();

            if (!latitud || !longitud) {
                alert('Selecciona una estación de la lista, o usa el botón para detectarla automáticamente.');
                return;
            }

            $.ajax({
                url: "{{ route('usuarios.guardarConfiguracion') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    latitud,
                    longitud,
                    sensibilidad_presion: sensibilidad
                },
                success: function () {
                    window.location.href = "{{ route('usuarios.dashboard') }}";
                },
                error: function () {
                    $('#configStatus').text('No se pudo guardar la configuración. Inténtalo de nuevo.');
                }
            });
        }

        $(function () {
            cargarEstacionesAemet();

            $('#buscadorEstacion').on('input', filtrarEstaciones);

            $(document).on('click', function (evento) {
                if (!$(evento.target).closest('.config-select-wrap').length) {
                    $('#listaEstaciones').removeClass('is-open');
                }
            });
        });
    </script>
@endsection
@section('contenido')
    <main class="config-page">
        <h2>Configuración</h2>
        <p class="subtitle">Ajusta tu ubicación y tu sensibilidad a los cambios de presión atmosférica.</p>

        <form onsubmit="guardarConfiguracion(event)">
            <div class="config-section">
                <h3>Ubicación</h3>
                <p class="hint">Elige entre las estaciones meteorológicas oficiales de AEMET, para asegurar que siempre haya datos disponibles.</p>
                <div class="config-field config-select-wrap">
                    <label for="buscadorEstacion">Busca tu localidad o provincia</label>
                    <input type="text" id="buscadorEstacion" placeholder="Ej: Almería, Madrid, Barcelona..." autocomplete="off">
                    <div id="listaEstaciones" class="config-station-list"></div>
                </div>
                <input type="hidden" id="latitud">
                <input type="hidden" id="longitud">
                <button type="button" class="config-button-secondary" onclick="usarUbicacionAutomatica()">Usar mi ubicación actual</button>
                <p id="configLocationStatus" class="config-location-status" role="status">Cargando lista de estaciones...</p>
            </div>

            <div class="config-section">
                <h3>Sensibilidad a la presión atmosférica</h3>
                <p class="hint">Esto determina cuándo te avisamos de un posible riesgo de migraña por cambios de presión.
                </p>
                <div class="config-radio-group">
                    <label class="config-radio-option">
                        <input type="radio" name="sensibilidad_presion" value="poco_sensible" {{ $usuario->sensibilidad_presion === 'poco_sensible' ? 'checked' : '' }}>
                        <span>
                            <strong>Poco sensible</strong>
                            <span>Solo avisos ante cambios de presión muy fuertes.</span>
                        </span>
                    </label>
                    <label class="config-radio-option">
                        <input type="radio" name="sensibilidad_presion" value="sensible" {{ $usuario->sensibilidad_presion === 'sensible' ? 'checked' : '' }}>
                        <span>
                            <strong>Sensible</strong>
                            <span>Avisos ante cambios moderados de presión.</span>
                        </span>
                    </label>
                    <label class="config-radio-option">
                        <input type="radio" name="sensibilidad_presion" value="muy_sensible" {{ $usuario->sensibilidad_presion === 'muy_sensible' ? 'checked' : '' }}>
                        <span>
                            <strong>Muy sensible</strong>
                            <span>Avisos ante cualquier cambio leve de presión.</span>
                        </span>
                    </label>
                </div>
            </div>

            <button type="submit" class="config-button">Guardar configuración</button>
            <p id="configStatus" class="config-location-status" role="status"></p>
        </form>
    </main>
@endsection