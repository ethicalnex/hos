
<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-credit-card me-2"></i>Payment Settings
        </h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hospital</th>
                            <th>Paystack</th>
                            <th>Flutterwave</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $hospitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hospital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($hospital->name); ?></td>
                            <td>
                                <?php if($hospital->paymentSettings->where('payment_gateway', 'paystack')->first()): ?>
                                    <span class="badge bg-success">Configured</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($hospital->paymentSettings->where('payment_gateway', 'flutterwave')->first()): ?>
                                    <span class="badge bg-success">Configured</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('super-admin.payment-settings.edit', $hospital)); ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/payment-settings/index.blade.php ENDPATH**/ ?>