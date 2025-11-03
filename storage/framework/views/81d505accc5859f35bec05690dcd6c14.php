

<?php $__env->startSection('title', 'Super Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-chart-line me-2"></i>Super Admin Dashboard
        </h1>
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i><?php echo e(auth()->user()->name); ?>

            </button>
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
        </div>
    </div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Hospitals</h6>
                        <h3 class="mb-0 text-primary"><?php echo e($stats['hospitals']); ?></h3>
                    </div>
                    <div class="bg-primary text-white p-3 rounded-circle">
                        <i class="fas fa-hospital fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ... other stats using $stats['patients'], etc. ... -->
</div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Patients</h6>
                            <h3 class="mb-0 text-success"><?php echo e($stats['patients']); ?></h3>
                        </div>
                        <div class="bg-success text-white p-3 rounded-circle">
                            <i class="fas fa-procedures fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Appointments</h6>
                            <h3 class="mb-0 text-info"><?php echo e($stats['appointments']); ?></h3>
                        </div>
                        <div class="bg-info text-white p-3 rounded-circle">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Revenue (₦)</h6>
                            <h3 class="mb-0 text-warning"><?php echo e(number_format($stats['revenue'], 2)); ?></h3>
                        </div>
                        <div class="bg-warning text-white p-3 rounded-circle">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Hospital Growth</h5>
                </div>
                <div class="card-body">
                    <canvas id="hospitalChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Role Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="roleChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hospitals Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-hospital me-2"></i>Recent Hospitals</h5>
            <a href="<?php echo e(route('super-admin.hospitals.index')); ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i>View All
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hospital Name</th>
                            <th>Email</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentHospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($hospital->name); ?></strong></td>
                            <td><?php echo e($hospital->email); ?></td>
                            <td><code><?php echo e($hospital->slug); ?></code></td>
                            <td>
                                <span class="badge bg-<?php echo e($hospital->is_active ? 'success' : 'secondary'); ?>">
                                    <?php echo e($hospital->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td><?php echo e($hospital->created_at->format('M j, Y')); ?></td>
                            <td>
                                <a href="<?php echo e(url('/' . $hospital->slug . '/admin/dashboard')); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Hospital Growth Chart
const hospitalCtx = document.getElementById('hospitalChart').getContext('2d');
new Chart(hospitalCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chartData['months'], 15, 512) ?>,
        datasets: [{
            label: 'New Hospitals',
            data: <?php echo json_encode($chartData['hospitalCounts'], 15, 512) ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Role Distribution Chart
const roleCtx = document.getElementById('roleChart').getContext('2d');
new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($roleData['labels'], 15, 512) ?>,
        datasets: [{
            data: <?php echo json_encode($roleData['counts'], 15, 512) ?>,
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#6c757d']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/dashboard.blade.php ENDPATH**/ ?>