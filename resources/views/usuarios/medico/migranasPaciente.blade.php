@extends("layouts.default")

@section('title', 'Migrañas de ' . $paciente->nombre)

@section('scripts')
    <style>
        .medico-migranas {
            --ink: #173042;
            --muted: #647783;
            --line: #dce7eb;
            --surface: #ffffff;
            --accent: #006994;
            --soft: #eaf4f8;
            width: min(900px, calc(100% - 32px));
            box-sizing: border-box;
            margin: 36px auto 56px;
            color: var(--ink);
        }

        .medico-migranas-volver {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 14px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
        }

        .medico-migranas-volver:hover {
            text-decoration: underline;
        }

        .medico-migranas-header {
            margin-bottom: 20px;
        }

        .medico-migranas-header p.kicker {
            margin: 0 0 6px;
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .medico-migranas-header h2 {
            margin: 0;
            color: var(--ink);
            font-size: clamp(1.5rem, 3.5vw, 2.1rem);
        }

        .medico-migranas-header p.correo {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .episodio-medico {
            margin-bottom: 16px;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 8px 20px rgba(23, 48, 66, 0.06);
        }

        .episodio-medico-fechas {
            font-weight: 700;
        }

        .episodio-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 8px 0;
        }

        .episodio-tag {
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--soft);
            color: var(--ink);
            font-size: .78rem;
        }

        .episodio-tag-stars {
            color: #f2c94c;
            letter-spacing: 2px;
            font-size: 0.9rem;
        }

        /* Insignia que marca el episodio como ya tratado por el médico, en
           cuanto tiene un diagnóstico guardado (se ve sin desplegar nada). */
        .episodio-tag-tratado {
            background: #e3f6e8;
            color: #187a36;
            font-weight: 700;
        }

        .episodio-notas {
            margin: 7px 0 0;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f7f9fa;
            color: var(--muted);
            font-size: .88rem;
        }

        .episodio-notas strong {
            color: var(--ink);
        }

        /* Diagnóstico ya guardado: desplegable igual que el que ve el
           paciente, cerrado por defecto para no ocupar sitio en la lista. */
        .diagnostico-guardado {
            margin-top: 10px;
            padding: 10px 12px;
            border-left: 3px solid var(--accent);
            border-radius: 0 8px 8px 0;
            background: #eef6f9;
        }

        .diagnostico-guardado summary {
            cursor: pointer;
            color: var(--accent);
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .diagnostico-guardado summary::marker { color: var(--accent); }
        .diagnostico-guardado[open] summary { margin-bottom: 8px; }
        .diagnostico-guardado p { margin: 0; color: var(--ink); font-size: .9rem; white-space: pre-line; }

        /* Botón que abre la ventana (dialog) para escribir o editar el
           diagnóstico de un episodio concreto. */
        .btn-diagnostico {
            display: inline-flex;
            align-items: center;
            margin-top: 12px;
            min-height: 38px;
            padding: 0 16px;
            border-radius: 7px;
            font-weight: 700;
            font-size: .88rem;
            cursor: pointer;
        }

        .btn-diagnostico-nuevo {
            border: none;
            background: var(--accent);
            color: #fff;
        }

        .btn-diagnostico-nuevo:hover { background: #004f70; }

        .btn-diagnostico-editar {
            border: 1px solid var(--accent);
            background: #fff;
            color: var(--accent);
        }

        .btn-diagnostico-editar:hover { background: var(--soft); }

        /* Ventana emergente (elemento <dialog> nativo) para escribir el
           diagnóstico, en vez de tener el formulario siempre abierto en la
           propia tarjeta. Se cierra sola al guardar porque el formulario
           hace una recarga normal de la página (con el aviso de éxito ya
           integrado en el layout). */
        .diagnostico-dialog {
            width: min(480px, calc(100vw - 48px));
            padding: 0;
            border: none;
            border-radius: 14px;
            box-shadow: 0 24px 60px rgba(23, 48, 66, .35);
        }

        .diagnostico-dialog::backdrop {
            background: rgba(23, 48, 66, .45);
        }

        .diagnostico-dialog form {
            padding: 22px;
        }

        .diagnostico-dialog h3 {
            margin: 0 0 4px;
            font-size: 1.15rem;
            color: var(--ink);
        }

        .diagnostico-dialog-fecha {
            margin: 0 0 16px;
            color: var(--muted);
            font-size: .85rem;
        }

        .diagnostico-dialog label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: .85rem;
            color: var(--ink);
        }

        .diagnostico-dialog textarea {
            width: 100%;
            box-sizing: border-box;
            min-height: 120px;
            padding: 10px 12px;
            border: 1px solid #cbd9de;
            border-radius: 8px;
            font: inherit;
            resize: vertical;
        }

        .diagnostico-dialog textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 105, 148, .14);
        }

        .diagnostico-dialog-acciones {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        .btn-cancelar-dialogo {
            min-height: 38px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 7px;
            background: #fff;
            color: var(--ink);
            font-weight: 700;
            cursor: pointer;
        }

        .btn-cancelar-dialogo:hover { background: var(--soft); }

        .btn-guardar-dialogo {
            min-height: 38px;
            padding: 0 18px;
            border: none;
            border-radius: 7px;
            background: var(--accent);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-guardar-dialogo:hover { background: #004f70; }

        .btn-guardar-dialogo:disabled {
            background: #b7c4ca;
            cursor: not-allowed;
        }

        .sin-migranas {
            padding: 30px;
            border: 1px dashed #b9cdd4;
            border-radius: 10px;
            color: var(--muted);
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Abrir la ventana de diagnóstico del episodio correspondiente.
            document.querySelectorAll('[data-abrir-dialogo]').forEach((boton) => {
                boton.addEventListener('click', () => {
                    const dialogo = document.getElementById(boton.dataset.abrirDialogo);
                    if (dialogo && typeof dialogo.showModal === 'function') {
                        dialogo.showModal();
                    }
                });
            });

            // Botón "Cancelar" dentro de cada ventana: la cierra sin enviar
            // el formulario. Al guardar, en cambio, la ventana se cierra
            // sola porque el envío normal del formulario recarga la página.
            document.querySelectorAll('[data-cerrar-dialogo]').forEach((boton) => {
                boton.addEventListener('click', () => {
                    boton.closest('dialog')?.close();
                });
            });

            // El botón "Guardar diagnóstico" empieza desactivado y solo se
            // activa cuando hay algo escrito en el texto (si se está
            // editando un diagnóstico que ya tenía texto, empieza activado).
            document.querySelectorAll('.diagnostico-dialog').forEach((dialogo) => {
                const textarea = dialogo.querySelector('textarea');
                const botonGuardar = dialogo.querySelector('.btn-guardar-dialogo');
                if (!textarea || !botonGuardar) return;

                const actualizarEstadoBoton = () => {
                    botonGuardar.disabled = textarea.value.trim().length === 0;
                };

                textarea.addEventListener('input', actualizarEstadoBoton);
                actualizarEstadoBoton();
            });
        });
    </script>
@endsection

@section('contenido')
    <div class="medico-migranas">
        <a class="medico-migranas-volver" href="{{ route('usuarios.medico.misPacientes') }}">&larr; Mis pacientes</a>

        <div class="medico-migranas-header">
            <p class="kicker">Área médica</p>
            <h2>{{ $paciente->nombre }} {{ $paciente->apellidos }}</h2>
            <p class="correo">{{ $paciente->correo }}</p>
        </div>

        @if ($migranas->isNotEmpty())
            @foreach ($migranas as $episodio)
                @php
                    $fechaEpisodio = \Carbon\Carbon::parse($episodio->hora_inicio)->locale('es')->translatedFormat('j \d\e F \d\e Y, H:i');
                    if ($episodio->hora_termina) {
                        $fechaEpisodio .= ' – ' . \Carbon\Carbon::parse($episodio->hora_termina)->locale('es')->translatedFormat('j \d\e F \d\e Y, H:i');
                    }
                    $tieneDiagnostico = filled($episodio->diagnostico);
                    $idDialogo = 'dialogo-diagnostico-' . $episodio->id;
                @endphp
                <article class="episodio-medico">
                    <div class="episodio-medico-fechas">{{ $fechaEpisodio }}</div>
                    <div class="episodio-meta">
                        <span class="episodio-tag">{{ \App\Support\MigranaVisual::icono($episodio->tipo_migrana) }} {{ \App\Support\MigranaVisual::etiquetaTipo($episodio->tipo_migrana) }}</span>
                        <span class="episodio-tag episodio-tag-stars" title="Intensidad: {{ ucfirst($episodio->intensidad) }}">{{ \App\Support\MigranaVisual::estrellas($episodio->intensidad) }}</span>
                        @if ($tieneDiagnostico)
                            <span class="episodio-tag episodio-tag-tratado">&check; Tratado</span>
                        @endif
                    </div>

                    @if (filled($episodio->notas))
                        <p class="episodio-notas"><strong>Notas del paciente:</strong> {{ $episodio->notas }}</p>
                    @endif

                    @if ($tieneDiagnostico)
                        <details class="diagnostico-guardado">
                            <summary>Ver diagnóstico</summary>
                            <p>{{ $episodio->diagnostico }}</p>
                        </details>
                        <button type="button" class="btn-diagnostico btn-diagnostico-editar" data-abrir-dialogo="{{ $idDialogo }}">Editar diagnóstico</button>
                    @else
                        <button type="button" class="btn-diagnostico btn-diagnostico-nuevo" data-abrir-dialogo="{{ $idDialogo }}">Escribir diagnóstico</button>
                    @endif

                    <dialog class="diagnostico-dialog" id="{{ $idDialogo }}" aria-labelledby="{{ $idDialogo }}-titulo">
                        <form method="POST" action="{{ route('usuarios.medico.guardarDiagnostico', $episodio->id) }}">
                            @csrf
                            <h3 id="{{ $idDialogo }}-titulo">Diagnóstico del episodio</h3>
                            <p class="diagnostico-dialog-fecha">{{ $fechaEpisodio }}</p>
                            <label for="diagnostico_{{ $episodio->id }}">Escribe tu valoración</label>
                            <textarea id="diagnostico_{{ $episodio->id }}" name="diagnostico" maxlength="1000"
                                placeholder="Escribe aquí tu valoración sobre este episodio...">{{ old('diagnostico', $episodio->diagnostico) }}</textarea>
                            <div class="diagnostico-dialog-acciones">
                                <button type="button" class="btn-cancelar-dialogo" data-cerrar-dialogo>Cancelar</button>
                                <button type="submit" class="btn-guardar-dialogo" @if (blank(old('diagnostico', $episodio->diagnostico))) disabled @endif>Guardar diagnóstico</button>
                            </div>
                        </form>
                    </dialog>
                </article>
            @endforeach
        @else
            <div class="sin-migranas">Este paciente todavía no ha registrado ninguna migraña.</div>
        @endif
    </div>
@endsection