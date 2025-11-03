

<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Personal Information</h5>
            </div>
            <div class="card-body">
                <p><strong>MRN:</strong> <?php echo e($patient->medical_record_number); ?></p>
                <p><strong>Name:</strong> <?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo e($patient->date_of_birth?->format('F j, Y')); ?> 
                   (<?php echo e(\Carbon\Carbon::parse($patient->date_of_birth)->age); ?> years)</p>
                <p><strong>Gender:</strong> <?php echo e(ucfirst($patient->gender)); ?></p>
                <p><strong>Phone:</strong> <?php echo e($patient->phone); ?></p>
                <p><strong>Email:</strong> <?php echo e($patient->email); ?></p>
                <p><strong>Address:</strong> <?php echo e($patient->address ?? 'Not provided'); ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Medical Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Blood Type:</strong> <?php echo e($patient->blood_type ?? 'Not specified'); ?></p>
                <p><strong>Allergies:</strong> <?php echo e($patient->allergies ?? 'None reported'); ?></p>
                <p><strong>Insurance Provider:</strong> <?php echo e($patient->insurance_provider ?? '—'); ?></p>
                <p><strong>Insurance Number:</strong> <?php echo e($patient->insurance_number ?? '—'); ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Emergency Contact</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo e($patient->emergency_contact_name); ?></p>
                <p><strong>Phone:</strong> <?php echo e($patient->emergency_contact_phone); ?></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body text-center">
                <a href="<?php echo e(route('patient.home')); ?>" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/patient/profile.blade.php ENDPATH**/ ?>