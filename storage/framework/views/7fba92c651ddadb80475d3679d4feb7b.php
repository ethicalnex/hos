
<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Select Time for Dr. <?php echo e($doctor->name); ?></h2>
        <a href="<?php echo e(route('booking.doctor', ['hospital' => $hospital->slug, 'service' => request('service_id')])); ?>" 
           class="btn btn-outline-secondary">
            ← Back to Doctors
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" min="<?php echo e(now()->format('Y-m-d')); ?>">
                    <input type="hidden" name="service_id" value="<?php echo e(request('service_id')); ?>">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Check Availability</button>
                </div>
            </form>
        </div>
    </div>

    <?php if(empty($availableSlots)): ?>
        <div class="alert alert-warning">
            No available slots on <?php echo e(\Carbon\Carbon::parse($date)->format('F j, Y')); ?>. Please select another date.
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $availableSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3 mb-3">
                <a href="#" 
                   onclick="document.getElementById('time-<?php echo e($slot); ?>').submit()"
                   class="btn btn-outline-primary w-100">
                    <?php echo e(\Carbon\Carbon::parse($slot)->format('g:i A')); ?>

                </a>
                <form id="time-<?php echo e($slot); ?>" method="POST" action="<?php echo e(route('booking.store', $hospital->slug)); ?>" class="d-none">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="service_id" value="<?php echo e(request('service_id')); ?>">
                    <input type="hidden" name="doctor_id" value="<?php echo e($doctor->id); ?>">
                    <input type="hidden" name="date" value="<?php echo e($date); ?>">
                    <input type="hidden" name="time" value="<?php echo e($slot); ?>">
                 
                </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/public/booking/select-time.blade.php ENDPATH**/ ?>