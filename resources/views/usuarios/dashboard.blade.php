@extends("layouts.default")
@section('title', 'Inicio')
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
            max-width: 900px;
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

        .risk-card {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 28px;
            padding: 28px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--surface);
            box-shadow: 0 12px 30px rgba(23, 48, 66, 0.07);
        }

        .risk-face {
            flex-shrink: 0;
            width: 110px;
            height: 110px;
        }

        .risk-text h3 {
            margin: 0 0 6px;
            font-size: 1.3rem;
        }

        .risk-text p {
            margin: 0;
            color: var(--muted);
        }

        .risk-card.verde {
            border-color: #bfe3c5;
            background: #f3fbf4;
        }

        .risk-card.amarillo {
            border-color: #f3dd9c;
            background: #fffaf0;
        }

        .risk-card.rojo {
            border-color: #f0b8ae;
            background: #fff3f1;
        }

        .risk-card.desconocido {
            border-color: var(--line);
        }

        .weather-current {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1px;
            margin-bottom: 28px;
            overflow: hidden;
            border-radius: 12px;
            background: var(--line);
        }

        .weather-stat {
            padding: 24px 22px 18px;
            background: var(--surface);
        }

        .weather-stat-label {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .weather-stat-value {
            margin-top: 6px;
            font-size: 2.1rem;
            font-weight: 800;
        }

        .weather-stat-value.temperature {
            color: var(--warm);
        }

        .weather-stat-value.pressure {
            color: var(--accent);
        }

        /* Pie de la sección: nombre de la estación (y "estimado" si aplica),
           una sola vez para las dos cifras en vez de repetido en cada una. */
        .weather-stat-footer {
            grid-column: 1 / -1;
            padding: 10px 22px;
            background: var(--surface);
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 0.75rem;
        }

        .weather-chart-panel {
            padding: 24px;
            border-radius: 12px;
            background: var(--surface);
            border: 1px solid var(--line);
            box-shadow: 0 10px 24px rgba(23, 48, 66, 0.06);
            margin-bottom: 28px;
        }

        .weather-chart-title {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .weather-chart-title h3 {
            margin: 0;
            font-size: 1.1rem;
        }

        /* Icono "ⓘ" con el texto largo de la leyenda en un tooltip, en vez
           de tenerlo siempre escrito debajo del título. */
        .info-tooltip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1;
            cursor: help;
        }

        .info-tooltip:hover,
        .info-tooltip:focus-visible {
            color: var(--accent);
        }

        .weather-chart {
            position: relative;
            height: 320px;
            margin-top: 16px;
        }

        .weather-empty {
            padding: 28px;
            border-radius: 12px;
            border: 1px dashed var(--line);
            color: var(--muted);
            text-align: center;
            margin-bottom: 24px;
        }

        .weather-empty a {
            color: var(--accent);
            font-weight: 700;
        }

        .quick-action {
            margin-bottom: 24px;
            text-align: center;
        }

        .quick-action-button {
            min-height: 46px;
            padding: 12px 28px;
            border: 0;
            border-radius: 10px;
            background: var(--accent);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
        }

        .quick-action-button:hover {
            background: #004f70;
        }

        .migraine-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1500;
            background: rgba(23, 48, 66, 0.45);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .migraine-modal-overlay.is-open {
            display: flex;
        }

        .migraine-modal {
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            background: #fff;
            border-radius: 14px;
            padding: 24px;
        }

        .migraine-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .migraine-modal-header h3 {
            margin: 0;
            color: var(--ink);
        }

        .migraine-modal-close {
            border: 0;
            background: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            color: var(--muted);
        }

        .migraine-modal-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .migraine-modal-row>div {
            flex: 1;
            min-width: 160px;
        }

        .migraine-modal-field {
            margin-bottom: 16px;
        }

        .migraine-modal-field label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .migraine-modal-field textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 8px 11px;
            border: 1px solid var(--line);
            border-radius: 7px;
            font: inherit;
        }

        .date-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .date-field label {
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .date-field input {
            min-height: 40px;
            padding: 8px 11px;
            border: 1px solid var(--line);
            border-radius: 7px;
            font: inherit;
        }

        .field-error {
            margin: 8px 0 0;
            color: #b42318;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .migraine-modal-submit {
            min-height: 42px;
            padding: 10px 22px;
            border: 0;
            border-radius: 7px;
            background: var(--accent);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .risk-card {
                flex-direction: column;
                text-align: center;
            }

            .weather-current {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .migraine-modal-row {
                flex-direction: column;
            }
        }
    </style>
    <script>
        function abrirModalMigrana() {
            $('#modalMigrana').addClass('is-open');
        }

        function limpiarErroresModal() {
            document.querySelectorAll('#modalMigrana .field-error').forEach((el) => el.textContent = '');
        }

        function mostrarErrorCampoModal(id, mensaje) {
            const el = document.getElementById(id);
            if (el) el.textContent = mensaje;
        }

        function cerrarModalMigrana() {
            $('#modalMigrana').removeClass('is-open');
            limpiarErroresModal();
        }

        @if ($datosClima)
            const observaciones = @json($datosClima['observaciones']);
            const previsionTemperatura = @json($datosClima['prevision_temperatura'] ?? []);
            const previsionPresion = @json($datosClima['prevision_presion'] ?? []);
        @endif

        $(function () {
            @if ($datosClima)
                // Horas con datos reales (ya pasadas) + horas con previsión (resto del día).
                const horasReales = observaciones.map((obs) => obs.hora);
                const horasPrevistas = Array.from(new Set([
                    ...previsionTemperatura.map((p) => p.hora),
                    ...previsionPresion.map((p) => p.hora)
                ])).sort();
                const labels = horasReales.concat(horasPrevistas);

                // Serie de datos reales: se rellena con null en las horas de
                // previsión para que esa parte de la línea no se dibuje.
                const temperaturaReal = observaciones.map((obs) => obs.temperatura)
                    .concat(horasPrevistas.map(() => null));
                const presionReal = observaciones.map((obs) => obs.presion)
                    .concat(horasPrevistas.map(() => null));

                // Serie de previsión: null durante las horas reales, salvo en el
                // último punto real (se repite ahí) para que la línea discontinua
                // arranque justo donde termina la línea de datos reales, sin salto.
                const ultimaTemperaturaReal = observaciones.length ? observaciones[observaciones.length - 1].temperatura : null;
                const ultimaPresionReal = observaciones.length ? observaciones[observaciones.length - 1].presion : null;

                function mapaPorHora(lista) {
                    const mapa = {};
                    lista.forEach((p) => { mapa[p.hora] = p.valor; });
                    return mapa;
                }
                const mapaTemperaturaPrevista = mapaPorHora(previsionTemperatura);
                const mapaPresionPrevista = mapaPorHora(previsionPresion);

                const temperaturaPrevista = horasReales.map((_, i) => i === horasReales.length - 1 ? ultimaTemperaturaReal : null)
                    .concat(horasPrevistas.map((h) => mapaTemperaturaPrevista[h] ?? null));
                const presionPrevista = horasReales.map((_, i) => i === horasReales.length - 1 ? ultimaPresionReal : null)
                    .concat(horasPrevistas.map((h) => mapaPresionPrevista[h] ?? null));

                new Chart(document.getElementById('chart'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Temperatura',
                            data: temperaturaReal,
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.12)',
                            tension: 0.3,
                            fill: true,
                            spanGaps: false,
                            yAxisID: 'temperatura'
                        }, {
                            label: 'Temperatura (previsión)',
                            data: temperaturaPrevista,
                            borderColor: '#e74c3c',
                            backgroundColor: 'transparent',
                            borderDash: [6, 4],
                            tension: 0.3,
                            fill: false,
                            pointRadius: 2,
                            spanGaps: true,
                            yAxisID: 'temperatura'
                        }, {
                            label: 'Presión',
                            data: presionReal,
                            borderColor: '#006994',
                            backgroundColor: 'rgba(0, 105, 148, 0.1)',
                            tension: 0.3,
                            fill: true,
                            spanGaps: false,
                            yAxisID: 'presion'
                        }, {
                            label: 'Presión (estimada)',
                            data: presionPrevista,
                            borderColor: '#006994',
                            backgroundColor: 'transparent',
                            borderDash: [6, 4],
                            tension: 0.3,
                            fill: false,
                            pointRadius: 2,
                            spanGaps: true,
                            yAxisID: 'presion'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        // Sin números en los ejes: el valor exacto de cada
                        // hora se ve al pasar el ratón (tooltip), así el
                        // gráfico queda más limpio y menos "numerado".
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            x: {
                                ticks: { maxTicksLimit: 8 }
                            },
                            temperatura: {
                                position: 'left',
                                title: { display: true, text: 'Temperatura (°C)' },
                                ticks: { display: false },
                                grid: { drawOnChartArea: false }
                            },
                            presion: {
                                position: 'right',
                                title: { display: true, text: 'Presión (hPa)' },
                                ticks: { display: false },
                                grid: { drawOnChartArea: false }
                            }
                        }
                    }
                });
            @endif

            document.getElementById('modal_hora_inicio').addEventListener('input', actualizarLimitesModal);
            document.getElementById('modal_hora_termina').addEventListener('input', actualizarLimitesModal);
            actualizarLimitesModal();

            $('#formMigranaModal').on('submit', function (evento) {
                evento.preventDefault();

                limpiarErroresModal();

                let hayErrores = false;

                if (!$('input[name="tipo_migrana"]:checked').length) {
                    mostrarErrorCampoModal('modal_error_tipo_migrana', 'Selecciona un tipo de migraña.');
                    hayErrores = true;
                }
                if (!$('input[name="intensidad"]:checked').length) {
                    mostrarErrorCampoModal('modal_error_intensidad', 'Selecciona la intensidad.');
                    hayErrores = true;
                }
                if (!$('#modal_hora_inicio').val()) {
                    mostrarErrorCampoModal('modal_error_hora_inicio', 'Indica la hora de inicio.');
                    hayErrores = true;
                }
                if (!$('#modal_hora_termina').val()) {
                    mostrarErrorCampoModal('modal_error_hora_termina', 'Indica la hora de fin.');
                    hayErrores = true;
                }

                if (hayErrores) {
                    return;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function () {
                        window.location.href = window.location.pathname + '?registrado=1';
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errores = xhr.responseJSON.errors;
                            if (errores.tipo_migrana) mostrarErrorCampoModal('modal_error_tipo_migrana', errores.tipo_migrana[0]);
                            if (errores.intensidad) mostrarErrorCampoModal('modal_error_intensidad', errores.intensidad[0]);
                            if (errores.hora_inicio) mostrarErrorCampoModal('modal_error_hora_inicio', errores.hora_inicio[0]);
                            if (errores.hora_termina) mostrarErrorCampoModal('modal_error_hora_termina', errores.hora_termina[0]);
                        }
                    }
                });
            });

            const paramsDashboard = new URLSearchParams(window.location.search);
            const stack = document.querySelector('.notification-stack');

            function mostrarNotificacionExito(mensaje) {
                if (!stack) return;

                const item = document.createElement('div');
                item.className = 'notification-item';
                item.innerHTML = `
            <div class="notification notification-success" role="status" aria-live="polite">
                <div class="notification-mark" aria-hidden="true">✓</div>
                <div class="notification-content">
                    <strong>Éxito</strong>
                    <span>${mensaje}</span>
                </div>
            </div>
        `;
                stack.appendChild(item);
            }

            if (paramsDashboard.get('registrado') === '1') {
                paramsDashboard.delete('registrado');
            }

            if (paramsDashboard.get('config_guardada') === '1') {
                mostrarNotificacionExito('Configuración guardada correctamente.');
                paramsDashboard.delete('config_guardada');
            }

            const nuevaUrlDashboard = window.location.pathname + (paramsDashboard.toString() ? '?' + paramsDashboard.toString() : '');
            window.history.replaceState({}, '', nuevaUrlDashboard);
        });

        function actualizarLimitesModal() {
            const inicio = document.getElementById('modal_hora_inicio');
            const fin = document.getElementById('modal_hora_termina');

            if (inicio.value) {
                fin.min = inicio.value;
            }
        }
    </script>
