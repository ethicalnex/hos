

<?php $__env->startSection('title', 'Payment Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Payment Gateway Settings</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('hospital.settings.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Settings
        </a>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Configure your Paystack or Flutterwave test/live keys below. Use <strong>test keys</strong> during development.
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gateway Configuration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('hospital.payment.save')); ?>">
                    <?php echo csrf_field(); ?>

                    <!-- Gateway Selection -->
                    <div class="mb-3">
                        <label for="payment_gateway" class="form-label">Payment Gateway *</label>
                        <select class="form-control <?php $__errorArgs = ['payment_gateway'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="payment_gateway" name="payment_gateway" required>
                            <option value="">Select a gateway</option>
                            <option value="paystack" <?php echo e(old('payment_gateway', $paymentSettings->first()?->payment_gateway) == 'paystack' ? 'selected' : ''); ?>>
                                Paystack
                            </option>
                            <option value="flutterwave" <?php echo e(old('payment_gateway', $paymentSettings->first()?->payment_gateway) == 'flutterwave' ? 'selected' : ''); ?>>
                                Flutterwave
                            </option>
                        </select>
                        <?php $__errorArgs = ['payment_gateway'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Public Key -->
                    <div class="mb-3">
                        <label for="public_key" class="form-label">Public Key *</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="public_key" 
                               name="public_key" 
                               value="<?php echo e(old('public_key') ?? ($paymentSettings->first()?->public_key ?? '')); ?>" 
                               placeholder="e.g., pk_test_xxx or FLWPUBK_TEST-xxx"
                               required>
                        <?php $__errorArgs = ['public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">
                            <strong>Paystack:</strong> Starts with <code>pk_test_</code> (test) or <code>pk_live_</code> (live)<br>
                            <strong>Flutterwave:</strong> Found in your dashboard under API Keys
                        </div>
                    </div>

                    <!-- Secret Key -->
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Secret Key *</label>
                        <input type="password" 
                               class="form-control <?php $__errorArgs = ['secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="secret_key" 
                               name="secret_key" 
                               value="<?php echo e(old('secret_key')); ?>" 
                               placeholder="••••••••••••••••"
                               required>
                        <?php $__errorArgs = ['secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">
                            Never share this key. Used only by your server.
                        </div>
                    </div>

                    <!-- Webhook Secret -->
                    <div class="mb-3">
                        <label for="webhook_secret" class="form-label">Webhook Secret</label>
                        <input type="password" 
                               class="form-control" 
                               id="webhook_secret" 
                               name="webhook_secret" 
                               value="<?php echo e(old('webhook_secret')); ?>" 
                               placeholder="Optional, but recommended">
                        <div class="form-text">
                            <strong>Paystack:</strong> Set in Dashboard → Webhooks → Edit → "Add Secret Key"<br>
                            <strong>Flutterwave:</strong> Called "Verify hash" in Webhook settings
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Save Payment Settings
                        </button>
                        <a href="<?php echo e(route('hospital.settings.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Settings Preview -->
        <?php if($paymentSettings->isNotEmpty()): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Active Payment Settings</h5>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $paymentSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <span class="badge bg-primary">
                                        <?php echo e(ucfirst($setting->payment_gateway)); ?>

                                    </span>
                                    <?php if($setting->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </h6>
                                <p class="mb-1"><strong>Public Key:</strong> <?php echo e(substr($setting->public_key, 0, 8)); ?><?php echo e(str_repeat('*', max(0, strlen($setting->public_key) - 8))); ?></p>
                                <p class="mb-1"><strong>Secret Key:</strong> <?php echo e($setting->secret_key ? '••••••••' : 'Not set'); ?></p>
                                <p class="mb-0"><strong>Webhook Secret:</strong> <?php echo e($setting->webhook_secret ? '••••••••' : 'Not set'); ?></p>
                            </div>
                            <div>
                                <!-- Future: Add "Make Default" or "Deactivate" button -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">How to Find Your Keys</h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Paystack</h6>
                <ol>
                    <li>Go to <a href="https://dashboard.paystack.com" target="_blank">Paystack Dashboard</a></li>
                    <li>Click <strong>Settings → API Keys & Webhooks</strong></li>
                    <li>Copy <strong>Test Secret Key</strong> and <strong>Test Public Key</strong></li>
                    <li>Set a <strong>Webhook URL</strong>: <code><?php echo e(url('/webhooks/paystack')); ?></code></li>
                    <li>Enable and set a <strong>Secret Key</strong> for the webhook</li>
                </ol>

                <h6 class="text-primary mt-3">Flutterwave</h6>
                <ol>
                    <li>Go to <a href="https://dashboard.flutterwave.com" target="_blank">Flutterwave Dashboard</a></li>
                    <li>Click <strong>Settings → API</strong></li>
                    <li>Copy <strong>Test Secret Key</strong> and <strong>Test Public Key</strong></li>
                    <li>Go to <strong>Webhooks</strong>, add URL: <code><?php echo e(url('/webhooks/flutterwave')); ?></code></li>
                    <li>Set a <strong>Verify hash</strong> (this is your webhook secret)</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/settings/payment.blade.php ENDPATH**/ ?>