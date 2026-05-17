@extends("layouts.default")
@section('title','AquaSense')
@section('scripts')
<style>
    .hero {
        text-align: center;
        padding: 80px 20px;
        background: linear-gradient(135deg, #006994, #00a8cc);
        color: white;
    }
    .hero h1 {
        font-size: 3.5em;
        margin: 0 0 10px 0;
    }
    .hero p {
        font-size: 1.3em;
        margin: 0;
        opacity: 0.9;
    }
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 40px;
    }
    .feature-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .feature-card .icon {
        font-size: 2.5em;
        margin-bottom: 10px;
    }
    .feature-card h3 {
        color: #006994;
        margin: 0 0 10px 0;
    }
    .feature-card p {
        color: #555;
        margin: 0;
        font-size: 0.95em;
    }
    .info-section {
        margin-top: 60px;
        text-align: center;
    }
    .info-section h2 {
        color: #006994;
        margin-bottom: 15px;
    }
    .info-section p {
        color: #555;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
</style>
@endsection
@section('contenido')
<div class="hero">
    <h1>AquaSense</h1>
    <p>Monitoreo inteligente de humedad para tu hogar y cultivos</p>
</div>

<div class="container">
    <div class="features">
        <div class="feature-card">
            <div class="icon">💧</div>
            <h3>Monitoreo en Tiempo Real</h3>
            <p>Medición continua de humedad ambiental con actualizaciones al instante.</p>
        </div>
        <div class="feature-card">
            <div class="icon">📊</div>
            <h3>Informes Detallados</h3>
            <p>Historial de datos y gráficos para analizar tendencias de humedad.</p>
        </div>
        <div class="feature-card">
            <div class="icon">🔔</div>
            <h3>Alertas Inteligentes</h3>
            <p>Notificaciones cuando la humedad supera los niveles óptimos.</p>
        </div>
        <div class="feature-card">
            <div class="icon">🌱</div>
            <h3>Optimización de Cultivos</h3>
            <p>Recomendaciones basadas en datos para mejorar el rendimiento.</p>
        </div>
    </div>

    <div class="info-section">
        <h2>¿Qué es AquaSense?</h2>
        <p>
            AquaSense es un sistema de monitoreo de humedad ambiental que permite
            a usuarios y agricultores控制 y supervisar las condiciones de sus espacios
            en tiempo real. Con sensores distribuidos estratégicamente, recopilamos
            datos precisos para ayudarte a tomar decisiones informadas.
        </p>
    </div>
</div>
@endsection
