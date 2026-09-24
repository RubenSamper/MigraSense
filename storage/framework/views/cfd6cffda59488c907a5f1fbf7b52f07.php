<?php $__env->startSection('title', 'AquaSense | Tiempo y seguimiento personal'); ?>
<?php $__env->startSection('scripts'); ?>
<style>
    .landing {
        --navy: #102b3a;
        --ink: #173042;
        --muted: #647783;
        --teal: #006994;
        --teal-deep: #004c6a;
        --aqua: #dff1f3;
        --line: #d9e7ea;
        color: var(--ink);
        background: #f6faf9;
    }
    .landing-hero {
        position: relative;
        min-height: 570px;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: var(--navy);
        background-image: url('https://images.unsplash.com/photo-1534088568595-a066f410bcda?auto=format&fit=crop&w=1800&q=85');
        background-size: cover;
        background-position: center;
        color: white;
    }
    .landing-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(8, 34, 47, .64);
    }
    .landing-hero-inner {
        position: relative;
        z-index: 1;
        width: min(1120px, calc(100% - 48px));
        margin: 0 auto;
        padding: 86px 0 100px;
    }
    .landing-kicker {
        margin: 0 0 18px;
        color: #a9e0e3;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
    }
    .landing-hero h1 {
        max-width: 720px;
        margin: 0;
        font-size: clamp(2.7rem, 7vw, 5.8rem);
        line-height: .98;
        letter-spacing: 0;
    }
    .landing-hero-copy {
        max-width: 620px;
        margin: 24px 0 0;
        color: #e6f2f3;
        font-size: clamp(1rem, 2vw, 1.25rem);
        line-height: 1.65;
    }
    .landing-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
    .landing-button {
        display: inline-flex;
        align-items: center;
        min-height: 46px;
        padding: 0 18px;
        border: 1px solid transparent;
        border-radius: 6px;
        font-weight: 700;
        text-decoration: none;
    }
    .landing-button-primary { background: #fff; color: var(--teal-deep); }
    .landing-button-secondary { border-color: rgba(255,255,255,.55); color: #fff; }
    .landing-button:hover { transform: translateY(-1px); }
    .landing-section { width: min(1120px, calc(100% - 48px)); margin: 0 auto; padding: 76px 0; }
    .landing-section-heading { max-width: 650px; margin-bottom: 30px; }
    .landing-section-heading p { margin: 0 0 10px; color: var(--teal); font-size: .78rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .landing-section-heading h2 { margin: 0; color: var(--ink); font-size: clamp(1.8rem, 4vw, 3rem); line-height: 1.08; }
    .landing-section-heading span { display: block; margin-top: 14px; color: var(--muted); line-height: 1.65; }
    .landing-service-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .landing-service { min-height: 230px; padding: 24px; border: 1px solid var(--line); border-radius: 10px; background: #fff; }
    .landing-service-number { color: var(--teal); font-size: .8rem; font-weight: 800; letter-spacing: .1em; }
    .landing-service h3 { margin: 42px 0 10px; color: var(--ink); font-size: 1.25rem; }
    .landing-service p { margin: 0; color: var(--muted); line-height: 1.6; }
    .landing-split { display: grid; grid-template-columns: .85fr 1.15fr; gap: 70px; align-items: center; }
    .landing-split-copy h2 { margin: 0; color: var(--ink); font-size: clamp(1.8rem, 4vw, 2.8rem); line-height: 1.1; }
    .landing-split-copy p { color: var(--muted); line-height: 1.7; }
    .landing-list { display: grid; gap: 16px; margin: 28px 0 0; padding: 0; list-style: none; }
    .landing-list li { display: grid; grid-template-columns: 34px 1fr; gap: 12px; align-items: start; }
    .landing-list-mark { display: grid; place-items: center; width: 30px; height: 30px; border-radius: 50%; background: var(--aqua); color: var(--teal-deep); font-size: .8rem; font-weight: 800; }
    .landing-list strong { display: block; margin-bottom: 3px; color: var(--ink); }
    .landing-list span { color: var(--muted); font-size: .92rem; line-height: 1.5; }
    .landing-preview { padding: 24px; border: 1px solid var(--line); border-radius: 12px; background: #fff; box-shadow: 0 18px 40px rgba(16, 43, 58, .1); }
    .landing-preview-top { display: flex; justify-content: space-between; gap: 20px; padding-bottom: 18px; border-bottom: 1px solid var(--line); }
    .landing-preview-label { color: var(--muted); font-size: .78rem; }
    .landing-preview-value { margin-top: 4px; color: var(--ink); font-size: 1.8rem; font-weight: 800; }
    .landing-preview-value.teal { color: var(--teal); }
    .landing-bars { display: flex; align-items: end; gap: 12px; height: 160px; padding: 24px 8px 0; }
    .landing-bar { flex: 1; min-height: 24px; border-radius: 5px 5px 0 0; background: var(--teal); opacity: .82; }
    .landing-bar:nth-child(2), .landing-bar:nth-child(5) { background: #d85d48; opacity: .72; }
    .landing-bar:nth-child(3) { height: 70%; }
    .landing-bar:nth-child(4) { height: 92%; }
    .landing-bar:nth-child(6) { height: 58%; }
    .landing-bar:nth-child(7) { height: 78%; }
    .landing-bar-labels { display: flex; gap: 12px; padding: 8px; color: var(--muted); font-size: .72rem; text-align: center; }
    .landing-bar-labels span { flex: 1; }
    .landing-cta { background: var(--aqua); }
    .landing-cta-inner { display: flex; justify-content: space-between; gap: 28px; align-items: center; }
    .landing-cta h2 { max-width: 620px; margin: 0; color: var(--ink); font-size: clamp(1.7rem, 4vw, 2.8rem); }
    .landing-cta p { max-width: 560px; margin: 12px 0 0; color: var(--muted); line-height: 1.6; }
    .landing-button-dark { background: var(--teal-deep); color: #fff; }
    @media (max-width: 800px) {
        .landing-service-grid, .landing-split { grid-template-columns: 1fr; }
        .landing-split { gap: 32px; }
        .landing-cta-inner { display: block; }
        .landing-cta .landing-button { margin-top: 22px; }
    }
    @media (max-width: 560px) {
        .landing-hero-inner, .landing-section { width: min(100% - 32px, 1120px); }
        .landing-hero { min-height: 520px; }
        .landing-hero-inner { padding: 64px 0 78px; }
        .landing-section { padding: 54px 0; }
        .landing-service { min-height: auto; }
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<main class="landing">
    <section class="landing-hero">
        <div class="landing-hero-inner">
            <p class="landing-kicker">AquaSense · información que ayuda a decidir</p>
            <h1>Entiende el tiempo. Registra cómo te afecta.</h1>
            <p class="landing-hero-copy">Consulta la previsión hora a hora de tu ubicación y lleva un seguimiento claro de tus migrañas, con fechas, intensidad y notas en un mismo espacio.</p>
            <div class="landing-actions">
                <a class="landing-button landing-button-primary" href="<?php echo e(route('usuarios.createUsuario')); ?>">Crear una cuenta</a>
                <a class="landing-button landing-button-secondary" href="<?php echo e(route('usuarios.login')); ?>">Iniciar sesión</a>
            </div>
        </div>
    </section>

    <section class="landing-section">
        <div class="landing-section-heading">
            <p>Una herramienta, dos perspectivas</p>
            <h2>Datos del entorno y contexto personal, juntos.</h2>
            <span>AquaSense convierte la consulta diaria en un registro útil: sabes qué condiciones vienen y puedes compararlas con cómo te has sentido.</span>
        </div>
        <div class="landing-service-grid">
            <article class="landing-service">
                <span class="landing-service-number">01 / PREVISIÓN</span>
                <h3>El tiempo por horas</h3>
                <p>Selecciona una provincia o introduce coordenadas para consultar temperatura y viento durante los próximos siete días.</p>
            </article>
            <article class="landing-service">
                <span class="landing-service-number">02 / REGISTRO</span>
                <h3>Una historia de cada episodio</h3>
                <p>Guarda tipo de migraña, intensidad, inicio, fin y notas para que ningún detalle se pierda.</p>
            </article>
            <article class="landing-service">
                <span class="landing-service-number">03 / SEGUIMIENTO</span>
                <h3>Patrones que se entienden</h3>
                <p>Revisa tus días con migraña en tarjetas ordenadas y observa la frecuencia en una gráfica sencilla.</p>
            </article>
        </div>
    </section>

    <section class="landing-section">
        <div class="landing-split">
            <div class="landing-split-copy">
                <p class="landing-kicker">Tu panel de control</p>
                <h2>La información importante, sin ruido.</h2>
                <p>El dashboard está pensado para consultar rápido y volver a tus datos cuando los necesites.</p>
                <ul class="landing-list">
                    <li><span class="landing-list-mark">01</span><div><strong>Elige tu ubicación</strong><span>Usa una provincia predefinida o tus propias coordenadas.</span></div></li>
                    <li><span class="landing-list-mark">02</span><div><strong>Explora cada día</strong><span>Haz clic en un día para ver su evolución horaria de temperatura y viento.</span></div></li>
                    <li><span class="landing-list-mark">03</span><div><strong>Registra y revisa</strong><span>Consulta tus episodios agrupados por fecha, con sus notas disponibles cuando las necesites.</span></div></li>
                </ul>
            </div>
            <div class="landing-preview" aria-label="Vista previa de datos meteorológicos">
                <div class="landing-preview-top">
                    <div><div class="landing-preview-label">Temperatura actual</div><div class="landing-preview-value">22 °C</div></div>
                    <div><div class="landing-preview-label">Viento actual</div><div class="landing-preview-value teal">14 km/h</div></div>
                </div>
                <div class="landing-bars" aria-hidden="true"><span class="landing-bar" style="height: 48%"></span><span class="landing-bar" style="height: 65%"></span><span class="landing-bar"></span><span class="landing-bar"></span><span class="landing-bar"></span><span class="landing-bar"></span><span class="landing-bar"></span></div>
                <div class="landing-bar-labels" aria-hidden="true"><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span></div>
            </div>
        </div>
    </section>

    <section class="landing-cta">
        <div class="landing-section landing-cta-inner">
            <div><h2>Empieza a construir un registro que puedas consultar.</h2><p>Una cuenta te permite guardar tu ubicación, registrar episodios y volver a tus datos cuando quieras.</p></div>
            <a class="landing-button landing-button-dark" href="<?php echo e(route('usuarios.createUsuario')); ?>">Empezar ahora</a>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/landingPage.blade.php ENDPATH**/ ?>