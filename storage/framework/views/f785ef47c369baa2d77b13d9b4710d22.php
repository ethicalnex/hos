

<?php $__env->startSection('page-title', 'Create EMR Record'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Create EMR Record</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('doctor.emr.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Patient *</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($patient->id); ?>"><?php echo e($patient->name); ?> (MRN: <?php echo e($patient->patient->medical_record_number ?? '—'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Appointment</label>
                        <select name="appointment_id" class="form-control">
                            <option value="">No Appointment</option>
                            <?php $__currentLoopData = \App\Models\Appointment::where('doctor_id', auth()->id())->whereDate('scheduled_time', '<=', now())->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($appointment->id); ?>">
                                    <?php echo e($appointment->scheduled_time->format('M j, Y g:i A')); ?> - <?php echo e($appointment->patient->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Symptoms</label>
                                <textarea class="form-control" name="symptoms" rows="3"><?php echo e(old('symptoms')); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Temperature (°C)</label>
                                    <input type="text" class="form-control" name="temperature" value="<?php echo e(old('temperature')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Blood Pressure</label>
                                    <input type="text" class="form-control" name="blood_pressure" value="<?php echo e(old('blood_pressure')); ?>" placeholder="e.g., 120/80">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pulse (BPM)</label>
                                    <input type="text" class="form-control" name="pulse" value="<?php echo e(old('pulse')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Respiratory Rate</label>
                                    <input type="text" class="form-control" name="respiratory_rate" value="<?php echo e(old('respiratory_rate')); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="text" class="form-control" name="weight" value="<?php echo e(old('weight')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="text" class="form-control" name="height" value="<?php echo e(old('height')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Diagnosis *</label>
                                <textarea class="form-control" name="diagnosis" rows="4" required><?php echo e(old('diagnosis')); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Treatment Plan *</label>
                                <textarea class="form-control" name="treatment_plan" rows="4" required><?php echo e(old('treatment_plan')); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Doctor Notes</label>
                                <textarea class="form-control" name="doctor_notes" rows="3"><?php echo e(old('doctor_notes')); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nurse Notes</label>
                                <textarea class="form-control" name="nurse_notes" rows="3"><?php echo e(old('nurse_notes')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save EMR Record</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Today's Schedule</h6>
            </div>
            <div class="card-body">
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
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/emr/create.blade.php ENDPATH**/ ?>