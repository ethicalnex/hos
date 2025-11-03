

<?php $__env->startSection('page-title', 'Sale #<?php echo e($sale->id); ?>'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Sale #<?php echo e($sale->id); ?></h5>
                <p class="mb-0">
                    <strong>Patient:</strong> <?php echo e($sale->patient->name); ?><br>
                    <strong>Pharmacist:</strong> <?php echo e($sale->pharmacist->name); ?><br>
                    <strong>Date:</strong> <?php echo e($sale->created_at->format('M j, Y')); ?>

                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price (₦)</th>
                                <th>Total (₦)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->item->name); ?></td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td><?php echo e(number_format($item->price, 2)); ?></td>
                                <td><?php echo e(number_format($item->total, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <strong>Total Amount:</strong>
                    <strong>₦<?php echo e(number_format($sale->total_amount, 2)); ?></strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Sale Details</h6>
            </div>
            <div class="card-body">
                <p><strong>Payment Method:</strong> <?php echo e($sale->payment_method ?? '—'); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?php echo e($sale->status == 'completed' ? 'success' : 'danger'); ?>">
                        <?php echo e(ucfirst($sale->status)); ?>

                    </span>
                </p>
                <p><strong>Notes:</strong> <?php echo e($sale->notes ?? '—'); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pharmacy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/pharmacy/sales/show.blade.php ENDPATH**/ ?>