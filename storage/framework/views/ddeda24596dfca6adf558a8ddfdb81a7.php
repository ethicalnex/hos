

<?php $__env->startSection('page-title', 'Lab Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Orders</h2>
    <a href="<?php echo e(route('doctor.lab.orders.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Lab Order
    </a>
</div>

<?php if($orders->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No lab orders created yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Patient</th>
                    <th>Tests</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($order->id); ?></td>
                    <td><?php echo e($order->patient->name); ?></td>
                    <td><?php echo e($order->tests->count()); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($order->status == 'pending' ? 'warning' : ($order->status == 'in_progress' ? 'info' : 'success')); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($order->created_at->format('M j, Y')); ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($orders->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/lab/orders/index.blade.php ENDPATH**/ ?>