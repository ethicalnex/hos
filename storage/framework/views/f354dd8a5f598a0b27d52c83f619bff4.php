

<?php $__env->startSection('title', 'Hospitals Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hospitals Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('super-admin.hospitals.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add New Hospital
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Email</th>
                        <th>Staff Count</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $hospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($hospital->name); ?></strong>
                            <?php if($hospital->city): ?>
                                <br><small class="text-muted"><?php echo e($hospital->city); ?>, <?php echo e($hospital->state); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><code><?php echo e($hospital->slug); ?></code></td>
                        <td><?php echo e($hospital->email); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($hospital->users_count); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($hospital->is_active ? 'success' : 'danger'); ?>">
                                <?php echo e($hospital->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td><?php echo e($hospital->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('super-admin.hospitals.edit', $hospital)); ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('super-admin.hospitals.toggle', $hospital)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-<?php echo e($hospital->is_active ? 'danger' : 'success'); ?>">
                                    <i class="fas fa-<?php echo e($hospital->is_active ? 'times' : 'check'); ?>"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">No hospitals found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            <?php echo e($hospitals->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/hospitals/index.blade.php ENDPATH**/ ?>