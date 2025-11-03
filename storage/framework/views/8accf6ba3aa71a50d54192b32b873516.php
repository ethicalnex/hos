

<?php $__env->startSection('page-title', 'Lab Results'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Results</h2>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Patient</th>
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
                <td><?php echo e($order->patient->name); ?></td>
                <td><?php echo e($order->doctor->name); ?></td>
                <td><?php echo e($order->tests->count()); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($order->status == 'pending' ? 'warning' : ($order->status == 'in_progress' ? 'info' : 'success')); ?>">
                        <?php echo e(ucfirst($order->status)); ?>

                    </span>
                </td>
                <td>
                    <?php if($order->status != 'completed'): ?>
                        <a href="<?php echo e(route('lab-technician.lab.results.create', $order)); ?>" class="btn btn-sm btn-primary">
                            Enter Results
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($orders->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.lab-technician', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/lab-technician/lab/results/index.blade.php ENDPATH**/ ?>