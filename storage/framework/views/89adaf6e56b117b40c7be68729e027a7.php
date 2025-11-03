

<?php $__env->startSection('title', 'EMR System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">EMR System</h1>
        <a href="<?php echo e(route('hospital.emr.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Record
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if($records->isEmpty()): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No EMR records found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($record->patient->name); ?></td>
                                <td><?php echo e($record->doctor->name); ?></td>
                                <td><?php echo e($record->diagnosis ?? '—'); ?></td>
                                <td><?php echo e($record->created_at->format('M j, Y')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('hospital.emr.show', $record)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('hospital.emr.edit', $record)); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($records->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/emr/index.blade.php ENDPATH**/ ?>