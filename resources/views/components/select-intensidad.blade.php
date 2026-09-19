<label for="intensidad">Intensidad</label>
<select id="intensidad" name="intensidad" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
    <option value="">Seleccione la intensidad</option>
    @foreach ($listado as $intensidad)
        <option value="{{ $intensidad }}" @selected($seleccionado === $intensidad)>
            {{ ucfirst(str_replace('_', ' ', $intensidad)) }}
        </option>
    @endforeach
</select>
