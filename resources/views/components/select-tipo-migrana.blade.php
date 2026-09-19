<label for="tipo_migrana">Tipo de migraña</label>
<select id="tipo_migrana" name="tipo_migrana" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
    <option value="">Seleccione su migraña</option>
    @foreach ($listado as $tipoMigrana)
        <option value="{{ $tipoMigrana }}" @selected($seleccionado === $tipoMigrana)>
            {{ ucfirst(str_replace('_', ' ', $tipoMigrana)) }}
        </option>
    @endforeach
</select>