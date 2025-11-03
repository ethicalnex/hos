

<?php $__env->startSection('title', 'Lab Tests'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lab Tests</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('hospital.lab.tests.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Test
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price (₦)</th>
                <th>Unit</th>
                <th>Normal Range</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($test->name); ?></td>
                <td><?php echo e($test->category->name); ?></td>
                <td><?php echo e(number_format($test->price, 2)); ?></td>
                <td><?php echo e($test->unit ?? '—'); ?></td>
                <td><?php echo e($test->normal_range ?? '—'); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($test->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($test->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </td>
                <td>
                    <a href="<?php echo e(route('hospital.lab.tests.edit', $test)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($tests->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/lab/tests/index.blade.php ENDPATH**/ ?>