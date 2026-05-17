@extends("layouts.default")

@section('title','Lista de Tipos de Abonos')

@section('scripts')
<script>
    $(document).ready(function() {
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
            "columns": [{
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
                    data: 'rol_id',
                    name: 'rol_id'
                },

                {
                    data: null,
                    name: 'eliminar',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return '<input type="button" data-row="' + meta.row + '" value="Eliminar" />'
                    },
                }
            ],
            language: {
                "decimal": ",",
                "emptyTable": "No hay datos en la tabla",
                "info": "Monstrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "",
                "infoFiltered": "",
                "infoPostFix": "",
                "thousands": ".",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No han encontrado registros",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": Click/return para ordenar ascendentemente",
                    "sortDescending": ": Click/return para ordenar descendentemente"
                }
            }
        });

        $('#listadoUsuario tbody').on('click', 'tr input[value="Eliminar"]', function() {
            var confirmacion = window.confirm('Va a eliminar este usuario. ¿Desea continuar?')

            var dataRow = $(this).attr('data-row')
            var row = table.data()[dataRow];

            $('#listaUsuarios tbody').on('click', 'tr input[type="checkbox"]', function() {
                var dataRow = $(this).attr('data-row')
                var row = table.data()[dataRow];

            });

            if (confirmacion) {
                $.ajax({
                    url: "{{ route('usuarios.eliminarUsuario') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: row.id
                    },
                    success: function(response) {
                        table.ajax.reload();
                    },
                    error: function(response) {
                        console.error("Error", response);
                        window.alert("Ha ocurrido un problema en el servidor.");
                    }
                });
            }


        });
    });


    function eliminarUsuario(row) {
        $.ajax({
            url: "{{ route('usuarios.eliminarUsuario') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id: row[0]
            },
            success: function(response) {
                console.log(response);
            },
            error: function(response) {
                console.error("Error", response);
                window.alert("Ha ocurrido un problema en el servidor.");
            }
        });
    };
</script>

@endsection

@section('contenido')

<div class="listado">

    <h2 style="font-family: Helvetica;color: #1100ff;">Usuarios registrados</h2>

    <table id="listadoUsuario" class="display" style="width:100%; border: 1px solid #293c29; background-color: #3e8e41;">
        <thead> <!-- Etiqueta -->
            <tr>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29">Id</th>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29">Nombre</th>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29">Apellidos</th>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29">Correo</th>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29">Rol</th>
                <th style="font-family: Helvetica;color: #1100ff; padding:5px; border: 1px solid #293c29"></th>
            </tr>
        </thead>
        <tbody style="font-family: Helvetica;"></tbody>
    </table>
    <a href="{{ route('usuarios.createUsuario') }}" style="margin-top: 10px; display: inline-block; font-family: Helvetica;color: #1100ff;">Crear nuevo usuario</a>

</div>

@endsection