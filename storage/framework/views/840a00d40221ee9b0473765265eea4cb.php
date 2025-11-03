

<?php $__env->startSection('page-title', 'Create Prescription'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h5>Create Prescription</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('doctor.prescriptions.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Patient *</label>
                <select name="patient_id" class="form-control" required>
    <option value="">Select Patient</option>
    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($patient->id); ?>"><?php echo e($patient->name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
            </div>
            <div class="mb-3">
                <label class="form-label">Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Valid Until</label>
                <input type="date" name="valid_until" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Medications *</label>
                <div id="medication-list">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select name="items[0][item_id]" class="form-control medication-item" required>
                                <option value="">Select Medication</option>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?> (<?php echo e($item->category); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][dosage]" class="form-control" placeholder="Dosage" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][frequency]" class="form-control" placeholder="Frequency" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][quantity]" class="form-control" min="1" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][instructions]" class="form-control" placeholder="Instructions">
                        </div>
                    </div>
                </div>
                <button type="button" id="add-medication" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-plus me-1"></i> Add Another Medication
                </button>
            </div>
            <button type="submit" class="btn btn-primary">Create Prescription</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let medicationCount = 1;
    
    document.getElementById('add-medication').addEventListener('click', function() {
        const medicationList = document.getElementById('medication-list');
        const newMedication = document.createElement('div');
        newMedication.className = 'row mb-2';
        newMedication.innerHTML = `
            <div class="col-md-4">
                <select name="items[${medicationCount}][item_id]" class="form-control medication-item" required>
                    <option value="">Select Medication</option>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?> (<?php echo e($item->category); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][dosage]" class="form-control" placeholder="Dosage" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][frequency]" class="form-control" placeholder="Frequency" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${medicationCount}][quantity]" class="form-control" min="1" placeholder="Quantity" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][instructions]" class="form-control" placeholder="Instructions">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-medication">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        medicationList.appendChild(newMedication);
        medicationCount++;
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-medication')) {
            e.target.closest('.row').remove();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/doctor/prescriptions/create.blade.php ENDPATH**/ ?>