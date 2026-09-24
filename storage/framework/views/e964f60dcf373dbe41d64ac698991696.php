<label for="intensidad">Intensidad</label>
<select id="intensidad" name="intensidad" style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
    <option value="">Seleccione la intensidad</option>
    <?php $__currentLoopData = $listado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intensidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($intensidad); ?>" <?php if($seleccionado === $intensidad): echo 'selected'; endif; ?>>
            <?php echo e(ucfirst(str_replace('_', ' ', $intensidad))); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<?php /**PATH /var/www/html/resources/views/components/select-intensidad.blade.php ENDPATH**/ ?>