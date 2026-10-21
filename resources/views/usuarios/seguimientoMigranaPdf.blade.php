<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Seguimiento de migraña - {{ $etiquetaMesSeleccionado }}</title>
    <style>
        /* dompdf no soporta flexbox ni grid: todo con bloques y tablas. */
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #173042;
            margin: 0;
            padding: 28px;
        }

        .cabecera {
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 2px solid #006994;
        }

        .cabecera h1 {
            margin: 0 0 4px;
            font-size: 19px;
            color: #006994;
        }

        .cabecera p {
            margin: 0;
            color: #647783;
            font-size: 11px;
        }

        table.episodios {
            width: 100%;
            border-collapse: collapse;
        }

        table.episodios th,
        table.episodios td {
            border: 1px solid #dce7eb;
            padding: 6px 7px;
            text-align: left;
            vertical-align: top;
        }

        table.episodios th {
            background: #eaf4f8;
            color: #173042;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        table.episodios td {
            font-size: 10px;
        }

        .col-fecha { width: 11%; }
        .col-tipo { width: 14%; }
        .col-dolor { width: 10%; }
        .col-inicio, .col-fin { width: 9%; }
        .col-duracion { width: 10%; }
        .col-diagnostico { width: 37%; }

        .diagnostico-si {
            color: #187a36;
            font-weight: bold;
        }

        .diagnostico-no {
            color: #b42318;
            font-weight: bold;
        }

        .diagnostico-texto {
            margin-top: 3px;
            color: #495a64;
        }

        .sin-registros {
            padding: 36px;
            text-align: center;
            color: #647783;
            border: 1px dashed #b9cdd4;
        }

        .pie {
            margin-top: 22px;
            color: #9aa7ad;
            font-size: 8.5px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="cabecera">
        <h1>Seguimiento de migraña</h1>
        <p>{{ $usuario->nombre }} {{ $usuario->apellidos }} &middot; {{ $etiquetaMesSeleccionado }}</p>
    </div>

    @if ($migranasDelMes->isEmpty())
        <div class="sin-registros">No hay episodios registrados en {{ $etiquetaMesSeleccionado }}.</div>
    @else
        <table class="episodios">
            <thead>
                <tr>
                    <th class="col-fecha">Fecha</th>
                    <th class="col-tipo">Tipo</th>
                    <th class="col-dolor">Nivel de dolor</th>
                    <th class="col-inicio">Inicio</th>
                    <th class="col-fin">Fin</th>
                    <th class="col-duracion">Duración</th>
                    <th class="col-diagnostico">Diagnóstico del médico</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($migranasDelMes as $episodio)
                    @php
                        $inicio = \Carbon\Carbon::parse($episodio->hora_inicio);
                        $fin = $episodio->hora_termina ? \Carbon\Carbon::parse($episodio->hora_termina) : null;
                        $duracion = $fin ? trim($inicio->diff($fin)->format('%hh %im')) : 'Sin hora de fin';
                        $tieneDiagnostico = filled($episodio->diagnostico);
                    @endphp
                    <tr>
                        <td class="col-fecha">{{ $inicio->format('d/m/Y') }}</td>
                        <td class="col-tipo">{{ \App\Support\MigranaVisual::etiquetaTipo($episodio->tipo_migrana) }}</td>
                        <td class="col-dolor">{{ ucfirst($episodio->intensidad) }}</td>
                        <td class="col-inicio">{{ $inicio->format('H:i') }}</td>
                        <td class="col-fin">{{ $fin ? $fin->format('H:i') : '—' }}</td>
                        <td class="col-duracion">{{ $duracion }}</td>
                        <td class="col-diagnostico">
                            @if ($tieneDiagnostico)
                                <span class="diagnostico-si">Sí</span>
                                <div class="diagnostico-texto">{{ $episodio->diagnostico }}</div>
                            @else
                                <span class="diagnostico-no">No</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="pie">Generado el {{ now()->locale('es')->translatedFormat('d/m/Y \a \l\a\s H:i') }} &middot; MigraSense</p>
</body>
</html>
