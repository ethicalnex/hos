

<?php $__env->startSection('page-title', 'Pharmacy Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Pending Prescriptions</h5>
                        <h2><?php echo e($pendingPrescriptions); ?></h2>
                    </div>
                    <i class="fas fa-prescription fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Today's Sales</h5>
                        <h2>₦<?php echo e(number_format($todaySales, 2)); ?></h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Low Stock Items</h5>
                        <h2><?php echo e($lowStockItems->count()); ?></h2>
                    </div>
                    <i class="fas fa-box-open fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Items</h5>
                        <h2><?php echo e($totalItems); ?></h2>
                    </div>
                    <i class="fas fa-capsules fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Low Stock Items</h5>
            </div>
            <div class="card-body">
                <?php if($lowStockItems->isEmpty()): ?>
                    <div class="alert alert-info">No low stock items.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Available Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->name); ?></td>
                                    <td><?php echo e($item->category); ?></td>
                                    <td><?php echo e($item->available_quantity); ?></td>
                                    <td><?php echo e($item->inventory?->expiry_date ?? '—'); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('hospital.pharmacy.inventory.add', $item)); ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-plus me-1"></i> Add Stock
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('pharmacy.dispense.index')); ?>" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-box-open me-2"></i>Dispense Prescriptions
                </a>
                <a href="<?php echo e(route('pharmacy.sales.create')); ?>" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-money-bill-wave me-2"></i>Record Sale
                </a>
                <a href="<?php echo e(route('pharmacy.reports.generate')); ?>" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-file-pdf me-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pharmacy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/pharmacy/dashboard.blade.php ENDPATH**/ ?>