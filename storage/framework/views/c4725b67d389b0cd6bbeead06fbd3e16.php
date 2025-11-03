
<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Complete Your Booking</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Patient Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('booking.process-payment', $hospital->slug)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">
                        <input type="hidden" name="service_id" value="<?php echo e($appointment->service_id); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
    <label class="form-label">Emergency Contact Name *</label>
    <input type="text" name="emergency_contact_name" class="form-control" required>
</div>
<div class="mb-3">
    <label class="form-label">Emergency Contact Phone *</label>
    <input type="text" name="emergency_contact_phone" class="form-control" required>
</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Hospital:</strong> <?php echo e($hospital->name); ?></p>
                    <p><strong>Doctor:</strong> Dr. <?php echo e($appointment->doctor->name); ?></p>
                    <p><strong>Service:</strong> <?php echo e($appointment->service->name); ?></p>
                    <p><strong>Date & Time:</strong> <?php echo e($appointment->scheduled_time->format('M j, Y g:i A')); ?></p>
                    <p><strong>Duration:</strong> <?php echo e($appointment->duration); ?> minutes</p>
                    <p class="h5 text-primary">Total: ₦<?php echo e(number_format($appointment->service->price, 2)); ?></p>
                    
                    <!-- Payment Method Selection -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_online" value="online" checked>
                            <label class="form-check-label" for="payment_online">
                                <i class="fas fa-credit-card me-2"></i>Pay Online
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_hospital" value="hospital">
                            <label class="form-check-label" for="payment_hospital">
                                <i class="fas fa-hospital me-2"></i>Pay at Hospital
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="fas fa-lock me-2"></i>
                        <span class="payment-text">Pay Now</span>
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const onlineRadio = document.getElementById('payment_online');
    const hospitalRadio = document.getElementById('payment_hospital');
    const paymentText = document.querySelector('.payment-text');
    
    onlineRadio.addEventListener('change', function() {
        if(this.checked) {
            paymentText.textContent = 'Pay Now';
        }
    });
    
    hospitalRadio.addEventListener('change', function() {
        if(this.checked) {
            paymentText.textContent = 'Confirm Appointment';
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/public/booking/payment.blade.php ENDPATH**/ ?>