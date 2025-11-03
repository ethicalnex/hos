

<?php $__env->startSection('title', 'Select Doctor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Select Doctor for <?php echo e($service->name); ?></h2>
        <a href="<?php echo e(route('booking.service', ['hospital' => $hospital->slug, 'department' => $service->department_id])); ?>" 
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Services
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Service:</strong> <?php echo e($service->name); ?> | 
        <strong>Price:</strong> ₦<?php echo e(number_format($service->price, 2)); ?> | 
        <strong>Duration:</strong> <?php echo e($service->duration); ?> minutes
    </div>

    <?php if($doctors->isEmpty()): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No doctors available for this service at the moment. Please try again later.
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Dr. <?php echo e($doctor->name); ?></h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-stethoscope me-1"></i>
                                    <?php echo e($doctor->specialization ?? 'General Practitioner'); ?>

                                </p>
                                <?php if($doctor->license_number): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>
                                        License: <?php echo e($doctor->license_number); ?>

                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($doctor->phone): ?>
                            <p class="text-muted mb-3">
                                <i class="fas fa-phone me-1"></i> <?php echo e($doctor->phone); ?>

                            </p>
                        <?php endif; ?>

                        <div class="mt-auto">
                            <a href="<?php echo e(route('booking.time', ['hospital' => $hospital->slug, 'doctor' => $doctor->id])); ?>?service_id=<?php echo e($service->id); ?>" 
                               class="btn btn-primary w-100 py-2">
                                <i class="fas fa-clock me-2"></i>Select Time Slot
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/public/booking/select-doctor.blade.php ENDPATH**/ ?>