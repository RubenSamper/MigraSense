@extends("layouts.default")
@section('title', 'Seguimiento de migraña')
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="resumen-migranas" type="application/json">@json($resumenDias)</script>
<script id="resumen-tipo" type="application/json">@json($resumenPorTipo)</script>
<style>
    .migraine-followup {
        --ink: #173042;
        --muted: #647783;
        --line: #dce7eb;
        --surface: #ffffff;
        --soft: #f4f8f9;
        --accent: #006994;
        --warm: #d85d48;
        max-width: 1120px;
        margin: 0 auto;
        padding: 42px 24px 56px;
        color: var(--ink);
    }
    .followup-header {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: end;
        margin-bottom: 28px;
    }
    .followup-eyebrow {
        margin: 0 0 8px;
        color: var(--accent);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .followup-header h1 { margin: 0; font-size: clamp(1.9rem, 4vw, 2.7rem); }
    .followup-header p { margin: 10px 0 0; color: var(--muted); }
    .followup-header-acciones {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }
    .followup-button {
        display: inline-flex;
        align-items: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 7px;
        background: var(--accent);
        color: white;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
    }
    .followup-button-secundario {
        background: var(--surface);
        color: var(--accent);
        border: 1px solid var(--accent);
    }
    .followup-button-secundario:hover { background: var(--soft); }

    /* Selector de mes */
    .month-switcher {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        padding: 18px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
    }
    .month-switcher-main {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 18px;
    }
    .month-switcher-label {
        margin: 0;
        min-width: 180px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        text-transform: capitalize;
    }
    .month-step-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border: 1px solid var(--line);
        border-radius: 50%;
        background: var(--soft);
        color: var(--accent);
        text-decoration: none;
    }
    .month-step-button:hover { background: #e2edf0; }
    .month-step-button.is-disabled {
        color: #b9c7cd;
        background: transparent;
        cursor: default;
    }
    .month-switcher-hint {
        margin: 0;
        color: var(--muted);
        font-size: .8rem;
        text-align: center;
    }
    .month-select-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 10px;
        border-top: 1px solid var(--line);
        width: 100%;
    }
    .month-select-label {
        color: var(--muted);
        font-size: .82rem;
        font-weight: 600;
    }
    .month-select-form select {
        min-height: 38px;
        padding: 0 10px;
        border: 1px solid var(--line);
        border-radius: 7px;
        background: var(--surface);
        color: var(--ink);
        font-size: .9rem;
    }
    .month-select-button {
        min-height: 38px;
        padding: 0 14px;
        border: 1px solid var(--accent);
        border-radius: 7px;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        font-size: .85rem;
        cursor: pointer;
    }
    .month-select-button:hover { background: #005578; }

    .followup-panel {
        margin-bottom: 28px;
        padding: 22px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 12px 30px rgba(23, 48, 66, .07);
    }
    .followup-panel h2 { margin: 0 0 5px; font-size: 1.15rem; }
    .followup-panel > p, .chart-panel-head p { margin: 0 0 18px; color: var(--muted); font-size: .9rem; }
    .followup-chart {
        height: 300px;
        overflow-x: auto;
        overflow-y: hidden;
    }
    .followup-chart:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
    .followup-chart-scroll { height: 100%; }

    /* Pestañas para cambiar de gráfica */
    .chart-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 6px;
    }
    .chart-panel-head h2 { margin: 0; }
    .chart-panel-desc { margin: 0 0 18px; color: var(--muted); font-size: .9rem; }
    .chart-tabs {
        display: inline-flex;
        gap: 4px;
        padding: 4px;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: var(--soft);
        flex-shrink: 0;
    }
    .chart-tab {
        min-height: 36px;
        padding: 0 14px;
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: var(--muted);
        font-weight: 700;
        font-size: .85rem;
        cursor: pointer;
    }
    .chart-tab[aria-selected="true"] {
        background: var(--surface);
        color: var(--accent);
        box-shadow: 0 2px 8px rgba(23, 48, 66, .12);
    }
    .chart-tab:focus-visible,
    .month-step-button:focus-visible,
    .month-select-form select:focus-visible,
    .month-select-button:focus-visible,
    .followup-button:focus-visible {
        outline: 3px solid var(--accent);
        outline-offset: 2px;
    }

    .migraine-days {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(285px, 1fr));
        gap: 26px;
    }
    .migraine-card {
        padding: 20px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 10px 26px rgba(23, 48, 66, .13);
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .migraine-card:hover {
        transform: translateY(-3px);
        border-color: var(--accent);
        box-shadow: 0 16px 34px rgba(23, 48, 66, .2);
    }
    .migraine-card-date { margin: 0; color: var(--accent); font-size: 1.05rem; text-transform: capitalize; }
    .migraine-card-count { margin: 5px 0 16px; color: var(--muted); font-size: .85rem; }
    .migraine-episode { padding: 13px 0; border-top: 1px solid var(--line); }
    .migraine-episode:first-of-type { border-top: 0; padding-top: 0; }
    .migraine-episode strong { color: var(--warm); }
    .migraine-episode-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .migraine-delete-form { margin: 0; }
    .migraine-delete-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid #e2b1ab;
        border-radius: 6px;
        background: #fff5f4;
        color: #b42318;
        cursor: pointer;
        font-size: .78rem;
        font-weight: 700;
    }
    .migraine-delete-button svg { flex-shrink: 0; }
    .migraine-delete-button:hover { background: #b42318; color: #fff; }
    .migraine-delete-button:focus-visible { outline: 3px solid #b42318; outline-offset: 2px; }
    .migraine-meta { display: flex; flex-wrap: wrap; gap: 7px; margin: 8px 0; }
    .migraine-tag { padding: 4px 8px; border-radius: 999px; background: var(--soft); color: var(--ink); font-size: .78rem; }
    .migraine-tag-stars { color: #f2c94c; letter-spacing: 2px; font-size: 0.9rem; }
    .migraine-range-note { margin: 4px 0 0; color: var(--muted); font-size: .8rem; font-style: italic; }
    .migraine-notes { margin: 7px 0 0; color: var(--muted); font-size: .88rem; }
    .migraine-more-info {
        margin-top: 14px;
        padding: 10px 12px;
        border: 1px solid #c6dce4;
        border-radius: 8px;
        background: #f1f8fa;
        color: var(--accent);
        font-size: .84rem;
    }
    .migraine-more-info summary { cursor: pointer; font-weight: 700; }
    .migraine-more-info summary::marker { color: var(--accent); }
    .migraine-more-info[open] summary { margin-bottom: 10px; }
    .migraine-diagnostico {
        margin-top: 10px;
        padding: 10px 12px;
        border-left: 3px solid var(--accent);
        border-radius: 0 8px 8px 0;
        background: #eef6f9;
    }
    .migraine-diagnostico summary {
        cursor: pointer;
        color: var(--accent);
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .migraine-diagnostico summary::marker { color: var(--accent); }
    .migraine-diagnostico[open] summary { margin-bottom: 8px; }
    .migraine-diagnostico p { margin: 0; color: var(--ink); font-size: .9rem; white-space: pre-line; }
    /* Episodio con un diagnóstico que todavía no se había visto antes de
       cargar esta página: una franja de color a la izquierda para que
       resalte dentro de la tarjeta del día, sin tener que abrir nada. */
    .migraine-episode-sin-leer {
        margin: 0 -14px;
        padding-left: 14px;
        padding-right: 14px;
        border-left: 3px solid #f2994a;
        background: #fff8f1;
        border-radius: 0 8px 8px 0;
    }
    .etiqueta-nuevo {
        display: inline-block;
        margin-left: 8px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #f2994a;
        color: #fff;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .03em;
        text-transform: uppercase;
        vertical-align: middle;
    }
    .contador-sin-leer-tab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 19px;
        height: 19px;
        margin-left: 6px;
        padding: 0 5px;
        border-radius: 999px;
        background: #e15b45;
        color: #fff;
        font-size: .72rem;
        font-weight: 700;
        line-height: 1;
        vertical-align: middle;
    }
    .empty-followup { padding: 30px; border: 1px dashed #b9cdd4; border-radius: 10px; color: var(--muted); text-align: center; }
    .followup-success {
        margin: 0 0 20px;
        padding: 14px 16px;
        border-radius: 8px;
        background: #f3fbf4;
        border: 1px solid #bfe3c5;
        color: #1e6b34;
        font-weight: 600;
    }
    @media (max-width: 700px) {
        .migraine-followup { padding: 28px 16px 40px; }
        .followup-header { display: block; }
        .followup-header-acciones { margin-top: 18px; }
        .month-switcher-main { gap: 10px; }
        .month-switcher-label { min-width: 0; font-size: 1rem; }
        .month-select-form { justify-content: center; }
        .month-select-form select { flex: 1; }
        .chart-panel-head { flex-direction: column; }
        .chart-tabs { align-self: stretch; justify-content: center; }
        .followup-panel { padding: 16px; }
        .followup-chart { height: 260px; }
        .migraine-days { grid-template-columns: 1fr; }
        .migraine-card { padding: 16px; }
        .migraine-episode-header { align-items: flex-start; }
        .migraine-delete-form { width: 100%; }
        .migraine-delete-button { width: 100%; justify-content: center; }
    }
    @media (max-width: 420px) {
        .chart-tabs { flex-direction: column; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resumenIntensidad = JSON.parse(document.getElementById('resumen-migranas').textContent);
        const resumenTipo = JSON.parse(document.getElementById('resumen-tipo').textContent);
        const canvas = document.getElementById('migraineChart');

        const coloresPorTipo = {
            con_aura: '#2f80ed',
            sin_aura: '#56ab91',
            menstrual: '#eb5757',
            vestibular: '#9b51e0',
            cronica: '#f2994a',
        };

        const datosIntensidad = {
            labels: resumenIntensidad.map((dia) => new Intl.DateTimeFormat('es-ES', {
                day: 'numeric',
                month: 'short'
            }).format(new Date(`${dia.fecha}T12:00:00`))),
            datasets: [
                {
                    label: 'Leve',
                    data: resumenIntensidad.map((dia) => dia.leve),
                    backgroundColor: '#2f80ed',
                    borderRadius: 4,
                    maxBarThickness: 42
                },
                {
                    label: 'Moderada',
                    data: resumenIntensidad.map((dia) => dia.moderada),
                    backgroundColor: '#f2c94c',
                    borderRadius: 4,
                    maxBarThickness: 42
                },
                {
                    label: 'Fuerte',
                    data: resumenIntensidad.map((dia) => dia.fuerte),
                    backgroundColor: '#f2994a',
                    borderRadius: 4,
                    maxBarThickness: 42
                }
            ]
        };

        const opcionesIntensidad = {
            maintainAspectRatio: false,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Episodios' } }
            }
        };

        const datosTipo = {
            labels: resumenTipo.map((item) => item.etiqueta),
            datasets: [{
                label: 'Episodios',
                data: resumenTipo.map((item) => item.total),
                backgroundColor: resumenTipo.map((item) => coloresPorTipo[item.tipo] || '#647783'),
                borderRadius: 4,
                maxBarThickness: 36
            }]
        };

        const opcionesTipo = {
            indexAxis: 'y',
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Episodios' } },
                y: { grid: { display: false } }
            }
        };

        let chart = null;
        const chartScroll = document.getElementById('chart-scroll');
        const chartOuter = document.querySelector('.followup-chart');
        const ANCHO_POR_DIA = 46; // px mínimos por barra para que no se amontonen

        // Si el mes tiene muchos días con episodios, ensanchamos el contenedor
        // del canvas (el chart se desplaza en horizontal) en vez de apretar las
        // barras hasta que sean ilegibles. La gráfica "por tipo" siempre cabe
        // (como mucho 5 barras), así que esa se queda a ancho completo.
        function ajustarAnchoGrafica(esTipo) {
            if (!chartScroll || !chartOuter) return;

            if (esTipo) {
                chartScroll.style.minWidth = '100%';
            } else {
                const anchoNecesario = resumenIntensidad.length * ANCHO_POR_DIA;
                chartScroll.style.minWidth = Math.max(anchoNecesario, chartOuter.clientWidth) + 'px';
            }

            if (chart) {
                chart.resize();
            }
        }

        if (canvas && (resumenIntensidad.length || resumenTipo.length)) {
            chart = new Chart(canvas, { type: 'bar', data: datosIntensidad, options: opcionesIntensidad });
            ajustarAnchoGrafica(false);
        }

        window.addEventListener('resize', () => {
            const tabActiva = document.getElementById('tab-tipo');
            ajustarAnchoGrafica(tabActiva && tabActiva.getAttribute('aria-selected') === 'true');
        });

        // Pestañas accesibles para cambiar de gráfica (patrón ARIA "tabs"),
        // navegables con flechas, Inicio y Fin además de con Tab.
        const tabs = Array.from(document.querySelectorAll('.chart-tab'));
        const tabPanel = document.getElementById('chart-panel');
        const descIntensidad = document.getElementById('chart-por-intensidad-desc');
        const descTipo = document.getElementById('chart-por-tipo-desc');

        function activarTab(tab, moverFoco) {
            tabs.forEach((t) => {
                const seleccionada = t === tab;
                t.setAttribute('aria-selected', seleccionada ? 'true' : 'false');
                t.tabIndex = seleccionada ? 0 : -1;
            });

            if (moverFoco) {
                tab.focus();
            }

            const esTipo = tab.id === 'tab-tipo';

            if (descIntensidad && descTipo) {
                descIntensidad.hidden = esTipo;
                descTipo.hidden = !esTipo;
            }
            if (tabPanel) {
                tabPanel.setAttribute('aria-labelledby', tab.id);
            }

            if (!chart) return;
            chart.data = esTipo ? datosTipo : datosIntensidad;
            chart.options = esTipo ? opcionesTipo : opcionesIntensidad;
            ajustarAnchoGrafica(esTipo);
            chart.update();
        }

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activarTab(tab, false));
            tab.addEventListener('keydown', (event) => {
                let nuevoIndice = null;

                if (event.key === 'ArrowRight' || event.key === 'ArrowDown') nuevoIndice = (index + 1) % tabs.length;
                if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') nuevoIndice = (index - 1 + tabs.length) % tabs.length;
                if (event.key === 'Home') nuevoIndice = 0;
                if (event.key === 'End') nuevoIndice = tabs.length - 1;

                if (nuevoIndice !== null) {
                    event.preventDefault();
                    activarTab(tabs[nuevoIndice], true);
                }
            });
        });

        // Pestañas del historial: "Todos" / "Diagnósticos sin leer". Es un
        // filtro simple en el propio navegador (los episodios ya están
        // todos renderizados), no hace falta recargar la página.
        const tabsHistorial = Array.from(document.querySelectorAll('.historial-tab'));
        const diasHistorial = Array.from(document.querySelectorAll('.migraine-card'));
        const avisoSinResultados = document.getElementById('historial-sin-leer-vacio');

        function aplicarFiltroHistorial(soloSinLeer) {
            let algunDiaVisible = false;

            diasHistorial.forEach((dia) => {
                const episodios = Array.from(dia.querySelectorAll('.migraine-episode'));
                let algunEpisodioVisible = false;

                episodios.forEach((episodio) => {
                    const esSinLeer = episodio.dataset.diagnosticoSinLeer === '1';
                    const visible = !soloSinLeer || esSinLeer;
                    episodio.style.display = visible ? '' : 'none';
                    if (visible) algunEpisodioVisible = true;
                });

                dia.style.display = algunEpisodioVisible ? '' : 'none';
                if (algunEpisodioVisible) algunDiaVisible = true;
            });

            if (avisoSinResultados) {
                avisoSinResultados.hidden = !soloSinLeer || algunDiaVisible;
            }
        }

        tabsHistorial.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabsHistorial.forEach((t) => t.setAttribute('aria-selected', t === tab ? 'true' : 'false'));
                aplicarFiltroHistorial(tab.id === 'tab-historial-sin-leer');
            });
        });
    });
