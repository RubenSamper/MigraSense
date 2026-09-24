<?php $__env->startSection('title','Usuarios'); ?>

<?php $__env->startSection('scripts'); ?>
<style>
    .listado-usuarios {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .listado-usuarios h2 {
        color: #006994;
        margin-bottom: 20px;
    }

    .listado-usuarios table {
        width: 100%;
        border-collapse: collapse;
    }

    .listado-usuarios thead th {
        background: #006994;
        color: white;
        padding: 12px 10px;
        font-weight: 600;
        text-align: left;
    }

    .listado-usuarios tbody td {
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .listado-usuarios tbody tr:hover {
        background: #f0f7fa;
    }

    .listado-usuarios tbody tr:nth-child(even) {
        background: #f9f9f9;
    }

    .listado-usuarios tbody tr:nth-child(even):hover {
        background: #f0f7fa;
    }

    .listado-usuarios .btn-eliminar {
        padding: 6px 14px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85em;
    }

    .listado-usuarios .btn-eliminar:hover {
        background: #c0392b;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-left: 5px;
    }

    .dataTables_wrapper .dataTables_length select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 0 5px;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
        color: #555;
        font-size: 0.9em;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 15px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #006994;
        cursor: pointer;
        background: white;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #006994;
        color: white !important;
        border-color: #006994;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #006994;
        color: white !important;
        border-color: #006994;
    }

    .dataTables_wrapper .dataTables_processing {
        color: #006994;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function() {
        var editBaseUrl = "<?php echo e(route('usuarios.editarUsuario', ':id')); ?>";
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
                "url": "<?php echo e(route('usuarios.getListadoUsuario')); ?>",
                "type": "POST",
                "data": {
                    "_token": "<?php echo e(csrf_token()); ?>"
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
                    data: 'rol_nombre',
                    name: 'rol_nombre',
                    searchable: false
                },

                {
                    data: null,
                    name: 'editar',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var acciones = '';
                        if (row.puede_editar) {
                            acciones += '<a href="' + editBaseUrl.replace(':id', row.id) + '" style="padding:6px 14px; background:#3498db; color:white; text-decoration:none; border-radius:4px; font-size:0.85em; margin-right:4px;">Editar</a>';
                        }
                        if (row.puede_eliminar) {
                            acciones += '<input type="button" data-row="' + meta.row + '" value="Eliminar" class="btn-eliminar" />';
                        }
                        return acciones || '<span style="color:#888; font-size:0.85em;">Sin permisos</span>';
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

        $('#listadoUsuario tbody').on('click', 'tr .btn-eliminar', function() {
            var confirmacion = window.confirm('Va a eliminar este usuario. ¿Desea continuar?')

            var dataRow = $(this).attr('data-row')
            var row = table.data()[dataRow];

            if (confirmacion) {
                $.ajax({
                    url: "<?php echo e(route('usuarios.eliminarUsuario')); ?>",
                    type: 'POST',
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
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
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>
<div class="listado-usuarios">
    <h2>Usuarios registrados</h2>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/listarUsuario.blade.php ENDPATH**/ ?>