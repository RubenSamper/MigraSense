@extends("layouts.default")

@section('title', 'Usuarios')

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

        @media (max-width: 1000px) {
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
        }

        @media (max-width: 480px) {
            .listado-usuarios thead {
                display: none;
            }

            .listado-usuarios table,
            .listado-usuarios tbody,
            .listado-usuarios tr,
            .listado-usuarios td {
                display: block;
                width: 100%;
            }

            .listado-usuarios tbody tr {
                margin-bottom: 12px;
                border: 1px solid var(--line);
                border-radius: 10px;
                padding: 8px 10px;
            }

            .listado-usuarios tbody td {
                border-bottom: none;
                padding: 6px 4px;
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
        }
    </style>
    <script>
        $(document).ready(function () {
            var editBaseUrl = "{{ route('usuarios.editarUsuario', ':id') }}";

            var table = $('#listadoUsuario').DataTable({
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

                "ajax": {
                    "url": "{{ route('usuarios.getListadoUsuario') }}",
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
                        name: 'nombre',
                        render: function (data) {
                            return '<span data-label="Nombre">' + data + '</span>';
                        }
                    },
                    {
                        data: 'apellidos',
                        name: 'apellidos',
                        render: function (data) {
                            return '<span data-label="Apellidos">' + data + '</span>';
                        }
                    },
                    {
                        data: 'correo',
                        name: 'correo',
                        render: function (data) {
                            return '<span data-label="Correo">' + data + '</span>';
                        }
                    },
                    {
                        data: 'rol_nombre',
                        name: 'rol_nombre',
                        searchable: false,
                        render: function (data) {
                            return '<span data-label="Rol">' + data + '</span>';
                        }
                    },
                    {
                        data: null,
                        name: 'editar',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            var acciones = '<div class="acciones-usuario" data-label="Acciones">';

                            if (row.puede_editar) {
                                acciones += '<a href="' + editBaseUrl.replace(':id', row.id) + '" class="icon-button icon-button-editar" title="Editar usuario" aria-label="Editar usuario ' + row.nombre + '">✏️</a>';
                            }
                            if (row.puede_eliminar) {
                                acciones += '<button type="button" data-row="' + meta.row + '" class="icon-button icon-button-eliminar" title="Eliminar usuario" aria-label="Eliminar usuario ' + row.nombre + '">🗑️</button>';
                            }
                            if (!row.puede_editar && !row.puede_eliminar) {
                                acciones += '<span class="sin-permisos">Sin permisos</span>';
                            }

                            acciones += '</div>';
                            return acciones;
                        },
                    }
                ],
                language: {
                    "decimal": ",",
                    "emptyTable": "No hay datos en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "",
                    "infoFiltered": "",
                    "infoPostFix": "",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros",
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

            $('#listadoUsuario tbody').on('click', 'tr .icon-button-eliminar', function () {
                var confirmacion = window.confirm('Va a eliminar este usuario. ¿Desea continuar?')

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
                        },
                        error: function (response) {
                            console.error("Error", response);
                            window.alert("Ha ocurrido un problema en el servidor.");
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
            <p class="kicker">Administración</p>
            <h2>Usuarios registrados</h2>
        </div>
        <div class="listado-usuarios-panel">
            <table id="listadoUsuario" class="display">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection