@extends("layouts.default")
@section('title', 'Seguimiento de migraña')
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="resumen-migranas" type="application/json">@json($resumenDias)</script>
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
    background-color: #f7fafb;
    background-image:
        linear-gradient(180deg, #eef6f8 0%, #f7fafb 260px, #f7fafb 100%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='40' viewBox='0 0 120 40'%3E%3Cpath d='M0 20 Q 30 0, 60 20 T 120 20' fill='none' stroke='%23006994' stroke-opacity='0.06' stroke-width='2'/%3E%3C/svg%3E");
    background-repeat: no-repeat, repeat;
    background-size: 100% 400px, 120px 40px;
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
    .followup-panel {
        margin-bottom: 28px;
        padding: 22px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 12px 30px rgba(23, 48, 66, .07);
    }
    .followup-panel h2 { margin: 0 0 5px; font-size: 1.15rem; }
    .followup-panel > p { margin: 0 0 18px; color: var(--muted); font-size: .9rem; }
    .followup-chart { height: 300px; }
    .migraine-days {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(285px, 1fr));
        gap: 16px;
    }
    .migraine-card {
        padding: 20px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 8px 22px rgba(23, 48, 66, .06);
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
        padding: 6px 10px;
        border: 1px solid #e2b1ab;
        border-radius: 6px;
        background: #fff5f4;
        color: #b42318;
        cursor: pointer;
        font-size: .78rem;
        font-weight: 700;
    }
    .migraine-delete-button:hover { background: #b42318; color: #fff; }
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
        .followup-button { margin-top: 18px; }
        .followup-panel { padding: 16px; }
        .followup-chart { height: 260px; }
        .migraine-days { grid-template-columns: 1fr; }
        .migraine-card { padding: 16px; }
        .migraine-episode-header { align-items: flex-start; }
        .migraine-delete-form { width: 100%; }
        .migraine-delete-button { width: 100%; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resumen = JSON.parse(document.getElementById('resumen-migranas').textContent);
        const canvas = document.getElementById('migraineChart');

        if (canvas && resumen.length) {
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: resumen.map((dia) => new Intl.DateTimeFormat('es-ES', {
                        day: 'numeric',
                        month: 'short'
                    }).format(new Date(`${dia.fecha}T12:00:00`))),
                    datasets: [
                        {
                            label: 'Leve',
                            data: resumen.map((dia) => dia.leve),
                            backgroundColor: '#2f80ed',
                            borderRadius: 4,
                            maxBarThickness: 42
                        },
                        {
                            label: 'Moderada',
                            data: resumen.map((dia) => dia.moderada),
                            backgroundColor: '#f2c94c',
                            borderRadius: 4,
                            maxBarThickness: 42
                        },
                        {
                            label: 'Fuerte',
                            data: resumen.map((dia) => dia.fuerte),
                            backgroundColor: '#f2994a',
                            borderRadius: 4,
                            maxBarThickness: 42
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'top' } },
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Episodios' } }
                    }
                }
            });
        }

        const params = new URLSearchParams(window.location.search);

if (params.get('registrado') === '1') {
    const stack = document.querySelector('.notification-stack');

    if (stack) {
        const item = document.createElement('div');
        item.className = 'notification-item';
        item.innerHTML = `
            <div class="notification notification-success" role="status" aria-live="polite">
                <div class="notification-mark" aria-hidden="true">✓</div>
                <div class="notification-content">
                    <strong>Éxito</strong>
                    <span>Migraña registrada correctamente.</span>
                </div>
            </div>
        `;
        stack.appendChild(item);
    }

    params.delete('registrado');
    const nuevaUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState({}, '', nuevaUrl);
}
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
        <a class="followup-button" href="{{ route('usuarios.registrarMigrana') }}">+ Registrar nueva migraña</a>
    </header>

    <section class="followup-panel" aria-label="Gráfica de episodios">
        <h2>Frecuencia de episodios por intensidad</h2>
        <p>Cada barra se divide por intensidad: leve (azul), moderada (amarillo) y fuerte (naranja). Una migraña de varios días cuenta en cada uno de ellos.</p>
        @if ($resumenDias->isNotEmpty())
            <div class="followup-chart"><canvas id="migraineChart"></canvas></div>
        @else
            <div class="empty-followup">Todavía no hay suficientes registros para mostrar una gráfica.</div>
        @endif
    </section>

    <section aria-label="Historial de migrañas">
        <div class="followup-panel">
            <h2>Historial por días</h2>
            <p>Tus registros aparecen ordenados cronológicamente, agrupados por el día en que comenzaron.</p>

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
                                @endphp
                                <div class="migraine-episode">
                                    <div class="migraine-episode-header">
                                        <strong>{{ \Carbon\Carbon::parse($episodio->hora_inicio)->format('H:i') }} - {{ $episodio->hora_termina ? \Carbon\Carbon::parse($episodio->hora_termina)->format('H:i') : 'Sin hora de fin' }}</strong>
                                        <form class="migraine-delete-form" method="POST" action="{{ route('usuarios.eliminarMigrana', $episodio->id) }}" onsubmit="return confirm('¿Quieres eliminar este registro de migraña?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="migraine-delete-button" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                    @if ($esMultidia)
                                        <p class="migraine-range-note">{{ ucfirst(\Carbon\Carbon::parse($fechaInicioEpisodio)->locale('es')->translatedFormat('l, j \d\e F \d\e Y')) }} - {{ ucfirst(\Carbon\Carbon::parse($fechaFinEpisodio)->locale('es')->translatedFormat('l, j \d\e F \d\e Y')) }}</p>
                                    @endif
                                    <div class="migraine-meta">
                                        <span class="migraine-tag">{{ \App\Support\MigranaVisual::icono($episodio->tipo_migrana) }} {{ \App\Support\MigranaVisual::etiquetaTipo($episodio->tipo_migrana) }}</span>
                                        <span class="migraine-tag migraine-tag-stars" title="Intensidad: {{ ucfirst($episodio->intensidad) }}">{{ \App\Support\MigranaVisual::estrellas($episodio->intensidad) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            @if ($episodios->contains(fn ($episodio) => filled($episodio->notas)))
                                <details class="migraine-more-info">
                                    <summary>Hay más información. Haz clic para verla.</summary>
                                    @foreach ($episodios as $episodioConNotas)
                                        @if (filled($episodioConNotas->notas))
                                            <p class="migraine-notes"><strong>{{ \Carbon\Carbon::parse($episodioConNotas->hora_inicio)->format('H:i') }}</strong>: {{ $episodioConNotas->notas }}</p>
                                        @endif
                                    @endforeach
                                </details>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-followup">Aún no has registrado ninguna migraña. Empieza creando tu primer registro.</div>
            @endif
        </div>
    </section>
</main>
@endsection