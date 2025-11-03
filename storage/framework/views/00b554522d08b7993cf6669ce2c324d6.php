

<?php $__env->startSection('title', 'Pharmacy Items'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pharmacy Items</h1>
    <a href="<?php echo e(route('hospital.pharmacy.items.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Item
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Form</th>
                <th>Strength</th>
                <th>Price (₦)</th>
                <th>Available Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->name); ?></td>
                <td><?php echo e($item->category); ?></td>
                <td><?php echo e($item->form); ?></td>
                <td><?php echo e($item->strength); ?></td>
                <td><?php echo e(number_format($item->price, 2)); ?></td>
                <td><?php echo e($item->available_quantity); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($item->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($item->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </td>
                <td>
                    <a href="<?php echo e(route('hospital.pharmacy.items.edit', $item)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?php echo e(route('hospital.pharmacy.inventory.add', $item)); ?>" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-box"></i> Add Stock
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($items->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/pharmacy/items/index.blade.php ENDPATH**/ ?>