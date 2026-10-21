@extends("layouts.default")
@section('title', 'MigraSense | Riesgo de migraña según la presión atmosférica')
@section('scripts')
<style>
    .landing {
        --navy: #102b3a;
        --ink: #173042;
        --muted: #647783;
        --teal: #006994;
        --teal-deep: #004c6a;
        --aqua: #dff1f3;
        --line: #d9e7ea;
        --verde: #4caf6d;
        --amarillo: #f2c94c;
        --rojo: #e15b45;
        color: var(--ink);
        background: #f6faf9;
    }
    .landing-hero {
        position: relative;
        min-height: 440px;
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
        padding: 58px 0 66px;
    }
    .landing-kicker {
        margin: 0 0 16px;
        color: #a9e0e3;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
    }
    .landing-hero h1 {
        max-width: 700px;
        margin: 0;
        font-size: clamp(2.2rem, 5.5vw, 4rem);
        line-height: 1.05;
        letter-spacing: 0;
    }
    .landing-hero-copy {
        max-width: 580px;
        margin: 20px 0 0;
        color: #e6f2f3;
        font-size: clamp(1rem, 1.6vw, 1.15rem);
        line-height: 1.6;
    }
    .landing-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 26px; }
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
    .landing-preview-risk { display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1px solid var(--line); }
    .landing-preview-face { flex-shrink: 0; width: 64px; height: 64px; }
    .landing-preview-risk strong { display: block; color: var(--ink); font-size: 1.05rem; }
    .landing-preview-risk span { color: var(--muted); font-size: .85rem; }
    .landing-preview-top { display: flex; justify-content: space-between; gap: 20px; padding: 18px 0; border-bottom: 1px solid var(--line); }
    .landing-preview-label { color: var(--muted); font-size: .78rem; }
    .landing-preview-value { margin-top: 4px; color: var(--ink); font-size: 1.8rem; font-weight: 800; }
    .landing-preview-value.teal { color: var(--teal); }
    .landing-bars { display: flex; align-items: end; gap: 10px; height: 140px; padding: 20px 8px 0; }
    .landing-bar-stack { flex: 1; display: flex; flex-direction: column-reverse; height: 100%; border-radius: 5px 5px 0 0; overflow: hidden; }
    .landing-bar-stack span { width: 100%; }
    .landing-bar-labels { display: flex; gap: 10px; padding: 8px; color: var(--muted); font-size: .72rem; text-align: center; }
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
        .landing-hero { min-height: 340px; }
        .landing-hero-inner { padding: 40px 0 46px; }
        .landing-section { padding: 54px 0; }
        .landing-service { min-height: auto; }
    }
