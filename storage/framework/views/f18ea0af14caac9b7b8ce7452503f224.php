

<?php $__env->startSection('page-title', 'Prescriptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Prescriptions</h2>
    <a href="<?php echo e(route('doctor.prescriptions.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Prescription
    </a>
</div>

<?php if($prescriptions->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No prescriptions created yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($prescription->patient->name); ?></td>
                    <td><?php echo e($prescription->created_at->format('M j, Y')); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($prescription->status == 'pending' ? 'warning' : ($prescription->status == 'dispensed' ? 'success' : 'danger')); ?>">
                            <?php echo e(ucfirst($prescription->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($prescription->items->count()); ?></td>
                    <td>
                        <a href="<?php echo e(route('doctor.prescriptions.show', $prescription)); ?>" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($prescriptions->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/prescriptions/index.blade.php ENDPATH**/ ?>