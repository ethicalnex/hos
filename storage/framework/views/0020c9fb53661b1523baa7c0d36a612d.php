

<?php $__env->startSection('page-title', 'Nurse Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Welcome, <?php echo e(auth()->user()->name); ?>!</h5>
            </div>
            <div class="card-body">
                <p>You are logged in as a nurse at <strong><?php echo e(auth()->user()->hospital->name); ?></strong>.</p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    You have <strong>5 patients</strong assigned for vital signs monitoring today.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
           <div class="card-body">
                <a href="<?php echo e(route('nurse.appointments.index')); ?>" class="btn btn-primary w-100 mb-2">
    <i class="fas fa-calendar-check me-2"></i>Today's Appointments
</a>
                <a href="#" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-heartbeat me-2"></i>Record Vital Signs
                </a>
                <a href="#" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-notes-medical me-2"></i>Add Nurse Notes
                    </a>
                <a href="#" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-bed me-2"></i>Ward Assignments
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Assigned Patients</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">John Doe (MRN: KENNY-0001)</li>
                    <li class="list-group-item">Jane Smith (MRN: KENNY-0002)</li>
                    <li class="list-group-item">Robert Brown (MRN: KENNY-0003)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/nurse/dashboard.blade.php ENDPATH**/ ?>