@extends("layouts.default")
@section('title','Registrar Migraña')
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .migraine-form-shell {
            width: min(1000px, calc(100% - 32px));
            margin: 0 auto;
            padding: 30px 0;
        }

        .date-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            width: 220px;
        }

        .date-fields-row {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: nowrap;
        }

        .migraine-form-row {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .migraine-form-row > * { min-width: 0; }

        .date-field small {
            min-height: 30px;
            color: #647783;
            font-size: .78rem;
        }

        @media (max-width: 700px) {
            .migraine-form-shell { width: calc(100% - 32px); padding: 24px 0 40px; }
            .migraine-form-row { display: grid; grid-template-columns: 1fr; }
            .date-fields-row {
                display: grid;
                grid-template-columns: 1fr;
            }

            .date-field { width: 100%; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inicio = document.getElementById('hora_inicio');
            const fin = document.getElementById('hora_termina');

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
        });
    </script>
@endsection
@section('contenido')
<div class="migraine-form-shell">
    <h2 style="color: #2a0394;">¿Tienes dolor de cabeza?, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>
    <br>

    <form method="POST" action="{{ route('usuarios.guardarMigrana') }}">
        @csrf
        <div class="migraine-form-row">
            <div>
                <x-select-tipo-migrana />
            </div>
            <div>
                <x-select-intensidad />
            </div>
            <div class="date-fields-row">
                <div class="date-field">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="datetime-local" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio', now()->format('Y-m-d\TH:i')) }}" step="60" required>
                    <small>Selecciona cuándo comenzó el episodio.</small>
                </div>
                <div class="date-field">
                    <label for="hora_termina">Hora de fin</label>
                    <input type="datetime-local" id="hora_termina" name="hora_termina" value="{{ old('hora_termina') }}" step="60" required>
                    <small>Debe ser igual o posterior al inicio.</small>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="notas">Notas</label><br>
            <textarea id="notas" name="notas" maxlength="250" rows="4" style="width: 100%; max-width: 600px; padding: 8px; border: 1px solid #ccc; border-radius: 5px;" placeholder="Escribe cualquier detalle sobre la migraña">{{ old('notas') }}</textarea>
        </div>

        @if ($errors->any())
            <ul style="color: #b42318;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <button type="submit">Guardar migraña</button>
    </form>
</div>
@endsection
