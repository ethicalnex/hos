

<?php $__env->startSection('title', 'Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">All Appointments</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($appt->patient->name); ?></td>
                <td><?php echo e($appt->doctor->name); ?></td>
                <td><?php echo e($appt->scheduled_time->format('M j, Y g:i A')); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger'))); ?>">
                        <?php echo e(ucfirst($appt->status)); ?>

                    </span>
                </td>
                <td>
                    <a href="<?php echo e(route('hospital.appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-info">
                        View
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($appointments->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/appointments/index.blade.php ENDPATH**/ ?>