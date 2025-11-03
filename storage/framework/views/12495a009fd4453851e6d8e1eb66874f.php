
<?php $__env->startSection('page-title', 'Book Appointment'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5>Book New Appointment</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('patient.appointments.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label>Doctor *</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Select a doctor</option>
                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($doctor->id); ?>"><?php echo e($doctor->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Appointment Date & Time *</label>
                <input type="datetime-local" name="scheduled_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Reason for Visit</label>
                <textarea name="reason" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/patient/appointments/book.blade.php ENDPATH**/ ?>