<?php $__env->startSection('title', 'Seguimiento de migraña'); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="resumen-migranas" type="application/json"><?php echo json_encode($resumenDias, 15, 512) ?></script>
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
    .followup-chart { height: 270px; }
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
    @media (max-width: 700px) {
        .migraine-followup { padding: 28px 16px 40px; }
        .followup-header { display: block; }
        .followup-button { margin-top: 18px; }
        .followup-panel { padding: 16px; }
        .followup-chart { height: 230px; }
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

        if (!canvas || !resumen.length) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: resumen.map((dia) => new Intl.DateTimeFormat('es-ES', {
                    day: 'numeric',
                    month: 'short'
                }).format(new Date(`${dia.fecha}T12:00:00`))),
                datasets: [{
                    label: 'Episodios',
                    data: resumen.map((dia) => dia.cantidad),
                    backgroundColor: '#006994',
                    borderRadius: 6,
                    maxBarThickness: 42
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Episodios' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<main class="migraine-followup">
    <header class="followup-header">
        <div>
            <p class="followup-eyebrow">Salud y seguimiento</p>
            <h1>Seguimiento de migraña</h1>
            <p>Revisa tus episodios y observa cómo evoluciona su frecuencia.</p>
        </div>
        <a class="followup-button" href="<?php echo e(route('usuarios.registrarMigrana')); ?>">+ Registrar nueva migraña</a>
    </header>

    <section class="followup-panel" aria-label="Gráfica de episodios">
        <h2>Frecuencia de episodios</h2>
        <p>Cada barra representa cuántas migrañas registraste en un día.</p>
        <?php if($resumenDias->isNotEmpty()): ?>
            <div class="followup-chart"><canvas id="migraineChart"></canvas></div>
        <?php else: ?>
            <div class="empty-followup">Todavía no hay suficientes registros para mostrar una gráfica.</div>
        <?php endif; ?>
    </section>

    <section aria-label="Historial de migrañas">
        <div class="followup-panel">
            <h2>Historial por días</h2>
            <p>Tus registros aparecen ordenados cronológicamente.</p>

            <?php if($diasMigrana->isNotEmpty()): ?>
                <div class="migraine-days">
                    <?php $__currentLoopData = $diasMigrana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fecha => $episodios): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="migraine-card">
                            <h3 class="migraine-card-date"><?php echo e(\Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, j \d\e F \d\e Y')); ?></h3>
                            <p class="migraine-card-count"><?php echo e($episodios->count()); ?> <?php echo e($episodios->count() === 1 ? 'episodio registrado' : 'episodios registrados'); ?></p>

                            <?php $__currentLoopData = $episodios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $episodio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="migraine-episode">
                                    <div class="migraine-episode-header">
                                        <strong><?php echo e(\Carbon\Carbon::parse($episodio->hora_inicio)->format('H:i')); ?> - <?php echo e($episodio->hora_termina ? \Carbon\Carbon::parse($episodio->hora_termina)->format('H:i') : 'Sin hora de fin'); ?></strong>
                                        <form class="migraine-delete-form" method="POST" action="<?php echo e(route('usuarios.eliminarMigrana', $episodio->id)); ?>" onsubmit="return confirm('¿Quieres eliminar este registro de migraña?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="migraine-delete-button" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                    <div class="migraine-meta">
                                        <span class="migraine-tag"><?php echo e(ucfirst($episodio->tipo_migrana)); ?></span>
                                        <span class="migraine-tag">Intensidad: <?php echo e(ucfirst($episodio->intensidad)); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($episodios->contains(fn ($episodio) => filled($episodio->notas))): ?>
                                <details class="migraine-more-info">
                                    <summary>Hay más información. Haz clic para verla.</summary>
                                    <?php $__currentLoopData = $episodios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $episodioConNotas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(filled($episodioConNotas->notas)): ?>
                                            <p class="migraine-notes"><strong><?php echo e(\Carbon\Carbon::parse($episodioConNotas->hora_inicio)->format('H:i')); ?></strong>: <?php echo e($episodioConNotas->notas); ?></p>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </details>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="empty-followup">Aún no has registrado ninguna migraña. Empieza creando tu primer registro.</div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/seguimientoMigrana.blade.php ENDPATH**/ ?>