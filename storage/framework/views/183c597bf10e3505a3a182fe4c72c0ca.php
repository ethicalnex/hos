

<?php $__env->startSection('title', 'Receptionist Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Appointment Management</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Service</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if($appt->patient): ?>
                        <?php echo e($appt->patient->name); ?>

                    <?php else: ?>
                        <span class="text-muted">Not registered</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($appt->doctor->name); ?></td>
                <td><?php echo e($appt->service->name ?? '—'); ?></td>
                <td><?php echo e($appt->scheduled_time->format('M j, Y g:i A')); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger'))); ?>">
                        <?php echo e(ucfirst($appt->status)); ?>

                    </span>
                </td>
                <td>
                    <?php if($appt->status === 'scheduled'): ?>
                        <form action="<?php echo e(route('hospital.receptionist.appointments.confirm', $appt)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Confirm</button>
                        </form>
                    <?php endif; ?>
                    <a href="<?php echo e(route('hospital.appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-info">View</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($appointments->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/receptionist/appointments/index.blade.php ENDPATH**/ ?>