
<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-hospital me-2"></i><?php echo e($hospital->name); ?> Payment Settings
        </h1>
        <a href="<?php echo e(route('super-admin.payment-settings.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fab fa-paystack me-2"></i>Paystack</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('super-admin.payment-settings.update', $hospital)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="payment_gateway" value="paystack">
                        <div class="mb-3">
                            <label class="form-label">Public Key</label>
                            <input type="text" name="public_key" class="form-control" 
                                   value="<?php echo e($hospital->paymentSettings->where('payment_gateway', 'paystack')->first()?->public_key); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secret Key</label>
                            <input type="password" name="secret_key" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Webhook Secret</label>
                            <input type="text" name="webhook_secret" class="form-control"
                                   value="<?php echo e($hospital->paymentSettings->where('payment_gateway', 'paystack')->first()?->webhook_secret); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Save Paystack Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fab fa-cc-visa me-2"></i>Flutterwave</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('super-admin.payment-settings.update', $hospital)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="payment_gateway" value="flutterwave">
                        <div class="mb-3">
                            <label class="form-label">Public Key</label>
                            <input type="text" name="public_key" class="form-control"
                                   value="<?php echo e($hospital->paymentSettings->where('payment_gateway', 'flutterwave')->first()?->public_key); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secret Key</label>
                            <input type="password" name="secret_key" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Webhook Secret</label>
                            <input type="text" name="webhook_secret" class="form-control"
                                   value="<?php echo e($hospital->paymentSettings->where('payment_gateway', 'flutterwave')->first()?->webhook_secret); ?>">
                        </div>
                        <button type="submit" class="btn btn-warning w-100 text-white">
                            <i class="fas fa-save me-1"></i> Save Flutterwave Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super-admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/super-admin/payment-settings/edit.blade.php ENDPATH**/ ?>