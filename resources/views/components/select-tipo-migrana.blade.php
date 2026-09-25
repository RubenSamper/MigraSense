<style>
    .tipo-migrana-fieldset { border: none; padding: 0; margin: 0; }
    .tipo-migrana-fieldset legend {
        padding: 0;
        margin: 0 0 8px;
        font-weight: 700;
        color: #173042;
        font-size: 0.85rem;
    }
    .tipo-migrana-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
        gap: 8px;
    }
    .tipo-migrana-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        padding: 10px 6px;
        border: 2px solid #dce7eb;
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
        background: #fff;
    }
    .tipo-migrana-card:hover { border-color: #006994; }
    .tipo-migrana-card input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .tipo-migrana-card:has(input:checked) {
        border-color: #006994;
        background: #f1f8fa;
    }
    .tipo-migrana-icon { font-size: 1.7rem; }
    .tipo-migrana-label { font-size: 0.76rem; color: #173042; }
</style>
<fieldset class="tipo-migrana-fieldset">
    <legend>Tipo de migraña</legend>
    <div class="tipo-migrana-grid">
        @foreach ($listado as $tipoMigrana)
            @php $icono = $iconos[$tipoMigrana] ?? '❓'; @endphp
            <label class="tipo-migrana-card" title="{{ ucfirst(str_replace('_', ' ', $tipoMigrana)) }}">
                <input type="radio" name="tipo_migrana" value="{{ $tipoMigrana }}" @checked($seleccionado === $tipoMigrana) required>
                <span class="tipo-migrana-icon" aria-hidden="true">{{ $icono }}</span>
                <span class="tipo-migrana-label">{{ ucfirst(str_replace('_', ' ', $tipoMigrana)) }}</span>
            </label>
        @endforeach
    </div>
</fieldset>