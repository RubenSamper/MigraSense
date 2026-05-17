@extends("layouts.default")
@section('title','Dashboard')
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart = null;

    function cargarDatos() {
        const lat = $('#latitud').val();
        const lon = $('#longitud').val();

        if (!lat || !lon) {
            alert('Selecciona una zona o introduce latitud y longitud.');
            return;
        }

        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m`;

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                const horas = data.hourly.time.slice(0, 24).map(t => {
                    const d = new Date(t + 'Z');
                    return d.getHours() + ':00';
                });
                const temperaturas = data.hourly.temperature_2m.slice(0, 24);
                const humedades = data.hourly.relative_humidity_2m.slice(0, 24);

                $('#currentTemp').text(data.current.temperature_2m + '°C');
                $('#currentWind').text(data.current.wind_speed_10m + ' km/h');

                if (chart) chart.destroy();

                chart = new Chart(document.getElementById('chart'), {
                    type: 'line',
                    data: {
                        labels: horas,
                        datasets: [{
                            label: 'Temperatura (°C)',
                            data: temperaturas,
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y'
                        }, {
                            label: 'Humedad (%)',
                            data: humedades,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            tension: 0.3,
                            yAxisID: 'y1'
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
                                text: 'Pronóstico próximas 24 horas'
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: { display: true, text: 'Temperatura (°C)' }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: { display: true, text: 'Humedad (%)' },
                                min: 0,
                                max: 100,
                                grid: { drawOnChartArea: false }
                            }
                        }
                    }
                });
            },
            error: function() {
                alert('Error al cargar los datos meteorológicos.');
            }
        });
    }

    function preseleccionarZona() {
        const select = $('#zona')[0];
        const [lat, lon] = select.value.split(',');
        if (lat && lon) {
            $('#latitud').val(lat);
            $('#longitud').val(lon);
            cargarDatos();
        }
    }
</script>
@endsection
@section('contenido')
<div style="max-width: 1000px; margin: 0 auto; padding: 30px 20px;">
    <h2 style="color: #006994;">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>
    <br>

    <div style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap; margin-bottom: 20px;">
        <div>
            <label for="zona">Zona predefinida</label>
            <select id="zona" onchange="preseleccionarZona()" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
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
        <div>
            <label for="latitud">Latitud</label>
            <input type="text" id="latitud" placeholder="Ej: 52.52" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 120px;">
        </div>
        <div>
            <label for="longitud">Longitud</label>
            <input type="text" id="longitud" placeholder="Ej: 13.41" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 120px;">
        </div>
        <button onclick="cargarDatos()" style="padding: 8px 20px; background: #006994; color: white; border: none; border-radius: 5px; cursor: pointer;">Cargar</button>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; text-align: center; flex: 1; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="color: #888; font-size: 0.9em;">Temperatura actual</div>
            <div id="currentTemp" style="font-size: 2em; font-weight: bold; color: #e74c3c;">--</div>
        </div>
        <div style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; text-align: center; flex: 1; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="color: #888; font-size: 0.9em;">Viento actual</div>
            <div id="currentWind" style="font-size: 2em; font-weight: bold; color: #3498db;">--</div>
        </div>
    </div>

    <canvas id="chart" height="200" style="max-height: 200px;"></canvas>
</div>
@endsection
