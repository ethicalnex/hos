<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Lab Technician Dashboard'); ?></title>

    <!-- Google Fonts: Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #007e5d;
            --secondary: #f8c828;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #6b7280;
            --white: #ffffff;
            --border: #e5e7eb;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light);
            color: var(--dark);
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary), #006a4d) !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .nav-link {
            color: white !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: var(--secondary) !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            font-weight: 500;
        }
        .dropdown-item:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Sidebar */
        .sidebar {
            height: calc(100vh - 56px);
            position: sticky;
            top: 56px;
            background: linear-gradient(180deg, var(--primary), #006a4d);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0;
        }
        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.85rem 1.25rem;
            margin: 0.15rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.25s ease;
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(4px);
        }
        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 56px);
            padding-top: 20px;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary), #006a4d);
            color: white;
            border-bottom: none;
            font-weight: 600;
        }
        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #006a4d;
            border-color: #006a4d;
        }
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: #000;
            font-weight: 600;
        }
        .btn-secondary:hover {
            background-color: #e6b420;
            border-color: #e6b420;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        /* Tables */
        .table th {
            font-weight: 600;
            background-color: #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Form Controls */
        .form-control {
            font-family: 'Montserrat', sans-serif;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 126, 93, 0.25);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }
            main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo e(route('lab-technician.dashboard')); ?>">
            <i class="fas fa-flask me-2"></i><?php echo e(auth()->user()->hospital->name); ?> Lab
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-Nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?php echo e(auth()->user()->name); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
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
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: calc(100vh - 56px);">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('lab-technician.dashboard') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('lab-technician.dashboard')); ?>">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('lab-technician.lab.results.*') ? 'active' : ''); ?> 
                           <?php echo e(!auth()->user()->hospital->hasFeature('lab') ? 'disabled' : ''); ?>"
                           href="<?php echo e(auth()->user()->hospital->hasFeature('lab') ? route('lab-technician.lab.results.index') : '#'); ?>">
                            <i class="fas fa-flask me-2"></i>Lab Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('lab-technician.appointments.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('lab-technician.appointments.index')); ?>">
                            <i class="fas fa-calendar-check me-2"></i>Appointments
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            </div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/layouts/lab-technician.blade.php ENDPATH**/ ?>