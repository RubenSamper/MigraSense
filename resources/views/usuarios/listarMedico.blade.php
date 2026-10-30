@extends("layouts.default")

@section('title', 'Médicos')

@section('scripts')
    <style>
        .listado-usuarios {
            --ink: #173042;
            --muted: #647783;
            --line: #dce7eb;
            --surface: #ffffff;
            --accent: #006994;
            width: min(1100px, calc(100% - 32px));
            box-sizing: border-box;
            margin: 36px auto 56px;
            padding: 0;
            color: var(--ink);
        }

        .listado-usuarios-header {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .listado-usuarios-header p.kicker {
            margin: 0 0 6px;
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .listado-usuarios-header h2 {
            margin: 0;
            color: var(--ink);
            font-size: clamp(1.5rem, 3.5vw, 2.1rem);
        }

        .boton-nuevo-medico {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 42px;
            padding: 0 18px;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .boton-nuevo-medico:hover {
            background: #004f70;
        }

        .listado-usuarios-panel {
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 12px 30px rgba(23, 48, 66, 0.07);
        }

        .listado-usuarios table {
            width: 100%;
            border-collapse: collapse;
        }

        .listado-usuarios thead th {
            background: var(--accent);
            color: white;
            padding: 12px 10px;
            font-weight: 600;
            text-align: left;
        }

        .listado-usuarios thead th:first-child {
            border-top-left-radius: 8px;
        }

        .listado-usuarios thead th:last-child {
            border-top-right-radius: 8px;
        }

        .listado-usuarios tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .listado-usuarios tbody tr:hover {
            background: #f0f7fa;
        }

        .listado-usuarios tbody tr:nth-child(even) {
            background: #f9fbfc;
        }

        .listado-usuarios tbody tr:nth-child(even):hover {
            background: #f0f7fa;
        }

        .acciones-usuario {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.1rem;
            line-height: 1;
            transition: background .15s, border-color .15s, transform .1s;
        }

        .icon-button:hover {
            transform: translateY(-1px);
        }

        .icon-button-editar {
            color: #006994;
            border-color: #b9d1d9;
        }

        .icon-button-editar:hover {
            background: #eaf4f8;
            border-color: #006994;
        }

        .icon-button-eliminar {
            color: #b42318;
            border-color: #f0c9c2;
        }

        .icon-button-eliminar:hover {
            background: #fff1ef;
            border-color: #b42318;
        }

        .sin-permisos {
            color: var(--muted);
            font-size: 0.85em;
        }

        .listado-usuarios-vacio {
            padding: 24px 4px;
            color: var(--muted);
            text-align: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            min-height: 38px;
            box-sizing: border-box;
            padding: 8px 12px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            min-height: 36px;
            padding: 5px 8px;
            border: 1px solid #cbd9de;
            border-radius: 7px;
            margin: 0 6px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 14px;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 15px;
            color: var(--muted);
            font-size: 0.88em;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 15px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 36px;
            min-height: 36px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            margin: 0 2px;
            border: 1px solid var(--line);
            border-radius: 7px;
            color: var(--accent);
            cursor: pointer;
            background: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--accent);
            color: white !important;
            border-color: var(--accent);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--accent);
            color: white !important;
            border-color: var(--accent);
        }

        .dataTables_wrapper .dataTables_processing {
            color: var(--accent);
            font-weight: bold;
        }

        .dataTables_wrapper {
            overflow-x: auto;
        }

        @media (max-width: 700px) {
            .listado-usuarios {
                margin: 24px auto 40px;
            }

            .listado-usuarios-panel {
                padding: 14px;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-left: 0;
                margin-top: 6px;
            }

            .dataTables_wrapper .dataTables_length select {
                margin-left: 0;
            }

            /* Mismo motivo que en la tabla de pacientes: hay que forzar a
               "auto" los anchos en píxeles que DataTables fija en línea,
               si no las tarjetas se quedan encajonadas en el ancho de
               escritorio. */
            .dataTables_wrapper,
            .listado-usuarios table,
            .listado-usuarios tbody,
            .listado-usuarios tr,
            .listado-usuarios td {
                width: auto !important;
                max-width: 100% !important;
            }

            .listado-usuarios {
                width: calc(100% - 32px);
                max-width: 100%;
            }

            .dataTables_wrapper {
                overflow-x: hidden;
            }

            .listado-usuarios table {
                table-layout: auto !important;
            }

            .listado-usuarios thead {
                display: none;
            }

            .listado-usuarios table,
            .listado-usuarios tbody,
            .listado-usuarios tr,
            .listado-usuarios td {
                display: block !important;
            }

            .listado-usuarios tbody tr {
                margin-bottom: 12px;
                border: 1px solid var(--line);
                border-radius: 10px;
                padding: 8px 10px;
                box-sizing: border-box;
            }

            .listado-usuarios tbody td {
                border-bottom: none;
                padding: 6px 4px;
                box-sizing: border-box;
                white-space: normal !important;
                word-break: break-word;
            }

            .listado-usuarios tbody td::before {
                content: attr(data-label);
                display: block;
                font-size: 0.72rem;
                font-weight: 700;
                color: var(--muted);
                text-transform: uppercase;
                margin-bottom: 2px;
            }

            .acciones-usuario {
                justify-content: flex-start;
            }
        }
    </style>
    <script>
        function mostrarNotificacion(tipo, mensaje) {
            var stack = document.querySelector('.notification-stack');
            if (!stack) return;

            var estilos = {
                success: { label: 'Éxito', clase: 'notification-success', marca: '✓', rol: 'status' },
                error: { label: 'Error', clase: 'notification-error', marca: '!', rol: 'alert' },
                warning: { label: 'Aviso', clase: 'notification-warning', marca: '!', rol: 'alert' },
                info: { label: 'Información', clase: 'notification-info', marca: 'i', rol: 'status' },
            };
            var estilo = estilos[tipo] || estilos.info;

            var item = document.createElement('div');
            item.className = 'notification-item';
            item.innerHTML =
                '<div class="notification ' + estilo.clase + '" role="' + estilo.rol + '" aria-live="polite">' +
                    '<div class="notification-mark" aria-hidden="true">' + estilo.marca + '</div>' +
                    '<div class="notification-content"><strong></strong><span></span></div>' +
                '</div>';

            item.querySelector('strong').textContent = estilo.label;
            item.querySelector('span').textContent = mensaje;

            stack.appendChild(item);

            setTimeout(function () {
                item.remove();
            }, 4000);
        }

        $(document).ready(function () {
            var editBaseUrl = "{{ route('usuarios.editarUsuario', ':id') }}";

            var table = $('#listadoMedico').DataTable({
                "paging": true,
                "pagingType": "numbers",
                "lengthChange": true,
                "lengthMenu": [
                    [5, 10, 100],
                    [5, 10, 100]
                ],
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "serverSide": true,
                "autoWidth": false,

                "ajax": {
                    "url": "{{ route('usuarios.getListadoMedico') }}",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'apellidos',
                        name: 'apellidos'
                    },
                    {
                        data: 'correo',
                        name: 'correo'
                    },
                    {
                        data: null,
                        name: 'editar',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            var acciones = '<div class="acciones-usuario">';

                            if (row.puede_editar) {
                                acciones += '<a href="' + editBaseUrl.replace(':id', row.id) + '" class="icon-button icon-button-editar" title="Editar médico" aria-label="Editar médico ' + row.nombre + '">✏️</a>';
                            }
                            if (row.puede_eliminar) {
                                acciones += '<button type="button" data-row="' + meta.row + '" class="icon-button icon-button-eliminar" title="Eliminar médico" aria-label="Eliminar médico ' + row.nombre + '">🗑️</button>';
                            }
                            if (!row.puede_editar && !row.puede_eliminar) {
                                acciones += '<span class="sin-permisos">Sin permisos</span>';
                            }

                            acciones += '</div>';
                            return acciones;
                        },
                    }
                ],
                "columnDefs": [
                    { targets: 1, createdCell: function (td) { $(td).attr('data-label', 'Nombre'); } },
                    { targets: 2, createdCell: function (td) { $(td).attr('data-label', 'Apellidos'); } },
                    { targets: 3, createdCell: function (td) { $(td).attr('data-label', 'Correo'); } },
                    { targets: 4, createdCell: function (td) { $(td).attr('data-label', 'Acciones'); } }
                ],
                language: {
                    "decimal": ",",
                    "emptyTable": "Todavía no hay médicos registrados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "",
                    "infoFiltered": "",
                    "infoPostFix": "",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron médicos",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Click para ordenar ascendente",
                        "sortDescending": ": Click para ordenar descendente"
                    }
                }
            });

            $('#listadoMedico tbody').on('click', 'tr .icon-button-eliminar', function () {
                var confirmacion = window.confirm('Va a eliminar este médico. Sus pacientes se quedarán sin médico asignado. ¿Desea continuar?')

                var dataRow = $(this).attr('data-row')
                var row = table.data()[dataRow];

                if (confirmacion) {
                    $.ajax({
                        url: "{{ route('usuarios.eliminarUsuario') }}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: row.id
                        },
                        success: function (response) {
                            table.ajax.reload();
                            mostrarNotificacion('success', (response && response.message) || 'Médico eliminado correctamente.');
                        },
                        error: function (response) {
                            console.error("Error", response);
                            mostrarNotificacion('error', 'Ha ocurrido un problema en el servidor.');
                        }
                    });
                }
            });
        });
    </script>
@endsection

@section('contenido')
    <div class="listado-usuarios">
        <div class="listado-usuarios-header">
            <div>
                <p class="kicker">Administración</p>
                <h2>Médicos registrados</h2>
            </div>
            <a class="boton-nuevo-medico" href="{{ route('usuarios.crearMedico') }}">+ Nuevo médico</a>
        </div>
        <div class="listado-usuarios-panel">
            <table id="listadoMedico" class="display">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection