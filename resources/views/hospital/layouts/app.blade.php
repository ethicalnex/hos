<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hospital Dashboard')</title>

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

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary), #006a4d);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            padding: 0;
        }

        .hospital-brand {
            padding: 1.25rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .hospital-brand h5 {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.85rem 1.25rem;
            margin: 0.15rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main content area */
        main {
            margin-left: 16.666667%; /* col-lg-2 = ~16.66% */
            padding-top: 20px;
        }

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

        /* Top navbar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 0.6rem 1.25rem;
        }

        .top-navbar .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Stat cards */
        .stat-card {
            border-left: 4px solid var(--primary);
            transition: transform 0.25s, box-shadow 0.25s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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

        /* Form controls */
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

        /* Tables */
        .table th {
            font-weight: 600;
            background-color: #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Modal */
        .modal-content {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Feature Restriction Modal -->
@if(session('upgrade_required'))
<div class="modal fade" id="upgradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feature Upgrade Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ session('upgrade_required') }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('hospital.subscription.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-1"></i> Upgrade Plan
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('trial_expired'))
<div class="modal fade" id="trialExpiredModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Free Trial Expired</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ session('trial_expired') }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('hospital.subscription.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-1"></i> Upgrade Now
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('upgrade_required'))
        var upgradeModal = new bootstrap.Modal(document.getElementById('upgradeModal'));
        upgradeModal.show();
    @endif
    
    @if(session('trial_expired'))
        var trialExpiredModal = new bootstrap.Modal(document.getElementById('trialExpiredModal'));
        trialExpiredModal.show();
    @endif
});
</script>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
<nav class="col-lg-2 col-md-3 d-md-block bg-light sidebar collapse" style="height: calc(100vh - 56px);">
            <div class="hospital-brand">
                <h5>
                    <i class="fas fa-hospital me-2"></i>
                    {{ auth()->user()->hospital->name }}
                </h5>
                <small class="text-light opacity-75">Hospital Admin Panel</small>
            </div>

            <div class="position-sticky pt-3">
                <ul class="nav flex-column px-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}" 
                           href="{{ route('hospital.dashboard') }}">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.analytics.*') ? 'active' : '' }}" href="{{ route('hospital.analytics.index') }}">
                            <i class="fas fa-chart-line me-2"></i>Analytics & Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.staff.*') ? 'active' : '' }}" href="{{ route('hospital.staff.index') }}">
                            <i class="fas fa-users me-2"></i>Staff Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.doctor-schedules.*') ? 'active' : '' }}" href="{{ route('hospital.doctor-schedules.index') }}">
                            <i class="fas fa-clock me-2"></i>Doctor Schedules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.patients.*') ? 'active' : '' }}" href="{{ route('hospital.patients.index') }}">
                            <i class="fas fa-procedures me-2"></i>Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.departments.*') ? 'active' : '' }}" href="{{ route('hospital.departments.index') }}">
                            <i class="fas fa-building me-2"></i>Departments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.services.*') ? 'active' : '' }}" href="{{ route('hospital.services.index') }}">
                            <i class="fas fa-concierge-bell me-2"></i>Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.appointments.*') ? 'active' : '' }}" href="{{ route('hospital.appointments.index') }}">
                            <i class="fas fa-calendar-check me-2"></i>Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.lab.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('lab') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('lab') ? route('hospital.lab.categories.index') : '#' }}">
                            <i class="fas fa-flask me-2"></i>Lab Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.pharmacy.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('pharmacy') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('pharmacy') ? route('hospital.pharmacy.items.index') : '#' }}">
                            <i class="fas fa-pills me-2"></i>Pharmacy Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.emr.*') ? 'active' : '' }} 
                           {{ !auth()->user()->hospital->hasFeature('emr') ? 'disabled' : '' }}"
                           href="{{ auth()->user()->hospital->hasFeature('emr') ? route('hospital.emr.index') : '#' }}">
                            <i class="fas fa-file-medical me-2"></i>EMR
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.receptionist.*') ? 'active' : '' }}" href="{{ route('hospital.receptionist.appointments.index') }}">
                            <i class="fas fa-user-md me-2"></i>Receptionist View
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.subscription.*') ? 'active' : '' }}" href="{{ route('hospital.subscription.index') }}">
                            <i class="fas fa-crown me-2"></i>Subscription
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hospital.settings.*') ? 'active' : '' }}" href="{{ route('hospital.settings.index') }}">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-warning" href="{{ route('super-admin.dashboard') }}">
                            <i class="fas fa-arrow-left me-1"></i> Super Admin
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Navbar -->
<nav class="navbar top-navbar navbar-expand">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-md me-2"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i> My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>