

<?php $__env->startSection('title', 'Register Your Hospital'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Register Your Hospital</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('hospital.register')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Hospital Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug (URL) *</label>
                            <input type="text" name="slug" class="form-control" value="<?php echo e(old('slug')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo e(old('address')); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?php echo e(old('city')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="<?php echo e(old('state')); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Email *</label>
                            <input type="email" name="admin_email" class="form-control" value="<?php echo e(old('admin_email')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Password *</label>
                            <input type="password" name="admin_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subscription Plan *</label>
                            <select name="subscription_plan_id" class="form-control" required>
                                <option value="">Select Plan</option>
                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($plan->id); ?>" <?php echo e(old('subscription_plan_id') == $plan->id ? 'selected' : ''); ?>>
                                        <?php echo e($plan->name); ?> - ₦<?php echo e(number_format($plan->price, 2)); ?>/<?php echo e($plan->billing_cycle); ?>

                                        <?php if($plan->trial_days > 0): ?>
                                            (<?php echo e($plan->trial_days); ?> days free trial)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_paystack" value="paystack" checked>
                                <label class="form-check-label" for="payment_paystack">
                                    <i class="fab fa-paystack me-2"></i>Paystack
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_flutterwave" value="flutterwave">
                                <label class="form-check-label" for="payment_flutterwave">
                                    <i class="fab fa-cc-visa me-2"></i>Flutterwave
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-lock me-2"></i>Register & Pay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital-registration/register.blade.php ENDPATH**/ ?>