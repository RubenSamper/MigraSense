<?php $__env->startSection('title','Registrar Migraña'); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .migraine-form-shell {
            width: min(1000px, calc(100% - 32px));
            margin: 0 auto;
            padding: 30px 0;
        }

        .date-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            width: 220px;
        }

        .date-fields-row {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: nowrap;
        }

        .migraine-form-row {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .migraine-form-row > * { min-width: 0; }

        .date-field small {
            min-height: 30px;
            color: #647783;
            font-size: .78rem;
        }

        @media (max-width: 700px) {
            .migraine-form-shell { width: calc(100% - 32px); padding: 24px 0 40px; }
            .migraine-form-row { display: grid; grid-template-columns: 1fr; }
            .date-fields-row {
                display: grid;
                grid-template-columns: 1fr;
            }

            .date-field { width: 100%; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inicio = document.getElementById('hora_inicio');
            const fin = document.getElementById('hora_termina');

            const actualizarLimites = () => {
                if (inicio.value) {
                    fin.min = inicio.value;
                }

                if (fin.value) {
                    inicio.max = fin.value;
                } else {
                    inicio.removeAttribute('max');
                }

                if (inicio.value && fin.value && fin.value < inicio.value) {
                    fin.setCustomValidity('La hora de fin debe ser posterior o igual a la hora de inicio.');
                } else {
                    fin.setCustomValidity('');
                }
            };

            inicio.addEventListener('input', actualizarLimites);
            fin.addEventListener('input', actualizarLimites);
            actualizarLimites();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenido'); ?>
<div class="migraine-form-shell">
    <h2 style="color: #2a0394;">¿Tienes dolor de cabeza?, <?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellidos); ?></h2>
    <br>

    <form method="POST" action="<?php echo e(route('usuarios.guardarMigrana')); ?>">
        <?php echo csrf_field(); ?>
        <div class="migraine-form-row">
            <div>
                <?php if (isset($component)) { $__componentOriginal48825951aba906709626aeb69225ede5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48825951aba906709626aeb69225ede5 = $attributes; } ?>
<?php $component = App\View\Components\SelectTipoMigrana::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select-tipo-migrana'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SelectTipoMigrana::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48825951aba906709626aeb69225ede5)): ?>
<?php $attributes = $__attributesOriginal48825951aba906709626aeb69225ede5; ?>
<?php unset($__attributesOriginal48825951aba906709626aeb69225ede5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48825951aba906709626aeb69225ede5)): ?>
<?php $component = $__componentOriginal48825951aba906709626aeb69225ede5; ?>
<?php unset($__componentOriginal48825951aba906709626aeb69225ede5); ?>
<?php endif; ?>
            </div>
            <div>
                <?php if (isset($component)) { $__componentOriginal30546c9622184d79f682866b02deaa20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30546c9622184d79f682866b02deaa20 = $attributes; } ?>
<?php $component = App\View\Components\SelectIntensidad::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select-intensidad'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SelectIntensidad::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30546c9622184d79f682866b02deaa20)): ?>
<?php $attributes = $__attributesOriginal30546c9622184d79f682866b02deaa20; ?>
<?php unset($__attributesOriginal30546c9622184d79f682866b02deaa20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30546c9622184d79f682866b02deaa20)): ?>
<?php $component = $__componentOriginal30546c9622184d79f682866b02deaa20; ?>
<?php unset($__componentOriginal30546c9622184d79f682866b02deaa20); ?>
<?php endif; ?>
            </div>
            <div class="date-fields-row">
                <div class="date-field">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="datetime-local" id="hora_inicio" name="hora_inicio" value="<?php echo e(old('hora_inicio', now()->format('Y-m-d\TH:i'))); ?>" step="60" required>
                    <small>Selecciona cuándo comenzó el episodio.</small>
                </div>
                <div class="date-field">
                    <label for="hora_termina">Hora de fin</label>
                    <input type="datetime-local" id="hora_termina" name="hora_termina" value="<?php echo e(old('hora_termina')); ?>" step="60" required>
                    <small>Debe ser igual o posterior al inicio.</small>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="notas">Notas</label><br>
            <textarea id="notas" name="notas" maxlength="250" rows="4" style="width: 100%; max-width: 600px; padding: 8px; border: 1px solid #ccc; border-radius: 5px;" placeholder="Escribe cualquier detalle sobre la migraña"><?php echo e(old('notas')); ?></textarea>
        </div>

        <?php if($errors->any()): ?>
            <ul style="color: #b42318;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>

        <button type="submit">Guardar migraña</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.default", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/registrarMigrana.blade.php ENDPATH**/ ?>