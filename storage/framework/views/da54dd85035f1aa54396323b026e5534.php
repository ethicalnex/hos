
<?php $__env->startSection('content'); ?>
<table class="table">
    <thead>
        <tr>
            <th>Patient</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($appt->patient->name); ?></td>
            <td><?php echo e($appt->scheduled_time->format('M j, Y g:i A')); ?></td>
            <td>
                <span class="badge bg-<?php echo e($appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger'))); ?>">
                    <?php echo e(ucfirst($appt->status)); ?>

                </span>
            </td>
            <td>
                <?php if($appt->status === 'scheduled'): ?>
                    <form action="<?php echo e(route('doctor.appointments.confirm', $appt)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Confirm</button>
                    </form>
                <?php elseif($appt->status === 'confirmed'): ?>
                    <form action="<?php echo e(route('doctor.appointments.complete', $appt)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-success">Complete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/appointments/index.blade.php ENDPATH**/ ?>