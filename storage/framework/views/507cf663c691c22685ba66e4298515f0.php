

<?php $__env->startSection('title', 'Super Admin Payment Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-crown me-2"></i>Super Admin Payment Settings
        </h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fab fa-paystack me-2"></i>Paystack</h5>
                </div>
                <div class="card-body">
                    <?php
                        $paystack = $settings->where('payment_gateway', 'paystack')->first();
                    ?>
                    <?php if($paystack): ?>
                        <span class="badge bg-success">Configured</span>
                        <p class="mt-2"><strong>Public Key:</strong> <?php echo e(substr($paystack->public_key, 0, 8)); ?>...</p>
                    <?php else: ?>
                        <span class="badge bg-secondary">Not configured</span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('super-admin.super-admin-payment.edit', 'paystack')); ?>" 
                       class="btn btn-primary mt-3">
                        <i class="fas fa-edit me-1"></i> Configure
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fab fa-cc-visa me-2"></i>Flutterwave</h5>
                </div>
                <div class="card-body">
                    <?php
                        $flutterwave = $settings->where('payment_gateway', 'flutterwave')->first();
                    ?>
                    <?php if($flutterwave): ?>
                        <span class="badge bg-success">Configured</span>
                        <p class="mt-2"><strong>Public Key:</strong> <?php echo e(substr($flutterwave->public_key, 0, 8)); ?>...</p>
                    <?php else: ?>
                        <span class="badge bg-secondary">Not configured</span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('super-admin.super-admin-payment.edit', 'flutterwave')); ?>" 
                       class="btn btn-warning mt-3 text-white">
                        <i class="fas fa-edit me-1"></i> Configure
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/super-admin-payment/index.blade.php ENDPATH**/ ?>