<style>
    .intensidad-fieldset {
        border: none;
        padding: 0;
        margin: 0;
    }

    .intensidad-fieldset legend {
        padding: 0;
        margin: 0 0 12px;
        font-weight: 700;
        color: #173042;
        font-size: 0.85rem;
    }

    .intensidad-stars-row {
        display: flex;
        gap: 28px;
    }

    .intensidad-star-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .intensidad-star-col input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .intensidad-star-col .estrella {
        font-size: 2rem;
        line-height: 1;
        color: #dce7eb;
        transition: color .15s;
    }

    .intensidad-star-col.is-filled .estrella {
        color: #f2c94c;
    }

    .intensidad-star-col .nombre {
        font-size: 0.8rem;
        color: #647783;
    }

    .intensidad-star-col.is-selected .nombre {
        color: #173042;
        font-weight: 700;
    }
</style>
<fieldset class="intensidad-fieldset">
    <legend>Intensidad</legend>
    <div class="intensidad-stars-row">
        @foreach ($listado as $indice => $nivel)
            <label class="intensidad-star-col" data-indice="{{ $indice }}">
                <input type="radio" name="intensidad" value="{{ $nivel }}" @checked($seleccionado === $nivel) required>
                <span class="estrella">★</span>
                <span class="nombre">{{ ucfirst($nivel) }}</span>
            </label>
        @endforeach
    </div>
</fieldset>
<script>
    (function () {
        function pintarHasta(columnas, indiceHasta) {
            columnas.forEach((columna, indice) => {
                columna.classList.toggle('is-filled', indiceHasta !== null && indice <= indiceHasta);
            });
        }

        function indiceSeleccionado(columnas) {
            let seleccionado = -1;
            columnas.forEach((columna, indice) => {
                if (columna.querySelector('input').checked) {
                    seleccionado = indice;
                }
            });
            return seleccionado;
        }

        function actualizarSeleccion(columnas) {
            const seleccionado = indiceSeleccionado(columnas);
            columnas.forEach((columna, indice) => {
                columna.classList.toggle('is-selected', indice === seleccionado);
            });
            pintarHasta(columnas, seleccionado === -1 ? null : seleccionado);
        }

        document.querySelectorAll('.intensidad-stars-row').forEach((fila) => {
            const columnas = Array.from(fila.querySelectorAll('.intensidad-star-col'));

            columnas.forEach((columna, indice) => {
                columna.querySelector('input').addEventListener('change', () => actualizarSeleccion(columnas));

                columna.addEventListener('mouseenter', () => pintarHasta(columnas, indice));
                columna.addEventListener('mouseleave', () => actualizarSeleccion(columnas));
            });

            actualizarSeleccion(columnas);
        });
    })();
</script>