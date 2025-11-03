<!--  -->

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo e($department->name); ?> Services</h2>
        <a href="<?php echo e(route('booking.department', $hospital->slug)); ?>" class="btn btn-outline-secondary">
            ← Back to Departments
        </a>
    </div>

    <div class="row">
        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5><?php echo e($service->name); ?></h5>
                    <p class="text-muted"><?php echo e($service->description ?? 'No description'); ?></p>
                    <div class="mt-auto">
                        <p class="h5 text-primary">₦<?php echo e(number_format($service->price, 2)); ?></p>
                        <a href="<?php echo e(route('booking.doctor', ['hospital' => $hospital->slug, 'service' => $service->id])); ?>" 
   class="btn btn-primary w-100">Select Doctor</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/public/booking/select-service.blade.php ENDPATH**/ ?>