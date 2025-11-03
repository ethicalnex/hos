

<?php $__env->startSection('page-title', 'Today\'s Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Today's Appointments</h2>
</div>

<?php if($appointments->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No appointments scheduled for today.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($appt->patient->name); ?></td>
                    <td><?php echo e($appt->doctor->name); ?></td>
                    <td><?php echo e($appt->scheduled_time->format('g:i A')); ?></td>
                    <td><?php echo e($appt->service?->name ?? '—'); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : 'success')); ?>">
                            <?php echo e(ucfirst($appt->status)); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('lab-technician.appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($appointments->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.lab-technician', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/lab-technician/appointments/index.blade.php ENDPATH**/ ?>