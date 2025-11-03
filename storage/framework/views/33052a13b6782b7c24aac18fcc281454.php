

<?php $__env->startSection('page-title', 'Doctor Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Welcome, Dr. <?php echo e(auth()->user()->name); ?>!</h5>
            </div>
            <div class="card-body">
                <p>You are logged in as a doctor at <strong><?php echo e(auth()->user()->hospital->name); ?></strong>.</p>

                <?php
                    $today = \Carbon\Carbon::today();
                    $appointments = \App\Models\Appointment::where('doctor_id', auth()->id())
                        ->whereDate('scheduled_time', $today)
                        ->with(['patient' => function ($query) {
                            $query->with('patient');
                        }])
                        ->orderBy('scheduled_time')
                        ->get();
                ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Today you have <strong><?php echo e($appointments->count()); ?> appointment<?php echo e($appointments->count() == 1 ? '' : 's'); ?></strong>. Don't forget to complete EMR records after each visit.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('doctor.appointments.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i>My Appointments
                    </a>
                    <a href="<?php echo e(route('doctor.lab.orders.index')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-flask me-2"></i>Lab Orders
                    </a>
                    <a href="#" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar-alt me-2"></i>Calendar View
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Today's Schedule</h6>
            </div>
            <div class="card-body">
                <?php if($appointments->isEmpty()): ?>
                    <p class="text-muted">No appointments today.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <?php echo e($appointment->scheduled_time->format('g:i A')); ?> - 
                                <?php echo e($appointment->patient->name); ?> 
                                (MRN: <?php echo e($appointment->patient->patient->medical_record_number ?? '—'); ?>)
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>