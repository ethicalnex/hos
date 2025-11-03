

<?php $__env->startSection('page-title', 'Lab Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Reports</h2>
</div>

<?php if($orders->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No lab reports available yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Report #</th>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Tests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($order->id); ?></td>
                    <td><?php echo e($order->created_at->format('M j, Y')); ?></td>
                    <td><?php echo e($order->doctor->name); ?></td>
                    <td><?php echo e($order->tests->count()); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($order->status == 'completed' ? 'success' : 'warning'); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($order->status == 'completed'): ?>
                            <a href="<?php echo e(route('patient.lab.reports.show', $order)); ?>" class="btn btn-sm btn-primary">
                                View Report
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($orders->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/patient/lab/reports/index.blade.php ENDPATH**/ ?>