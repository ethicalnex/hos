

<?php $__env->startSection('title', 'Subscription Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-crown me-2"></i>Subscription Plans
        </h1>
        <a href="<?php echo e(route('super-admin.subscription-plans.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Plan
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if($plans->isEmpty()): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No subscription plans found.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price (₦)</th>
                                <th>Trial Days</th>
                                <th>Billing Cycle</th>
                                <th>Features</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($plan->name); ?></strong></td>
                                <td><?php echo e(number_format($plan->price, 2)); ?></td>
                                <td><?php echo e($plan->trial_days); ?></td>
                                <td><?php echo e(ucfirst($plan->billing_cycle)); ?></td>
                                <td>
                                    <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature => $enabled): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($enabled): ?>
                                            <span class="badge bg-success me-1"><?php echo e($plan->getFeatureName($feature)); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($plan->is_active ? 'success' : 'secondary'); ?>">
                                        <?php echo e($plan->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('super-admin.subscription-plans.edit', $plan)); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('super-admin.subscription-plans.destroy', $plan)); ?>" 
                                          method="POST" class="d-inline">
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
                <?php echo e($plans->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/subscription-plans/index.blade.php ENDPATH**/ ?>