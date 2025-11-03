

<?php $__env->startSection('page-title', 'Pharmacy Sales'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Pharmacy Sales</h2>
    <a href="<?php echo e(route('pharmacy.sales.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Record Sale
    </a>
</div>

<?php if($sales->isEmpty()): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No sales recorded yet.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sale #</th>
                    <th>Patient</th>
                    <th>Pharmacist</th>
                    <th>Amount (₦)</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($sale->id); ?></td>
                    <td><?php echo e($sale->patient->name); ?></td>
                    <td><?php echo e($sale->pharmacist->name); ?></td>
                    <td><?php echo e(number_format($sale->total_amount, 2)); ?></td>
                    <td><?php echo e($sale->created_at->format('M j, Y')); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($sale->status == 'completed' ? 'success' : 'danger'); ?>">
                            <?php echo e(ucfirst($sale->status)); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('pharmacy.sales.show', $sale)); ?>" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php echo e($sales->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pharmacy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/pharmacy/sales/index.blade.php ENDPATH**/ ?>