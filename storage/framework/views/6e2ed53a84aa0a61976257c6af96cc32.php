<?php $__env->startSection('title','Nuevo Usuario'); ?>
<?php $__env->startSection('scripts'); ?>
<style>
    .registro-wrapper {
        width: min(500px, calc(100% - 32px));
        box-sizing: border-box;
        margin: 40px auto;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        background: #fff;
    }
    .registro-wrapper h3 {
        color: #006994;
        margin: 0 0 20px;
        text-align: center;
        font-size: 1.5em;
    }
    .registro-wrapper label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }
    .registro-wrapper input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
        box-sizing: border-box;
    }
    .registro-wrapper input:focus {
        outline: none;
        border-color: #006994;
        box-shadow: 0 0 5px rgba(0,105,148,0.3);
    }
    .registro-wrapper button {
        width: 100%;
        padding: 12px;
        background: #006994;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
    }
    .registro-wrapper button:hover {
        background: #004b6b;
    }
    .registro-wrapper .alerta {
        padding: 8px;
        border: 1px solid #ff0000;
        color: #ff0000;
        background-color: #ced3eb;
        border-radius: 5px;
        margin-top: 5px;
        font-size: 0.9em;
    }
    .registro-wrapper .valido {
        margin-left: 5px;
        color: #00cc00;
        display: none;
    }
    .registro-wrapper .error-box {
        padding: 5px;
        border: 1px solid #ff0000;
        color: #ff0000;
        background-color: #ced3eb;
        margin-top: 5px;
        border-radius: 5px;
        font-size: 0.9em;
        display: none;
    }

    @media (max-width: 480px) {
        .registro-wrapper { margin: 24px auto; padding: 20px 16px; }
    }
</style>
<script>
    function validarNombreUsuario(){
        $.ajax({
            url: "<?php echo e(route('usuarios.validarNombreUsuario')); ?>",
            type: 'POST',
            data: {
                nombre: $('#nombre').val(),
                _token: '<?php echo e(csrf_token()); ?>',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#nombreErrorSpan').text(response.errors[0]);
                    $('#nombreValido').hide();
                    $('#nombreErrorSpan').parent().show();
                }
                else {
                    $('#nombreErrorSpan').text("");
                    $('#nombreErrorSpan').parent().hide();
                    $('#nombreValido').show();
                }
            },

            error: function (response) {
                console.error("Error", response);
                window.alert("Ha ocurrido un problema en el servidor.");
            }
        });
    };

    function validarApellidosUsuario(){
         $.ajax({
            url: "<?php echo e(route('usuarios.validarApellidosUsuario')); ?>",
            type: 'POST',
            data: {
                apellidos: $('#apellidos').val(),
                _token: '<?php echo e(csrf_token()); ?>',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#apellidosErrorSpan').text(response.errors[0]);
                    $('#apellidosValido').hide();
                    $('#apellidosErrorSpan').parent().show();
                }
                else {
                     $('#apellidosErrorSpan').text("");
                    $('#apellidosErrorSpan').parent().hide()
                    $('#apellidosValido').show();
                }
            },

            error: function (response) {
                console.error("Error", response);
                window.alert("Ha ocurrido un problema en el servidor.");
            }
        });   
    };

    function validarCorreoUsuario(){
        $.ajax({
            url: "<?php echo e(route('usuarios.validarCorreoUsuario')); ?>",
            type: 'POST',
            data: {
                correo: $('#correo').val(),
                _token: '<?php echo e(csrf_token()); ?>',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#correoErrorSpan').text(response.errors[0]);
                    $('#correoValido').hide();
                    $('#correoErrorSpan').parent().show();
                }
                else {
                    $('#correoErrorSpan').text("");
                    $('#correoErrorSpan').parent().hide();
                    $('#correoValido').show();
                }
            },

            error: function (response) {
                console.error("Error", response);
                window.alert("Ha ocurrido un problema en el servidor.");
            }
        });
    };

    function validarPasswordUsuario(){
        $.ajax({
            url: "<?php echo e(route('usuarios.validarPasswordUsuario')); ?>",
            type: 'POST',
            data: {
                password: $('#password').val(),
                _token: '<?php echo e(csrf_token()); ?>',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#passwordErrorSpan').text(response.errors[0]);
                    $('#passwordValido').hide();
                    $('#passwordErrorSpan').parent().show();
                }
                else {
                    $('#passwordErrorSpan').text("");
                    $('#passwordValido').show();
                }
            },

            error: function (response) {
                console.error("Error", response);
                window.alert("Ha ocurrido un problema en el servidor.");
            }
        });
    };

</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<div class="registro-wrapper">
    <h3>Nuevo Usuario</h3>
    <form action="<?php echo e(route('usuarios.almacenarUsuario')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div style="margin-bottom: 20px;">
            <label for="nombre">Nombre</label>
            <input type="text" onInput="validarNombreUsuario()" id="nombre" name="nombre" value="<?php echo e(old('nombre')); ?>" placeholder="Ej: Juan"/>
            <span id="nombreValido" class="valido">✔ Nombre válido</span>
            <div class="error-box">
                Error: <span id="nombreErrorSpan"></span>
            </div>
            <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alerta"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div style="margin-bottom: 20px;">
            <label for="apellidos">Apellidos</label>
            <input type="text" onInput="validarApellidosUsuario()" id="apellidos" name="apellidos" value="<?php echo e(old('apellidos')); ?>" placeholder="Ej: García López"/>
            <span id="apellidosValido" class="valido">✔ Apellidos válidos</span>
            <div class="error-box">
                Error: <span id="apellidosErrorSpan"></span>
            </div>
            <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alerta"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div style="margin-bottom: 20px;">
            <label for="correo">Correo Electrónico</label>
            <input type="text" onInput="validarCorreoUsuario()" id="correo" name="correo" value="<?php echo e(old('correo')); ?>" placeholder="ejemplo@correo.com"/>
            <span id="correoValido" class="valido">✔ Correo válido</span>
            <div class="error-box">
                Error: <span id="correoErrorSpan"></span>
            </div>
            <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alerta"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password">Contraseña</label>
            <input type="password" onInput="validarPasswordUsuario()" id="password" name="password" placeholder="Mínimo 8 caracteres"/>
            <span id="passwordValido" class="valido">✔ Contraseña válida</span>
            <div class="error-box">
                Error: <span id="passwordErrorSpan"></span>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alerta"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <button type="submit">Registrar</button>
    </form>
    <a href="<?php echo e(route('google.register')); ?>" style="display:block; text-align:center; padding:12px; background:white; color:#3c4043; border:1px solid #dadce0; border-radius:5px; text-decoration:none; font-weight:bold; margin-top:12px;">Crear cuenta con Google</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/crearUsuario.blade.php ENDPATH**/ ?>