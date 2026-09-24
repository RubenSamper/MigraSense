@props([
    'type' => 'info',
    'message' => null,
])

@if ($message)
    @php
        $styles = [
            'success' => ['label' => 'Éxito', 'class' => 'notification-success', 'role' => 'status'],
            'error' => ['label' => 'Error', 'class' => 'notification-error', 'role' => 'alert'],
            'warning' => ['label' => 'Aviso', 'class' => 'notification-warning', 'role' => 'alert'],
            'info' => ['label' => 'Información', 'class' => 'notification-info', 'role' => 'status'],
        ];
        $style = $styles[$type] ?? $styles['info'];
    @endphp

    <div class="notification-item">
        <div class="notification {{ $style['class'] }}" role="{{ $style['role'] }}" aria-live="polite">
            <div class="notification-mark" aria-hidden="true">{{ $type === 'success' ? '✓' : ($type === 'error' ? '!' : 'i') }}</div>
            <div class="notification-content">
                <strong>{{ $style['label'] }}</strong>
                <span>{{ $message }}</span>
            </div>
        </div>
    </div>
@endif

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
