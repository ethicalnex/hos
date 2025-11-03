
<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Book Appointment at <?php echo e($hospital->name); ?></h2>
    <div class="row">
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5><?php echo e($dept->name); ?></h5>
                    <a href="<?php echo e(route('booking.service', ['hospital' => $hospital->slug, 'department' => $dept])); ?>" 
                       class="btn btn-primary">Select</a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/public/booking/select-department.blade.php ENDPATH**/ ?>