

<?php $__env->startSection('page-title', 'My Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Appointments</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('patient.appointments.create')); ?>" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-1"></i> Book New Appointment
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if($appointments->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        You don't have any appointments yet. 
        <a href="<?php echo e(route('patient.appointments.create')); ?>" class="alert-link">Book your first appointment</a>.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($appointment->doctor->name); ?></td>
                    <td><?php echo e($appointment->scheduled_time->format('M j, Y g:i A')); ?></td>
                    <td><?php echo e($appointment->reason ?? '—'); ?></td>
                    <td>
                        <?php
                            $statusClass = match($appointment->status) {
                                'scheduled' => 'warning',
                                'confirmed' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'no_show' => 'secondary',
                                default => 'secondary'
                            };
                        ?>
                        <span class="badge bg-<?php echo e($statusClass); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $appointment->status))); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($appointment->status === 'scheduled'): ?>
                            <form action="<?php echo e(route('patient.appointments.cancel', $appointment)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                            </form>
                        <?php elseif($appointment->status === 'completed'): ?>
                            <a href="<?php echo e(route('patient.emr.index')); ?>" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-file-medical me-1"></i> View EMR
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <?php echo e($appointments->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/patient/appointments/index.blade.php ENDPATH**/ ?>