</style>
@endsection
@section('contenido')
<main class="landing">
    <section class="landing-hero">
        <div class="landing-hero-inner">
            <p class="landing-kicker">MigraSense · anticípate a tu migraña</p>
            <h1>Sabe cuándo la presión atmosférica puede desencadenar tu migraña.</h1>
            <p class="landing-hero-copy">MigraSense compara los datos oficiales de AEMET con tu sensibilidad personal y te muestra un aviso claro, verde, amarillo o rojo, antes de que el dolor aparezca.</p>
            <div class="landing-actions">
                @auth
                    @if(Auth::user()->rol_id == 2)
                        <a class="landing-button landing-button-primary" href="{{ route('usuarios.dashboard') }}">Ir a mi panel</a>
                    @endif
                @else
                    <a class="landing-button landing-button-primary" href="{{ route('usuarios.createUsuario') }}">Crear una cuenta</a>
                    <a class="landing-button landing-button-secondary" href="{{ route('usuarios.login') }}">Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="landing-section">
        <div class="landing-section-heading">
            <p>Una herramienta, tres pasos</p>
            <h2>Del dato meteorológico a un aviso que entiendes.</h2>
            <span>MigraSense convierte la presión atmosférica de tu zona en una señal simple, y guarda tu historial de episodios para que puedas ver tus propios patrones.</span>
        </div>
        <div class="landing-service-grid">
            <article class="landing-service">
                <span class="landing-service-number">01 / RIESGO</span>
                <h3>Un semáforo, no números</h3>
                <p>Elige tu sensibilidad a la presión y MigraSense calcula tu riesgo diario con datos reales de la estación de AEMET más cercana a ti.</p>
            </article>
            <article class="landing-service">
                <span class="landing-service-number">02 / REGISTRO</span>
                <h3>Registro en segundos</h3>
                <p>Elige el tipo de migraña con un icono y la intensidad con estrellas, sin formularios largos ni campos innecesarios.</p>
            </article>
            <article class="landing-service">
                <span class="landing-service-number">03 / SEGUIMIENTO</span>
                <h3>Tus patrones, visibles</h3>
                <p>Revisa tu historial por días y una gráfica que separa tus episodios leves, moderados y fuertes.</p>
            </article>
        </div>
    </section>

    <section class="landing-section">
        <div class="landing-split">
            <div class="landing-split-copy">
                <p class="landing-kicker">Tu panel de control</p>
                <h2>La información importante, sin ruido.</h2>
                <p>El dashboard está pensado para consultar tu riesgo de un vistazo y registrar un episodio en segundos.</p>
                <ul class="landing-list">
                    <li><span class="landing-list-mark">01</span><div><strong>Configura tu ubicación y sensibilidad</strong><span>Elige tu estación meteorológica más cercana y cuánto te afecta la presión atmosférica.</span></div></li>
                    <li><span class="landing-list-mark">02</span><div><strong>Consulta tu riesgo diario</strong><span>Verde, amarillo o rojo, según la variación real de presión en tu zona.</span></div></li>
                    <li><span class="landing-list-mark">03</span><div><strong>Registra y revisa</strong><span>Guarda un episodio desde el propio dashboard y consulta tu evolución por intensidad.</span></div></li>
                </ul>
            </div>
            <div class="landing-preview" aria-label="Vista previa del panel de riesgo">
                <div class="landing-preview-risk">
                    <svg class="landing-preview-face" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="50" cy="50" r="46" fill="#4caf6d" />
                        <circle cx="34" cy="42" r="6" fill="#173042" />
                        <circle cx="66" cy="42" r="6" fill="#173042" />
                        <path d="M30 62 Q50 82 70 62" stroke="#173042" stroke-width="5" fill="none" stroke-linecap="round" />
                    </svg>
                    <div>
                        <strong>Riesgo bajo</strong>
                        <span>Presión estable en tu zona hoy</span>
                    </div>
                </div>
                <div class="landing-preview-top">
                    <div><div class="landing-preview-label">Temperatura actual</div><div class="landing-preview-value">22 °C</div></div>
                    <div><div class="landing-preview-label">Presión atmosférica</div><div class="landing-preview-value teal">1018 hPa</div></div>
                </div>
                <div class="landing-bars" aria-hidden="true">
                    <div class="landing-bar-stack"><span style="height: 60%; background: #2f80ed;"></span><span style="height: 15%; background: #f2c94c;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 30%; background: #2f80ed;"></span><span style="height: 45%; background: #f2994a;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 70%; background: #2f80ed;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 20%; background: #2f80ed;"></span><span style="height: 20%; background: #f2c94c;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 55%; background: #2f80ed;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 10%; background: #2f80ed;"></span><span style="height: 55%; background: #f2994a;"></span></div>
                    <div class="landing-bar-stack"><span style="height: 40%; background: #2f80ed;"></span></div>
                </div>
                <div class="landing-bar-labels" aria-hidden="true"><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span></div>
            </div>
        </div>
    </section>

    <section class="landing-cta">
        <div class="landing-section landing-cta-inner">
            @auth
                @if(Auth::user()->rol_id == 2)
                    <div><h2>Sigue anticipándote a tu migraña.</h2><p>Consulta tu riesgo diario y registra un nuevo episodio desde tu panel.</p></div>
                    <a class="landing-button landing-button-dark" href="{{ route('usuarios.dashboard') }}">Ir a mi panel</a>
                @endif
            @else
                <div><h2>Empieza a anticiparte a tu migraña.</h2><p>Crea tu cuenta, configura tu ubicación y tu sensibilidad, y empieza a ver tu riesgo diario.</p></div>
                <a class="landing-button landing-button-dark" href="{{ route('usuarios.createUsuario') }}">Empezar ahora</a>
            @endauth
        </div>
    </section>
</main>
@endsection