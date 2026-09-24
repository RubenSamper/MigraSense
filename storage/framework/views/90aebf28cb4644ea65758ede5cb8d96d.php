<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'info',
    'message' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => 'info',
    'message' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($message): ?>
    <?php
        $styles = [
            'success' => ['label' => 'Éxito', 'class' => 'notification-success', 'role' => 'status'],
            'error' => ['label' => 'Error', 'class' => 'notification-error', 'role' => 'alert'],
            'warning' => ['label' => 'Aviso', 'class' => 'notification-warning', 'role' => 'alert'],
            'info' => ['label' => 'Información', 'class' => 'notification-info', 'role' => 'status'],
        ];
        $style = $styles[$type] ?? $styles['info'];
    ?>

    <div class="notification-item">
        <div class="notification <?php echo e($style['class']); ?>" role="<?php echo e($style['role']); ?>" aria-live="polite">
            <div class="notification-mark" aria-hidden="true"><?php echo e($type === 'success' ? '✓' : ($type === 'error' ? '!' : 'i')); ?></div>
            <div class="notification-content">
                <strong><?php echo e($style['label']); ?></strong>
                <span><?php echo e($message); ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .notification-item {
        max-height: 120px;
        overflow: hidden;
        width: min(440px, calc(100vw - 48px));
        animation: notification-life 4s ease forwards;
    }

    .notification {
        display: flex;
        gap: 13px;
        align-items: flex-start;
        min-height: 72px;
        box-sizing: border-box;
        padding: 16px 14px 16px 16px;
        border: 1px solid;
        border-left-width: 6px;
        border-radius: 12px;
        color: #173042;
        background: #fff;
        box-shadow: 0 14px 32px rgba(23, 48, 66, 0.2);
    }

    .notification-success { border-color: #187a36; background: #f2fbf5; }
    .notification-error { border-color: #b42318; background: #fff5f4; }
    .notification-warning { border-color: #b86b00; background: #fff9ed; }
    .notification-info { border-color: #1769aa; background: #f1f8fd; }

    .notification-mark {
        display: grid;
        flex: 0 0 28px;
        place-items: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        color: #fff;
        font-weight: 800;
    }

    .notification-success .notification-mark { background: #187a36; }
    .notification-error .notification-mark { background: #b42318; }
    .notification-warning .notification-mark { background: #b86b00; }
    .notification-info .notification-mark { background: #1769aa; }

    .notification-content {
        display: grid;
        gap: 4px;
        min-width: 0;
        flex: 1;
    }

    .notification-content strong { font-size: 0.95rem; }
    .notification-content span { line-height: 1.4; }

    @keyframes notification-life {
        0%, 82% {
            max-height: 120px;
            opacity: 1;
            transform: translateX(0);
        }
        100% {
            max-height: 0;
            opacity: 0;
            transform: translateX(18px);
            pointer-events: none;
        }
    }

    @media (max-width: 600px) {
        .notification-item { width: calc(100vw - 32px); }
    }
</style>
<?php /**PATH /var/www/html/resources/views/components/notification.blade.php ENDPATH**/ ?>