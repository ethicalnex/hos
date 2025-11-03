

<?php $__env->startSection('page-title', 'EMR Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>EMR Records</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('doctor.emr.create')); ?>" class="btn btn-primary mb-3">
                    <i class="fas fa-plus me-2"></i> New EMR Record
                </a>

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
                                    <th>Diagnosis</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($record->patient->name); ?></td>
                                    <td><?php echo e(Str::limit($record->diagnosis, 30)); ?></td>
                                    <td><?php echo e($record->created_at->format('M j, Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('doctor.emr.show', $record)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('doctor.emr.edit', $record)); ?>" class="btn btn-sm btn-outline-secondary">
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
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/emr/index.blade.php ENDPATH**/ ?>