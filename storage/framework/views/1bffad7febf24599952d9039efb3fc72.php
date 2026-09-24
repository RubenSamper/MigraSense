<header class="header">
    <style>
        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            box-sizing: border-box;
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 8px;
            background: rgba(0, 52, 76, 0.28);
            box-shadow: 0 2px 5px rgba(0, 35, 52, 0.18);
            color: white;
            text-decoration: none;
            font-size: 0.9em;
            padding: 8px 16px;
            font-weight: 700;
            transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 9px rgba(0, 35, 52, 0.25);
            transform: translateY(-1px);
        }

        .nav-link:focus-visible {
            outline: 3px solid #ffd166;
            outline-offset: 3px;
        }

        .nav-link-border {
            background: transparent;
        }

        .nav-link-border:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
        }

        .nav-link-primary {
            background: white;
            color: #006994;
            font-weight: bold;
        }

        .nav-link-primary:hover {
            background: #e6f0f5;
            color: #004b6b;
        }

        .nav-link-active {
            background: #fff;
            border-color: #fff;
            color: #005578;
            box-shadow: 0 4px 10px rgba(0, 35, 52, 0.25);
        }
    </style>
    <nav class="site-nav" aria-label="Navegación principal">
        <a class="site-brand" href="<?php echo e(route('landingPage')); ?>">AquaSense</a>
        <div class="site-nav-links">
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('usuarios.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('usuarios.dashboard') ? 'nav-link-active' : ''); ?>" <?php if(request()->routeIs('usuarios.dashboard')): ?> aria-current="page" <?php endif; ?>>Dashboard</a>
            <?php if(in_array(Auth::user()->rol_id, [1, 3])): ?>
            <a href="<?php echo e(route('usuarios.obtenerListadoUsuarios')); ?>" class="nav-link <?php echo e(request()->routeIs('usuarios.obtenerListadoUsuarios') ? 'nav-link-active' : ''); ?>" <?php if(request()->routeIs('usuarios.obtenerListadoUsuarios')): ?> aria-current="page" <?php endif; ?>>Usuarios</a>
            <?php endif; ?>
            <a href="<?php echo e(route('usuarios.seguimientoMigrana')); ?>" class="nav-link <?php echo e(request()->routeIs('usuarios.seguimientoMigrana') ? 'nav-link-active' : ''); ?>" <?php if(request()->routeIs('usuarios.seguimientoMigrana')): ?> aria-current="page" <?php endif; ?>>Seguimiento</a>
            <span class="site-user"><?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellidos); ?></span>
            <a href="<?php echo e(route('usuarios.logout')); ?>" class="nav-link nav-link-border">Cerrar sesión</a>
            <?php else: ?>
            <a href="<?php echo e(route('usuarios.createUsuario')); ?>" class="nav-link nav-link-border">Crear usuario</a>
            <a href="<?php echo e(route('usuarios.login')); ?>" class="nav-link nav-link-primary">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </nav>

    <style>
        .site-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 68px;
            padding: 12px clamp(16px, 4vw, 30px);
            box-sizing: border-box;
            background: #006994;
            color: white;
        }

        .site-brand {
            flex: 0 0 auto;
            color: white;
            text-decoration: none;
            font-size: 1.4em;
            font-weight: bold;
        }

        .site-nav-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .site-user {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .9em;
        }

        @media (max-width: 760px) {
            .site-nav {
                align-items: stretch;
                flex-direction: column;
                gap: 10px;
            }

            .site-brand { text-align: center; }
            .site-nav-links { justify-content: center; }
            .site-user { max-width: 100%; }
        }

        @media (max-width: 430px) {
            .site-nav-links { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); width: 100%; }
            .site-nav-links .nav-link { text-align: center; padding: 9px 8px; }
            .site-user { grid-column: 1 / -1; text-align: center; }
        }
    </style>
</header><?php /**PATH /var/www/html/resources/views/components/layouts/menu.blade.php ENDPATH**/ ?>