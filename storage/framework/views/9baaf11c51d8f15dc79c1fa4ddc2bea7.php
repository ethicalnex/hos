

<?php $__env->startSection('title', 'Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Services</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('hospital.services.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Service
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Department</th>
                <th>Price (₦)</th>
                <th>Duration (min)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($service->name); ?></td>
                <td><?php echo e($service->department->name); ?></td>
                <td><?php echo e(number_format($service->price, 2)); ?></td>
                <td><?php echo e($service->duration); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($service->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($service->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </td>
                <td>
                    <a href="<?php echo e(route('hospital.services.edit', $service)); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="<?php echo e(route('hospital.services.destroy', $service)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php echo e($services->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/services/index.blade.php ENDPATH**/ ?>