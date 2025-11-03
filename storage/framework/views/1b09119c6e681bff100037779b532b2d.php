<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Super Admin'); ?> - EthicalNex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .sidebar { height: calc(100vh - 56px); position: sticky; top: 56px; }
        .main-content { min-height: calc(100vh - 56px); }
        .card { border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header { border-radius: 12px 12px 0 0 !important; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo e(route('super-admin.dashboard')); ?>">
            <i class="fas fa-crown me-2"></i>Super Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?php echo e(auth()->user()->name); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('super-admin.dashboard') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('super-admin.dashboard')); ?>">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('super-admin.hospitals.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('super-admin.hospitals.index')); ?>">
                            <i class="fas fa-hospital me-2"></i>Hospitals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('super-admin.users.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('super-admin.users.index')); ?>">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('super-admin.payments.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('super-admin.payments.index')); ?>">
                            <i class="fas fa-coins me-2"></i>Payments
                        </a>
                    </li>
                    <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('super-admin.super-admin-payment.*') ? 'active' : ''); ?>" 
       href="<?php echo e(route('super-admin.super-admin-payment.index')); ?>">
        <i class="fas fa-crown me-2"></i>Super Admin Payment
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('super-admin.subscription-payments.*') ? 'active' : ''); ?>" 
       href="<?php echo e(route('super-admin.subscription-payments.index')); ?>">
        <i class="fas fa-coins me-2"></i>Subscription Payments
    </a>
</li>
                    <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('super-admin.subscription-plans.*') ? 'active' : ''); ?>" 
       href="<?php echo e(route('super-admin.subscription-plans.index')); ?>">
        <i class="fas fa-crown me-2"></i>Subscription Plans
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('super-admin.payment-settings.*') ? 'active' : ''); ?>" 
       href="<?php echo e(route('super-admin.payment-settings.index')); ?>">
        <i class="fas fa-credit-card me-2"></i>Payment Settings
    </a>
</li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('super-admin.analytics.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('super-admin.analytics.index')); ?>">
                            <i class="fas fa-chart-line me-2"></i>Analytics
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/layouts/app.blade.php ENDPATH**/ ?>