@endsection
@section('contenido')
    <main class="weather-dashboard">
        <header class="weather-intro">
            <p class="weather-kicker">Panel meteorológico</p>
            <h2>Hola, {{ Auth::user()->nombre }}</h2>
            <p>Riesgo de migraña según la presión atmosférica en tu zona.</p>
        </header>

        @if (!$datosClima)
            <div class="weather-empty">
                <p>Todavía no tenemos tu ubicación. Ve a <a href="{{ route('usuarios.configuracion') }}">Configuración</a> para
                    activarla y empezar a ver tu riesgo diario.</p>
            </div>
        @else
            <section class="risk-card {{ $riesgo['nivel'] }}">
                <div class="risk-face">
                    @if ($riesgo['nivel'] === 'verde')
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="46" fill="#4caf6d" />
                            <circle cx="34" cy="42" r="6" fill="#173042" />
                            <circle cx="66" cy="42" r="6" fill="#173042" />
                            <path d="M30 62 Q50 82 70 62" stroke="#173042" stroke-width="5" fill="none" stroke-linecap="round" />
                        </svg>
                    @elseif ($riesgo['nivel'] === 'amarillo')
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="46" fill="#f2c94c" />
                            <circle cx="34" cy="42" r="6" fill="#173042" />
                            <circle cx="66" cy="42" r="6" fill="#173042" />
                            <path d="M32 66 Q50 60 68 66" stroke="#173042" stroke-width="5" fill="none" stroke-linecap="round" />
                        </svg>
                    @elseif ($riesgo['nivel'] === 'rojo')
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="46" fill="#e15b45" />
                            <circle cx="34" cy="42" r="6" fill="#173042" />
                            <circle cx="66" cy="42" r="6" fill="#173042" />
                            <path d="M30 70 Q50 54 70 70" stroke="#173042" stroke-width="5" fill="none" stroke-linecap="round" />
                        </svg>
                    @endif
                </div>
                <div class="risk-text">
                    @if ($riesgo['nivel'] === 'verde')
                        <h3>Riesgo bajo</h3>
                        <p>La presión atmosférica está estable en tu zona.</p>
                    @elseif ($riesgo['nivel'] === 'amarillo')
                        <h3>Riesgo moderado</h3>
                        <p>Hay un cambio de presión notable. Presta atención a posibles síntomas.</p>
                    @elseif ($riesgo['nivel'] === 'rojo')
                        <h3>Riesgo alto</h3>
                        <p>Cambio fuerte de presión atmosférica. Es un desencadenante habitual de migraña.</p>
                    @endif
                </div>
            </section>

            <section class="weather-current">
                <div class="weather-stat">
                    <div class="weather-stat-label">Temperatura actual</div>
                    <div class="weather-stat-value temperature">{{ $datosClima['temperatura_actual'] }}°C</div>
                </div>
                <div class="weather-stat">
                    <div class="weather-stat-label">Presión actual</div>
                    <div class="weather-stat-value pressure">{{ $datosClima['presion_actual'] }} hPa</div>
                </div>
                <div class="weather-stat-footer">
                    {{ $datosClima['estacion_nombre'] }}
                    @if ($datosClima['actual_es_estimado'] ?? false)
                        · estimado para esta hora
                    @endif
                </div>
            </section>

            <section class="weather-chart-panel">
                <div class="weather-chart-title">
                    <h3>Evolución de hoy</h3>
                    <span class="info-tooltip" tabindex="0" title="La línea discontinua es la previsión para el resto del día: la temperatura es el dato oficial de AEMET; la presión es una estimación propia de MigraSense (no es un dato oficial).">&#9432;</span>
                </div>
                <div class="weather-chart"><canvas id="chart"></canvas></div>
            </section>
        @endif

        <section class="quick-action">
            <button type="button" class="quick-action-button" onclick="abrirModalMigrana()">+ Registrar migraña
                ahora</button>
        </section>

        <div id="modalMigrana" class="migraine-modal-overlay">
            <div class="migraine-modal">
                <div class="migraine-modal-header">
                    <h3>Registrar migraña</h3>
                    <button type="button" class="migraine-modal-close" onclick="cerrarModalMigrana()"
                        aria-label="Cerrar">&times;</button>
                </div>

                <form id="formMigranaModal" method="POST" action="{{ route('usuarios.guardarMigrana') }}" novalidate>
                    @csrf
                    <div class="migraine-modal-row">
                        <div>
                            <x-select-tipo-migrana />
                            <p class="field-error" id="modal_error_tipo_migrana"></p>
                        </div>
                        <div>
                            <x-select-intensidad />
                            <p class="field-error" id="modal_error_intensidad"></p>
                        </div>
                    </div>

                    <div class="migraine-modal-row">
                        <div class="date-field">
                            <label for="modal_hora_inicio">Hora de inicio</label>
                            <input type="datetime-local" id="modal_hora_inicio" name="hora_inicio"
                                value="{{ now()->format('Y-m-d\TH:i') }}" step="60">
                            <p class="field-error" id="modal_error_hora_inicio"></p>
                        </div>
                        <div class="date-field">
                            <label for="modal_hora_termina">Hora de fin</label>
                            <input type="datetime-local" id="modal_hora_termina" name="hora_termina" step="60">
                            <p class="field-error" id="modal_error_hora_termina"></p>
                        </div>
                    </div>

                    <div class="migraine-modal-field">
                        <label for="modal_notas">Notas (opcional)</label>
                        <textarea id="modal_notas" name="notas" maxlength="250" rows="3"
                            placeholder="Escribe cualquier detalle sobre la migraña"></textarea>
                    </div>

                    <button type="submit" class="migraine-modal-submit">Guardar migraña</button>
                </form>
            </div>
        </div>
    </main>
@endsection