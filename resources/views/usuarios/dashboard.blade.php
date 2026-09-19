@extends("layouts.default")
@section('title','Dashboard')
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .weather-dashboard {
        --ink: #173042;
        --muted: #647783;
        --line: #dce7eb;
        --surface: #ffffff;
        --soft: #f2f7f8;
        --accent: #006994;
        --warm: #e15b45;
        max-width: 1120px;
        margin: 0 auto;
        padding: 42px 24px 56px;
        color: var(--ink);
    }

    .weather-intro {
        margin-bottom: 26px;
    }

    .weather-kicker {
        margin: 0 0 8px;
        color: var(--accent);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .weather-intro h2 {
        margin: 0;
        font-size: clamp(1.8rem, 4vw, 2.7rem);
        color: var(--ink);
    }

    .weather-intro p {
        margin: 10px 0 0;
        color: var(--muted);
    }

    .weather-controls,
    .weather-chart-panel,
    .weather-current {
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 12px 30px rgba(23, 48, 66, 0.07);
    }

    .weather-controls {
        display: flex;
        gap: 16px;
        align-items: end;
        flex-wrap: wrap;
        padding: 18px;
        border-radius: 12px;
    }

    .weather-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .weather-field label {
        color: var(--muted);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .weather-field select,
    .weather-field input {
        min-height: 40px;
        padding: 8px 11px;
        border: 1px solid #cbd9de;
        border-radius: 7px;
        color: var(--ink);
        background: #fff;
    }

    .weather-field select {
        min-width: 190px;
    }

    .weather-button {
        min-height: 40px;
        padding: 8px 18px;
        border: 0;
        border-radius: 7px;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .weather-button:hover {
        background: #004f70;
    }

    .weather-current {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1px;
        margin: 24px 0;
        overflow: hidden;
        border-radius: 12px;
        background: var(--line);
    }

    .weather-stat {
        padding: 20px 22px;
        background: var(--surface);
    }

    .weather-stat-label {
        color: var(--muted);
        font-size: 0.82rem;
    }

    .weather-stat-value {
        margin-top: 5px;
        font-size: 1.9rem;
        font-weight: 800;
    }

    .weather-stat-value.temperature {
        color: var(--warm);
    }

    .weather-stat-value.wind {
        color: var(--accent);
    }

    .weather-chart-panel {
        padding: 22px;
        border-radius: 12px;
    }

    .weather-chart-heading {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: baseline;
        margin-bottom: 18px;
    }

    .weather-chart-heading h3 {
        margin: 0;
        font-size: 1.1rem;
    }

    .weather-chart-heading span {
        color: var(--muted);
        font-size: 0.85rem;
    }

    .weather-days {
        display: grid;
        grid-template-columns: repeat(7, minmax(76px, 1fr));
        gap: 8px;
        margin-bottom: 24px;
    }

    .weather-day {
        min-height: 58px;
        padding: 8px 5px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--soft);
        color: var(--accent);
        cursor: pointer;
        font-weight: 700;
    }

    .weather-day:hover,
    .weather-day.is-active {
        border-color: var(--accent);
        background: var(--accent);
        color: #fff;
    }

    .weather-chart {
        position: relative;
        height: 360px;
    }

    .weather-status {
        min-height: 20px;
        margin: 12px 0 0;
        color: var(--muted);
        font-size: 0.85rem;
    }

    .weather-table-wrap {
        margin-top: 24px;
        overflow-x: auto;
    }

    .weather-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--ink);
        font-size: 0.9rem;
    }

    .weather-table th,
    .weather-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }

    .weather-table th {
        color: var(--muted);
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    @media (max-width: 700px) {
        .weather-dashboard {
            padding: 28px 16px 40px;
        }

        .weather-controls>* {
            width: 100%;
        }

        .weather-field select,
        .weather-field input,
        .weather-button {
            width: 100%;
        }

        .weather-days {
            grid-template-columns: repeat(4, 1fr);
        }

        .weather-chart {
            height: 300px;
        }
    }
</style>
<script>
    let chart = null;
    let pronostico = null;

    function nombreDia(fecha) {
        return new Intl.DateTimeFormat('es-ES', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        }).format(new Date(`${fecha}T12:00:00`));
    }

    function mostrarDia(indice) {
        const dia = pronostico.dias[indice];
        const horasDelDia = pronostico.hourly.time
            .map((hora, horaIndice) => ({
                hora,
                horaIndice
            }))
            .filter(({
                hora
            }) => hora.slice(0, 10) === dia);

        if (chart) chart.destroy();

        chart = new Chart(document.getElementById('chart'), {
            type: 'line',
            data: {
                labels: horasDelDia.map(({
                    hora
                }) => hora.slice(11, 16)),
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: horasDelDia.map(({
                        horaIndice
                    }) => pronostico.hourly.temperature_2m[horaIndice]),
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.12)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'temperatura'
                }, {
                    label: 'Viento (km/h)',
                    data: horasDelDia.map(({
                        horaIndice
                    }) => pronostico.hourly.wind_speed_10m[horaIndice]),
                    borderColor: '#006994',
                    backgroundColor: 'rgba(0, 105, 148, 0.1)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'viento'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    title: {
                        display: true,
                        text: `Tiempo del ${nombreDia(dia)}`
                    }
                },
                scales: {
                    temperatura: {
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Temperatura (°C)'
                        }
                    },
                    viento: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Viento (km/h)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        document.querySelectorAll('[data-dia-indice]').forEach((boton) => {
            const seleccionado = Number(boton.dataset.diaIndice) === indice;
            boton.style.background = seleccionado ? '#006994' : 'white';
            boton.style.color = seleccionado ? 'white' : '#006994';
            boton.classList.toggle('is-active', seleccionado);
        });
    }

    function mostrarTablaSemana() {
        const filas = pronostico.dias.map((fecha) => {
            const indices = pronostico.hourly.time
                .map((hora, indice) => ({ hora, indice }))
                .filter(({ hora }) => hora.slice(0, 10) === fecha)
                .map(({ indice }) => indice);
            const temperaturas = indices.map((indice) => pronostico.hourly.temperature_2m[indice]);
            const vientos = indices.map((indice) => pronostico.hourly.wind_speed_10m[indice]);

            return `<tr>
                <td>${new Intl.DateTimeFormat('es-ES', { weekday: 'long', day: 'numeric', month: 'short' }).format(new Date(`${fecha}T12:00:00`))}</td>
                <td>${Math.min(...temperaturas)} °C / ${Math.max(...temperaturas)} °C</td>
                <td>${Math.max(...vientos)} km/h</td>
            </tr>`;
        }).join('');

        $('#tablaSemana').html(filas);
    }

    function cargarDatos() {
        const lat = $('#latitud').val();
        const lon = $('#longitud').val();

        if (!lat || !lon) {
            alert('Selecciona una zona o introduce latitud y longitud.');
            return;
        }

        $('#weatherStatus').text('Cargando previsión...');

        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,wind_speed_10m&forecast_days=7&timezone=auto`;

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                data.dias = [...new Set(data.hourly.time.map((hora) => hora.slice(0, 10)))];
                pronostico = data;
                $('#currentTemp').text(`${data.current.temperature_2m}°C`);
                $('#currentWind').text(`${data.current.wind_speed_10m} km/h`);

                $('#diasSemana').html(data.dias.map((fecha, indice) => `
                    <button type="button" class="weather-day" data-dia-indice="${indice}" onclick="mostrarDia(${indice})">
                        ${new Intl.DateTimeFormat('es-ES', { weekday: 'short', day: 'numeric' }).format(new Date(`${fecha}T12:00:00`))}
                    </button>
                `).join(''));

                mostrarDia(0);
                mostrarTablaSemana();
                $('#weatherStatus').text('Previsión actualizada para los próximos 7 días.');
                guardarZona(lat, lon);
            },
            error: function() {
                $('#weatherStatus').text('No se pudo cargar la previsión. Revisa la ubicación e inténtalo de nuevo.');
                alert('Error al cargar los datos meteorológicos.');
            }
        });
    }

    function guardarZona(lat, lon) {
        $.ajax({
            url: "{{ route('usuarios.guardarZona') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                latitud: lat,
                longitud: lon
            }
        });
    }

    function preseleccionarZona() {
        const [lat, lon] = $('#zona').val().split(',');
        if (lat && lon) {
            $('#latitud').val(lat);
            $('#longitud').val(lon);
            cargarDatos();
        }
    }

    function seleccionarZonaGuardada() {
        const latitud = Number(latitudGuardada);
        const longitud = Number(longitudGuardada);
        const opcion = [...document.querySelector('#zona').options].find((elemento) => {
            const [lat, lon] = elemento.value.split(',').map(Number);
            return Math.abs(lat - latitud) < 0.0001 && Math.abs(lon - longitud) < 0.0001;
        });

        if (opcion) document.querySelector('#zona').value = opcion.value;
    }

    const latitudGuardada = '{{ Auth::user()->latitud }}';
    const longitudGuardada = '{{ Auth::user()->longitud }}';

    $(function() {
        if (latitudGuardada && longitudGuardada) {
            seleccionarZonaGuardada();
            $('#latitud').val(latitudGuardada);
            $('#longitud').val(longitudGuardada);
            cargarDatos();
        }
    });
</script>
@endsection
@section('contenido')
<main class="weather-dashboard">
    <header class="weather-intro">
        <p class="weather-kicker">Panel meteorológico</p>
        <h2>Hola, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>
        <p>Consulta las condiciones actuales y explora la previsión hora a hora.</p>
    </header>

    <section class="weather-controls" aria-label="Ubicación de la previsión">
        <div class="weather-field">
            <label for="zona">Zona predefinida</label>
            <select id="zona" onchange="preseleccionarZona()">
                <option value="">-- Selecciona --</option>
                <option value="36.8340,-2.4637">Almería</option>
                <option value="37.1882,-3.6067">Granada</option>
                <option value="37.3891,-5.9845">Sevilla</option>
                <option value="40.4168,-3.7038">Madrid</option>
                <option value="41.3874,2.1686">Barcelona</option>
                <option value="41.6488,-0.8891">Zaragoza</option>
                <option value="39.4699,-0.3763">Valencia</option>
                <option value="43.2630,-2.9350">Bilbao</option>
                <option value="42.8782,-8.5448">Santiago</option>
                <option value="39.8628,4.0273">Menorca</option>
                <option value="28.4636,-16.2518">Tenerife</option>
            </select>
        </div>
        <div class="weather-field">
            <label for="latitud">Latitud</label>
            <input type="text" id="latitud" placeholder="Ej: 52.52">
        </div>
        <div class="weather-field">
            <label for="longitud">Longitud</label>
            <input type="text" id="longitud" placeholder="Ej: 13.41">
        </div>
        <button type="button" class="weather-button" onclick="cargarDatos()">Actualizar previsión</button>
    </section>

    <section class="weather-current" aria-label="Condiciones actuales">
        <div class="weather-stat">
            <div class="weather-stat-label">Temperatura actual</div>
            <div id="currentTemp" class="weather-stat-value temperature">--</div>
        </div>
        <div class="weather-stat">
            <div class="weather-stat-label">Viento actual</div>
            <div id="currentWind" class="weather-stat-value wind">--</div>
        </div>
    </section>

    <section class="weather-chart-panel" aria-label="Previsión de siete días">
        <div class="weather-chart-heading">
            <h3>Previsión por horas</h3>
            <span>Selecciona un día para explorar</span>
        </div>
        <div id="diasSemana" class="weather-days"></div>
        <div class="weather-chart"><canvas id="chart"></canvas></div>
        <p id="weatherStatus" class="weather-status" role="status">Selecciona una ubicación para cargar la previsión.</p>
        <div class="weather-table-wrap">
            <table class="weather-table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Temperatura mínima / máxima</th>
                        <th>Viento máximo</th>
                    </tr>
                </thead>
                <tbody id="tablaSemana"></tbody>
            </table>
        </div>
    </section>

</main>
@endsection