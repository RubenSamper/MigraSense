@extends("layouts.default")
@section('title','Registrar Migraña')
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('contenido')
<div style="max-width: 1000px; margin: 0 auto; padding: 30px 20px;">
    <h2 style="color: #2a0394;">¿Tienes dolor de cabeza?, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</h2>
    <br>

    @if (session('success'))
        <p style="color: #187a36;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('usuarios.guardarMigrana') }}">
        @csrf
        <div style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap; margin-bottom: 20px;">
            <div>
                <x-select-tipo-migrana />
            </div>
            <div>
                <x-select-intensidad />
            </div>
            <div>
                <label for="hora_inicio">Hora de inicio</label>
                <input type="datetime-local" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}" required>
            </div>
            <div>
                <label for="hora_termina">Hora de fin</label>
                <input type="datetime-local" id="hora_termina" name="hora_termina" value="{{ old('hora_termina') }}" required>
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
