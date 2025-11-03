

<?php $__env->startSection('title', 'Patients'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patients</h1>
</div>

<!-- Search Form -->
<form action="<?php echo e(route('hospital.patients.index')); ?>" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="q" placeholder="Search by name, MRN, phone, or email..." 
               value="<?php echo e(request('q')); ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
</form>

<?php if($patients->isEmpty()): ?>
    <div class="alert alert-info">
        No patients found. <?php if(request('q')): ?> Try a different search term. <?php endif; ?>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>MRN</th>
                    <th>Patient Name</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Blood Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($patient->medical_record_number); ?></td>
                    <td><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></td>
                    <td><?php echo e($patient->phone); ?></td>
                    <td><?php echo e($patient->date_of_birth?->format('Y-m-d')); ?></td>
                    <td><?php echo e(ucfirst($patient->gender)); ?></td>
                    <td><?php echo e($patient->blood_type ?? '—'); ?></td>
                    <td>
                        <a href="<?php echo e(route('hospital.patients.show', $patient)); ?>" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <?php echo e($patients->appends(['q' => request('q')])->links()); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/patients/index.blade.php ENDPATH**/ ?>