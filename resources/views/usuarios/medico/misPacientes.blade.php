@extends("layouts.default")

@section('title', 'Mis pacientes')

@section('scripts')
    <style>
        .medico-pacientes {
            --ink: #173042;
            --muted: #647783;
            --line: #dce7eb;
            --surface: #ffffff;
            --accent: #006994;
            width: min(900px, calc(100% - 32px));
            box-sizing: border-box;
            margin: 36px auto 56px;
            color: var(--ink);
        }

        .medico-pacientes-header {
            margin-bottom: 20px;
        }

        .medico-pacientes-header p.kicker {
            margin: 0 0 6px;
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .medico-pacientes-header h2 {
            margin: 0;
            color: var(--ink);
            font-size: clamp(1.5rem, 3.5vw, 2.1rem);
        }

        .lista-pacientes {
            display: grid;
            gap: 12px;
        }

        .tarjeta-paciente {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 16px 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 8px 20px rgba(23, 48, 66, 0.06);
            text-decoration: none;
            color: var(--ink);
            transition: border-color .15s, transform .1s;
        }

        .tarjeta-paciente:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        /* Paciente con episodios todavía sin leer: se resalta para que
           llame la atención en la lista. */
        .tarjeta-paciente.tiene-pendientes {
            border-color: #f2c94c;
            background: #fffaf0;
        }

        .tarjeta-paciente.tiene-pendientes:hover {
            border-color: #d9a520;
        }

        .tarjeta-paciente-info {
            min-width: 0;
        }

        .tarjeta-paciente-nombre {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .tarjeta-paciente-correo {
            margin-top: 2px;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .tarjeta-paciente-ultimo {
            margin-top: 2px;
            color: var(--muted);
            font-size: 0.82rem;
        }

        .contador-sin-leer {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background: #e15b45;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1;
        }

        .tarjeta-paciente-flecha {
            color: var(--accent);
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .sin-pacientes {
            padding: 30px;
            border: 1px dashed #b9cdd4;
            border-radius: 10px;
            color: var(--muted);
            text-align: center;
        }
    </style>
@endsection

@section('contenido')
    <div class="medico-pacientes">
        <div class="medico-pacientes-header">
            <p class="kicker">Área médica</p>
            <h2>Mis pacientes</h2>
        </div>

        @if ($pacientes->isNotEmpty())
            <div class="lista-pacientes">
                @foreach ($pacientes as $paciente)
                    <a class="tarjeta-paciente @if($paciente->episodios_sin_leer > 0) tiene-pendientes @endif"
                        href="{{ route('usuarios.medico.migranasPaciente', $paciente->id) }}">
                        <div class="tarjeta-paciente-info">
                            <div class="tarjeta-paciente-nombre">
                                {{ $paciente->nombre }} {{ $paciente->apellidos }}
                                @if ($paciente->episodios_sin_leer > 0)
                                    <span class="contador-sin-leer"
                                        title="{{ $paciente->episodios_sin_leer }} episodio(s) sin leer">{{ $paciente->episodios_sin_leer }}</span>
                                @endif
                            </div>
                            <div class="tarjeta-paciente-correo">{{ $paciente->correo }}</div>
                            <div class="tarjeta-paciente-ultimo">
                                @if ($paciente->migranas_max_hora_inicio)
                                    Último episodio: {{ \Carbon\Carbon::parse($paciente->migranas_max_hora_inicio)->locale('es')->translatedFormat('j \d\e F \d\e Y, H:i') }}
                                @else
                                    Todavía no ha registrado ningún episodio
                                @endif
                            </div>
                        </div>
                        <span class="tarjeta-paciente-flecha" aria-hidden="true">&rarr;</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="sin-pacientes">Todavía no tienes pacientes asignados.</div>
        @endif
    </div>
@endsection