<?php $__env->startSection('title','Nuevo Usuario'); ?>
<?php $__env->startSection('scripts'); ?>
<style>
    .registro-wrapper {
        width: min(520px, calc(100% - 32px));
        box-sizing: border-box;
        margin: 48px auto;
        padding: 34px;
        border: 1px solid #d8e4e8;
        border-radius: 14px;
        box-shadow: 0 12px 30px rgba(23, 48, 66, 0.1);
        background: #ffffff;
    }
    .registro-wrapper h3 {
        color: #173042;
        margin: 0 0 8px;
        text-align: center;
        font-size: 1.65em;
    }
    .registro-intro { margin: 0 0 28px; color: #60747d; text-align: center; }
    .campo { margin-bottom: 22px; }
    .registro-wrapper label {
        display: block;
        margin-bottom: 7px;
        color: #173042;
        font-weight: bold;
    }
    .campo-control { position: relative; }
    .registro-wrapper input {
        width: 100%;
        padding: 12px 42px 12px 13px;
        border: 1px solid #b7c8ce;
        border-radius: 8px;
        font-size: 1em;
        box-sizing: border-box;
        color: #173042;
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }
    .registro-wrapper input:focus {
        outline: none;
        border-color: #007f86;
        box-shadow: 0 0 0 3px rgba(0,127,134,.14);
    }
    .registro-wrapper input.is-valid { border-color: #198754; background: #f4fbf7; }
    .registro-wrapper input.is-invalid { border-color: #c0392b; background: #fff8f7; }
    .campo-icon { position: absolute; top: 50%; right: 13px; transform: translateY(-50%); font-size: 1.1em; font-weight: bold; }
    .campo-icon.valid { color: #198754; }
    .campo-icon.invalid { color: #c0392b; }
    .campo-mensaje { min-height: 20px; margin: 5px 0 0; font-size: .88em; }
    .campo-mensaje.error { color: #a93226; }
    .campo-mensaje.success { color: #19703f; }
    .campo-mensaje .server-error { display: block; }
    .registro-wrapper button {
        width: 100%;
        padding: 13px;
        background: #007f86;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
    }
    .registro-wrapper button:hover {
        background: #00666c;
    }
    .registro-wrapper .google-link { display: block; margin-top: 12px; padding: 12px; border: 1px solid #dadce0; border-radius: 8px; color: #3c4043; text-align: center; text-decoration: none; font-weight: bold; }
    .registro-wrapper .google-link:hover { background: #f8f9fa; }

    @media (max-width: 480px) {
        .registro-wrapper { margin: 24px auto; padding: 20px 16px; }
    }
</style>
<script>
    function mostrarEstado(campo, valido, mensaje) {
        const input = $('#' + campo);
        const icono = $('#' + campo + 'Icon');
        const ayuda = $('#' + campo + 'Mensaje');
        input.toggleClass('is-valid', valido).toggleClass('is-invalid', !valido).attr('aria-invalid', !valido);
        icono.html(valido ? '&#10003;' : '!').removeAttr('hidden').toggleClass('valid', valido).toggleClass('invalid', !valido).show();
        ayuda.text(mensaje).toggleClass('success', valido).toggleClass('error', !valido);
    }

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
                    mostrarEstado('nombre', false, response.errors[0]);
                }
                else {
                    mostrarEstado('nombre', true, 'Nombre válido');
                }
            },

            error: function (response) {
                console.error("Error", response);
                mostrarEstado('nombre', false, 'No se pudo validar el nombre.');
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
                    mostrarEstado('apellidos', false, response.errors[0]);
                }
                else {
                    mostrarEstado('apellidos', true, 'Apellidos válidos');
                }
            },

            error: function (response) {
                console.error("Error", response);
                mostrarEstado('apellidos', false, 'No se pudieron validar los apellidos.');
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
                    mostrarEstado('correo', false, response.errors[0]);
                }
                else {
                    mostrarEstado('correo', true, 'Correo disponible');
                }
            },

            error: function (response) {
                console.error("Error", response);
                mostrarEstado('correo', false, 'No se pudo validar el correo.');
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
                    mostrarEstado('password', false, response.errors[0]);
                }
                else {
                    mostrarEstado('password', true, 'Contraseña válida');
                }
            },

            error: function (response) {
                console.error("Error", response);
                mostrarEstado('password', false, 'No se pudo validar la contraseña.');
            }
        });
    };

</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<div class="registro-wrapper">
    <h3>Nuevo usuario</h3>
    <p class="registro-intro">Completa tus datos para crear una cuenta.</p>
    <form action="<?php echo e(route('usuarios.almacenarUsuario')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="campo">
            <label for="nombre">Nombre</label>
            <div class="campo-control"><input type="text" onInput="validarNombreUsuario()" id="nombre" name="nombre" value="<?php echo e(old('nombre')); ?>" placeholder="Ej: Blanca" maxlength="255" autocomplete="given-name" aria-describedby="nombreMensaje" aria-invalid="false"/><span id="nombreIcon" class="campo-icon" hidden aria-hidden="true"></span></div>
            <p id="nombreMensaje" class="campo-mensaje" aria-live="polite">Solo letras y espacios.</p>
            <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="campo-mensaje error server-error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="campo">
            <label for="apellidos">Apellidos</label>
            <div class="campo-control"><input type="text" onInput="validarApellidosUsuario()" id="apellidos" name="apellidos" value="<?php echo e(old('apellidos')); ?>" placeholder="Ej: García López" maxlength="255" autocomplete="family-name" aria-describedby="apellidosMensaje" aria-invalid="false"/><span id="apellidosIcon" class="campo-icon" hidden aria-hidden="true"></span></div>
            <p id="apellidosMensaje" class="campo-mensaje" aria-live="polite">Solo letras y espacios.</p>
            <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="campo-mensaje error server-error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="campo">
            <label for="correo">Correo Electrónico</label>
            <div class="campo-control"><input type="email" onInput="validarCorreoUsuario()" id="correo" name="correo" value="<?php echo e(old('correo')); ?>" placeholder="ejemplo@correo.com" maxlength="255" autocomplete="email" aria-describedby="correoMensaje" aria-invalid="false"/><span id="correoIcon" class="campo-icon" hidden aria-hidden="true"></span></div>
            <p id="correoMensaje" class="campo-mensaje" aria-live="polite">Usaremos este correo para iniciar sesión.</p>
            <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="campo-mensaje error server-error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="campo">
            <label for="password">Contraseña</label>
            <div class="campo-control"><input type="password" onInput="validarPasswordUsuario()" id="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8" autocomplete="new-password" aria-describedby="passwordMensaje" aria-invalid="false"/><span id="passwordIcon" class="campo-icon" hidden aria-hidden="true"></span></div>
            <p id="passwordMensaje" class="campo-mensaje" aria-live="polite">Debe tener al menos 8 caracteres.</p>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="campo-mensaje error server-error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <button type="submit">Crear usuario</button>
    </form>
    <a class="google-link" href="<?php echo e(route('google.register')); ?>">Crear cuenta con Google</a>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/crearUsuario.blade.php ENDPATH**/ ?>