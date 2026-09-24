<?php $__env->startSection('title','Editar Usuario'); ?>
<?php $__env->startSection('scripts'); ?>
<style>
    .registro-wrapper {
        max-width: 500px;
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
    .registro-wrapper input, .registro-wrapper select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
        box-sizing: border-box;
    }
    .registro-wrapper input:focus, .registro-wrapper select:focus {
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
    .registro-wrapper .success-box {
        padding: 10px;
        border: 1px solid #00cc00;
        color: #006600;
        background-color: #e8f5e8;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 0.9em;
        text-align: center;
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
                } else {
                    $('#nombreErrorSpan').text("");
                    $('#nombreErrorSpan').parent().hide();
                    $('#nombreValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }

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
                } else {
                    $('#apellidosErrorSpan').text("");
                    $('#apellidosErrorSpan').parent().hide()
                    $('#apellidosValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }

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
                } else {
                    $('#passwordErrorSpan').text("");
                    $('#passwordValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<div class="registro-wrapper">
    <h3>Editar Usuario</h3>

    <?php if(session('success')): ?>
    <div class="success-box"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form action="<?php echo e(route('usuarios.actualizarUsuario', $usuario->id)); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div style="margin-bottom: 20px;">
            <label for="nombre">Nombre</label>
            <input type="text" onInput="validarNombreUsuario()" id="nombre" name="nombre" value="<?php echo e(old('nombre', $usuario->nombre)); ?>" placeholder="Ej: Juan"/>
            <span id="nombreValido" class="valido" style="color:#00cc00; display:none;">✔ Nombre válido</span>
            <div class="alerta" style="display:none;">
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
            <input type="text" onInput="validarApellidosUsuario()" id="apellidos" name="apellidos" value="<?php echo e(old('apellidos', $usuario->apellidos)); ?>" placeholder="Ej: García López"/>
            <span id="apellidosValido" class="valido" style="color:#00cc00; display:none;">✔ Apellidos válidos</span>
            <div class="alerta" style="display:none;">
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
            <input type="text" id="correo" name="correo" value="<?php echo e(old('correo', $usuario->correo)); ?>" placeholder="ejemplo@correo.com"/>
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
            <label for="rol_id">Rol</label>
            <select id="rol_id" name="rol_id">
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if((int) $rol->id !== 3): ?>
                        <option value="<?php echo e($rol->id); ?>" <?php echo e(old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : ''); ?>><?php echo e($rol->nombre); ?></option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['rol_id'];
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
            <label for="password">Nueva Contraseña <span style="font-weight:normal; color:#888;">(dejar en blanco para mantener)</span></label>
            <input type="password" onInput="validarPasswordUsuario()" id="password" name="password" placeholder="Mínimo 8 caracteres"/>
            <span id="passwordValido" class="valido" style="color:#00cc00; display:none;">✔ Contraseña válida</span>
            <div class="alerta" style="display:none;">
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
        <div style="display:flex; gap:10px;">
            <button type="submit">Guardar cambios</button>
            <a href="<?php echo e(route('usuarios.obtenerListadoUsuarios')); ?>" style="display:block; text-align:center; padding:12px; background:#888; color:white; text-decoration:none; border-radius:5px; font-weight:bold; flex:1;">Cancelar</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/editarUsuario.blade.php ENDPATH**/ ?>