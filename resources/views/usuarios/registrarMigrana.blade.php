@extends("layouts.default")
@section('title', 'Registrar Migraña')
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .migraine-form-shell {
            width: min(720px, calc(100% - 32px));
            margin: 0 auto;
            padding: 42px 0 56px;
        }

        .migraine-form-shell h2 {
            color: #173042;
            margin: 0 0 24px;
        }

        .migraine-section {
            margin-bottom: 20px;
            padding: 22px;
            border: 1px solid #dce7eb;
            border-radius: 12px;
            background: #fff;
        }

        .migraine-section-label {
            display: block;
            margin: 0 0 12px;
            font-weight: 700;
            color: #173042;
            font-size: 0.95rem;
        }

        .date-fields-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .date-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 200px;
        }

        .date-field label {
            font-weight: 700;
            color: #173042;
            font-size: 0.9rem;
        }

        .date-field input {
            min-height: 40px;
            padding: 8px 11px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
            font: inherit;
        }

        .date-field small {
            min-height: 18px;
            color: #647783;
            font-size: .78rem;
        }

        .migraine-notes-textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
            font: inherit;
            resize: vertical;
        }

        .migraine-submit {
            min-height: 44px;
            padding: 10px 26px;
            border: 0;
            border-radius: 8px;
            background: #006994;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
        }

        .migraine-submit:hover {
            background: #004f70;
        }

        .migraine-success {
            margin: 0 0 16px;
            padding: 14px 16px;
            border-radius: 8px;
            background: #f3fbf4;
            border: 1px solid #bfe3c5;
            color: #1e6b34;
            font-weight: 600;
        }

        .field-error {
            margin: 8px 0 0;
            color: #b42318;
            font-size: 0.82rem;
            font-weight: 600;
            min-height: 0;
        }

        @media (max-width: 600px) {
            .date-fields-row {
                flex-direction: column;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inicio = document.getElementById('hora_inicio');
            const fin = document.getElementById('hora_termina');
            const form = document.querySelector('form');

            const actualizarLimites = () => {
                if (inicio.value) {
                    fin.min = inicio.value;
                }

                if (fin.value) {
                    inicio.max = fin.value;
                } else {
                    inicio.removeAttribute('max');
                }

                if (inicio.value && fin.value && fin.value < inicio.value) {
                    fin.setCustomValidity('La hora de fin debe ser posterior o igual a la hora de inicio.');
                } else {
                    fin.setCustomValidity('');
                }
            };

            inicio.addEventListener('input', actualizarLimites);
            fin.addEventListener('input', actualizarLimites);
            actualizarLimites();

            function limpiarErroresCampos() {
                document.querySelectorAll('.field-error').forEach((el) => el.textContent = '');
            }

            function mostrarErrorCampo(id, mensaje) {
                const el = document.getElementById(id);
                if (el) el.textContent = mensaje;
            }

            $(form).on('submit', function (evento) {
                evento.preventDefault();

                limpiarErroresCampos();
                $('#mensajeExito').hide();

                let hayErrores = false;

                if (!document.querySelector('input[name="tipo_migrana"]:checked')) {
                    mostrarErrorCampo('error_tipo_migrana', 'Selecciona un tipo de migraña.');
                    hayErrores = true;
                }
                if (!document.querySelector('input[name="intensidad"]:checked')) {
                    mostrarErrorCampo('error_intensidad', 'Selecciona la intensidad.');
                    hayErrores = true;
                }
                if (!inicio.value) {
                    mostrarErrorCampo('error_hora_inicio', 'Indica la hora de inicio.');
                    hayErrores = true;
                }
                if (!fin.value) {
                    mostrarErrorCampo('error_hora_termina', 'Indica la hora de fin.');
                    hayErrores = true;
                }

                if (hayErrores) {
                    document.querySelector('.migraine-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function () {
                        window.location.href = "{{ route('usuarios.seguimientoMigrana') }}?registrado=1";
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errores = xhr.responseJSON.errors;
                            if (errores.tipo_migrana) mostrarErrorCampo('error_tipo_migrana', errores.tipo_migrana[0]);
                            if (errores.intensidad) mostrarErrorCampo('error_intensidad', errores.intensidad[0]);
                            if (errores.hora_inicio) mostrarErrorCampo('error_hora_inicio', errores.hora_inicio[0]);
                            if (errores.hora_termina) mostrarErrorCampo('error_hora_termina', errores.hora_termina[0]);
                        } else {
                            mostrarErrorCampo('error_hora_termina', 'No se pudo guardar la migraña. Inténtalo de nuevo.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
@section('contenido')
    <div class="migraine-form-shell">
        <h2>¿Tienes dolor de cabeza?, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>

        <div id="mensajeExito" class="migraine-success" style="display:none;"></div>

        <form method="POST" action="{{ route('usuarios.guardarMigrana') }}" novalidate>
            @csrf

            <div class="migraine-section">
                <x-select-tipo-migrana />
                <p class="field-error" id="error_tipo_migrana"></p>
            </div>

            <div class="migraine-section">
                <x-select-intensidad />
                <p class="field-error" id="error_intensidad"></p>
            </div>

            <div class="migraine-section date-fields-row">
                <div class="date-field">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="datetime-local" id="hora_inicio" name="hora_inicio"
                        value="{{ old('hora_inicio', now()->format('Y-m-d\TH:i')) }}" step="60" required>
                    <small>Selecciona cuándo comenzó el episodio.</small>
                    <p class="field-error" id="error_hora_inicio"></p>
                </div>
                <div class="date-field">
                    <label for="hora_termina">Hora de fin</label>
                    <input type="datetime-local" id="hora_termina" name="hora_termina" value="{{ old('hora_termina') }}"
                        step="60" required>
                    <small>Debe ser igual o posterior al inicio.</small>
                    <p class="field-error" id="error_hora_termina"></p>
                </div>
            </div>

            <div class="migraine-section">
                <label class="migraine-section-label" for="notas">Notas (opcional)</label>
                <textarea id="notas" name="notas" maxlength="250" rows="4" class="migraine-notes-textarea"
                    placeholder="Escribe cualquier detalle sobre la migraña">{{ old('notas') }}</textarea>
            </div>

            <button type="submit" class="migraine-submit">Guardar migraña</button>
        </form>
    </div>
@endsection