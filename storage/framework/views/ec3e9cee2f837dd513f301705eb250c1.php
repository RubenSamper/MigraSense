<label for="tipo_migrana">Tipo de migraña</label>
<select id="tipo_migrana" name="tipo_migrana" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
    <option value="">Seleccione su migraña</option>
    <?php $__currentLoopData = $listado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoMigrana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($tipoMigrana); ?>" <?php if($seleccionado === $tipoMigrana): echo 'selected'; endif; ?>>
            <?php echo e(ucfirst(str_replace('_', ' ', $tipoMigrana))); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select><?php /**PATH /var/www/html/resources/views/components/select-tipo-migrana.blade.php ENDPATH**/ ?>