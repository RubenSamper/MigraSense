<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos.css?2')); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


        <!-- Datatables -->
        <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet"/> 
        <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { overflow-x: hidden; }
        body { margin: 0; min-width: 320px; overflow-x: hidden; }
        img, canvas, iframe { max-width: 100%; }
        button, input, select, textarea { max-width: 100%; font: inherit; }
        main, section, article, form { min-width: 0; }

        :focus-visible {
            outline: 3px solid #f5b942;
            outline-offset: 3px;
        }

        .skip-link {
            position: fixed;
            top: 8px;
            left: 8px;
            z-index: 2000;
            padding: 10px 14px;
            border-radius: 6px;
            background: #173042;
            color: #fff;
            transform: translateY(-150%);
        }

        .skip-link:focus { transform: translateY(0); }

        .page-content:focus { outline: none; }

        .page-shell {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        @media (max-width: 600px) {
            .notification-stack { right: 12px; left: 12px; }
            .notification-stack .notification-item { width: 100%; }
        }
    </style>
    <?php echo $__env->yieldContent('scripts'); ?>
</head>
<body>
    <a class="skip-link" href="#main-content">Saltar al contenido principal</a>
    <?php if (isset($component)) { $__componentOriginala551e800fe7ed2429c7be2f1c26019e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala551e800fe7ed2429c7be2f1c26019e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala551e800fe7ed2429c7be2f1c26019e4)): ?>
<?php $attributes = $__attributesOriginala551e800fe7ed2429c7be2f1c26019e4; ?>
<?php unset($__attributesOriginala551e800fe7ed2429c7be2f1c26019e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala551e800fe7ed2429c7be2f1c26019e4)): ?>
<?php $component = $__componentOriginala551e800fe7ed2429c7be2f1c26019e4; ?>
<?php unset($__componentOriginala551e800fe7ed2429c7be2f1c26019e4); ?>
<?php endif; ?>
    <div class="notification-stack" aria-label="Notificaciones">
        <?php $__currentLoopData = ['success', 'error', 'warning', 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification','data' => ['type' => $tipo,'message' => session($tipo)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tipo),'message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session($tipo))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $attributes = $__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__attributesOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839)): ?>
<?php $component = $__componentOriginal0d8d3c14ebd2b92d484be47e6c018839; ?>
<?php unset($__componentOriginal0d8d3c14ebd2b92d484be47e6c018839); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <style>
        .notification-stack {
            position: fixed;
            top: 82px;
            right: 24px;
            z-index: 1000;
            display: grid;
            gap: 12px;
            pointer-events: none;
        }

        .notification-stack .notification-item { pointer-events: auto; }

        @media (max-width: 600px) {
            .notification-stack {
                top: 72px;
                right: 16px;
            }
        }
    </style>

    <div id="main-content" class="page-content" tabindex="-1">
        <?php echo $__env->yieldContent('contenido'); ?>
    </div>

    <footer aria-label="Pie de página" style="background: #004b6b; color: white; padding: 30px 20px 15px; margin-top: 40px;">
        <div style="max-width: 1000px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">AquaSense</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">Monitoreo inteligente de humedad</p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Enlaces</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">
                    <a href="<?php echo e(route('landingPage')); ?>" style="color: white; text-decoration: none;">Inicio</a> ·
                    <a href="<?php echo e(route('usuarios.createUsuario')); ?>" style="color: white; text-decoration: none;">Registro</a>
                </p>
            </div>
            <div>
                <h4 style="margin: 0 0 8px; font-size: 1.1em;">Contacto</h4>
                <p style="margin: 0; font-size: 0.85em; opacity: 0.8;">info@aquasense.com</p>
            </div>
        </div>
        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 20px 0 10px;">
        <p style="text-align: center; margin: 0; font-size: 0.8em; opacity: 0.6;">Copyright &copy; 2026 AquaSense. Todos los derechos reservados.</p>
    </footer>
</body>
</html><?php /**PATH /var/www/html/resources/views/layouts/default.blade.php ENDPATH**/ ?>