

<?php $__env->startSection('title', 'Staff Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Staff Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('hospital.staff.create')); ?>" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Add Staff Member
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
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($user->name); ?></strong>
                            <?php if($user->specialization): ?>
                                <br><small class="text-muted"><?php echo e($user->specialization); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <span class="badge bg-info text-capitalize">
                                <?php echo e(str_replace('_', ' ', $user->role)); ?>

                            </span>
                        </td>
                        <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($user->is_active ? 'success' : 'danger'); ?>">
                                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('hospital.staff.edit', $user)); ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('hospital.staff.toggle', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-<?php echo e($user->is_active ? 'danger' : 'success'); ?>">
                                    <i class="fas fa-<?php echo e($user->is_active ? 'times' : 'check'); ?>"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No staff members found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            <?php echo e($staff->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/staff/index.blade.php ENDPATH**/ ?>