</script>
@endsection
@section('contenido')
<main class="migraine-followup">
    <header class="followup-header">
        <div>
            <p class="followup-eyebrow">Salud y seguimiento</p>
            <h1>Seguimiento de migraña</h1>
            <p>Revisa tus episodios y observa cómo evoluciona su frecuencia.</p>
        </div>
        <div class="followup-header-acciones">
            <a class="followup-button" href="{{ route('usuarios.registrarMigrana', ['mes' => $mesSeleccionado]) }}">+ Registrar nueva migraña</a>
            <a class="followup-button followup-button-secundario" href="{{ route('usuarios.seguimientoMigrana.pdf', ['mes' => $mesSeleccionado]) }}">Descargar PDF</a>
        </div>
    </header>

    <nav class="month-switcher" aria-label="Navegación por mes">
        <div class="month-switcher-main">
            <a class="month-step-button" href="{{ route('usuarios.seguimientoMigrana', ['mes' => $mesAnterior]) }}" aria-label="Ver {{ $etiquetaMesAnterior }}">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>

            <p class="month-switcher-label">{{ $etiquetaMesSeleccionado }}</p>

            @if ($mesSiguiente)
                <a class="month-step-button" href="{{ route('usuarios.seguimientoMigrana', ['mes' => $mesSiguiente]) }}" aria-label="Ver {{ $etiquetaMesSiguiente }}">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="month-step-button is-disabled" aria-disabled="true" aria-label="No puedes avanzar más allá del mes actual">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>

        @if ($mesesConRegistros->isNotEmpty())
            <form class="month-select-form" method="GET" action="{{ route('usuarios.seguimientoMigrana') }}">
                <label for="mes-selector" class="month-select-label">Ir directamente a</label>
                <select id="mes-selector" name="mes" onchange="this.form.submit()">
                    @foreach ($mesesParaSelector as $opcion)
                        <option value="{{ $opcion['valor'] }}" {{ $opcion['valor'] === $mesSeleccionado ? 'selected' : '' }}>
                            {{ $opcion['etiqueta'] }}
                        </option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="month-select-button">Ir</button></noscript>
            </form>
        @else
            <p class="month-switcher-hint">Puedes retroceder mes a mes con la flecha izquierda, aunque todavía no tengas episodios registrados.</p>
        @endif
    </nav>

    <section class="followup-panel" aria-label="Gráfica de episodios">
        <div class="chart-panel-head">
            <h2>Episodios registrados</h2>
            <div class="chart-tabs" role="tablist" aria-label="Tipo de gráfica">
                <button type="button" id="tab-intensidad" class="chart-tab" role="tab" aria-selected="true" aria-controls="chart-panel" tabindex="0">Por intensidad</button>
                <button type="button" id="tab-tipo" class="chart-tab" role="tab" aria-selected="false" aria-controls="chart-panel" tabindex="-1">Por tipo</button>
            </div>
        </div>
        <p id="chart-por-intensidad-desc" class="chart-panel-desc">Cada barra se divide por intensidad: leve (azul), moderada (amarillo) y fuerte (naranja). Una migraña de varios días cuenta en cada uno de ellos.</p>
        <p id="chart-por-tipo-desc" class="chart-panel-desc" hidden>Número de episodios de este mes, agrupados por tipo de migraña.</p>

        <div id="chart-panel" role="tabpanel" aria-labelledby="tab-intensidad" tabindex="0">
            @if ($diasMigrana->isNotEmpty())
                <div class="followup-chart" tabindex="0" aria-label="Gráfica de episodios. Si hay muchos días, desplázate en horizontal con las flechas o arrastrando.">
                    <div class="followup-chart-scroll" id="chart-scroll">
                        <canvas id="migraineChart"></canvas>
                    </div>
                </div>
            @elseif ($todasLasMigranas->isNotEmpty())
                <div class="empty-followup">No has registrado migrañas en {{ $etiquetaMesSeleccionado }}. Usa el selector de arriba para ver otro mes.</div>
            @else
                <div class="empty-followup">Todavía no hay suficientes registros para mostrar una gráfica.</div>
            @endif
        </div>
    </section>

    <section aria-label="Historial de migrañas">
        <div class="followup-panel">
            <div class="chart-panel-head">
                <div>
                    <h2>Historial por días</h2>
                    <p>Tus registros de {{ $etiquetaMesSeleccionado }} aparecen ordenados cronológicamente, agrupados por el día en que comenzaron.</p>
                </div>
                <div class="chart-tabs" role="tablist" aria-label="Filtro del historial">
                    <button type="button" id="tab-historial-todos" class="chart-tab historial-tab" role="tab" aria-selected="true" tabindex="0">Todos</button>
                    <button type="button" id="tab-historial-sin-leer" class="chart-tab historial-tab" role="tab" aria-selected="false" tabindex="-1">
                        Diagnósticos sin leer
                        @if (count($diagnosticosSinLeerIds) > 0)
                            <span class="contador-sin-leer-tab">{{ count($diagnosticosSinLeerIds) }}</span>
                        @endif
                    </button>
                </div>
            </div>

            @if ($diasMigrana->isNotEmpty())
                <div class="migraine-days">
                    @foreach ($diasMigrana as $fecha => $episodios)
                        <article class="migraine-card">
                            <h3 class="migraine-card-date">{{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, j \d\e F \d\e Y') }}</h3>
                            <p class="migraine-card-count">{{ $episodios->count() }} {{ $episodios->count() === 1 ? 'episodio registrado' : 'episodios registrados' }}</p>

                            @foreach ($episodios as $episodio)
                                @php
                                    $fechaInicioEpisodio = \Carbon\Carbon::parse($episodio->hora_inicio)->toDateString();
                                    $fechaFinEpisodio = $episodio->hora_termina
                                        ? \Carbon\Carbon::parse($episodio->hora_termina)->toDateString()
                                        : $fechaInicioEpisodio;
                                    $esMultidia = $fechaFinEpisodio !== $fechaInicioEpisodio;
                                    $diagnosticoSinLeer = in_array($episodio->id, $diagnosticosSinLeerIds, true);
                                @endphp
                                <div class="migraine-episode @if ($diagnosticoSinLeer) migraine-episode-sin-leer @endif" data-diagnostico-sin-leer="{{ $diagnosticoSinLeer ? '1' : '0' }}">
                                    <div class="migraine-episode-header">
                                        <strong>{{ \Carbon\Carbon::parse($episodio->hora_inicio)->format('H:i') }} - {{ $episodio->hora_termina ? \Carbon\Carbon::parse($episodio->hora_termina)->format('H:i') : 'Sin hora de fin' }}</strong>
                                        <form class="migraine-delete-form" method="POST" action="{{ route('usuarios.eliminarMigrana', $episodio->id) }}" onsubmit="return confirm('¿Quieres eliminar este registro de migraña?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="migraine-delete-button" type="submit">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                    @if ($esMultidia)
                                        <p class="migraine-range-note">{{ ucfirst(\Carbon\Carbon::parse($fechaInicioEpisodio)->locale('es')->translatedFormat('l, j \d\e F \d\e Y')) }} - {{ ucfirst(\Carbon\Carbon::parse($fechaFinEpisodio)->locale('es')->translatedFormat('l, j \d\e F \d\e Y')) }}</p>
                                    @endif
                                    <div class="migraine-meta">
                                        <span class="migraine-tag">{{ \App\Support\MigranaVisual::icono($episodio->tipo_migrana) }} {{ \App\Support\MigranaVisual::etiquetaTipo($episodio->tipo_migrana) }}</span>
                                        <span class="migraine-tag migraine-tag-stars" title="Intensidad: {{ ucfirst($episodio->intensidad) }}">{{ \App\Support\MigranaVisual::estrellas($episodio->intensidad) }}</span>
                                    </div>
                                    @if (filled($episodio->notas))
                                        <details class="migraine-more-info">
                                            <summary>Hay más información. Haz clic para verla.</summary>
                                            <p class="migraine-notes">{{ $episodio->notas }}</p>
                                        </details>
                                    @endif
                                    @if (filled($episodio->diagnostico))
                                        <details class="migraine-diagnostico" @if ($diagnosticoSinLeer) open @endif>
                                            <summary>
                                                Diagnóstico del médico
                                                @if ($diagnosticoSinLeer)
                                                    <span class="etiqueta-nuevo">Nuevo</span>
                                                @endif
                                            </summary>
                                            <p>{{ $episodio->diagnostico }}</p>
                                        </details>
                                    @endif
                                </div>
                            @endforeach
                        </article>
                    @endforeach
                </div>
                <p id="historial-sin-leer-vacio" class="empty-followup" hidden>No tienes diagnósticos nuevos por leer en {{ $etiquetaMesSeleccionado }}.</p>
            @elseif ($todasLasMigranas->isNotEmpty())
                <div class="empty-followup">No has registrado migrañas en {{ $etiquetaMesSeleccionado }}. Usa el selector de arriba para ver otro mes.</div>
            @else
                <div class="empty-followup">Aún no has registrado ninguna migraña. Empieza creando tu primer registro.</div>
            @endif
        </div>
    </section>
</main>
@endsection