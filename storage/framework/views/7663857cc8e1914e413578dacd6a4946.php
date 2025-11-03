

<?php $__env->startSection('title', 'Create Pharmacy Item'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Pharmacy Item</h1>
    <a href="<?php echo e(route('hospital.pharmacy.items.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Items
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('hospital.pharmacy.items.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Item Name *</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Antibiotics" <?php echo e(old('category') == 'Antibiotics' ? 'selected' : ''); ?>>Antibiotics</option>
                        <option value="Painkillers" <?php echo e(old('category') == 'Painkillers' ? 'selected' : ''); ?>>Painkillers</option>
                        <option value="Vitamins" <?php echo e(old('category') == 'Vitamins' ? 'selected' : ''); ?>>Vitamins</option>
                        <option value="Antihistamines" <?php echo e(old('category') == 'Antihistamines' ? 'selected' : ''); ?>>Antihistamines</option>
                        <option value="Antacids" <?php echo e(old('category') == 'Antacids' ? 'selected' : ''); ?>>Antacids</option>
                        <option value="Others" <?php echo e(old('category') == 'Others' ? 'selected' : ''); ?>>Others</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Form *</label>
                    <select name="form" class="form-control" required>
                        <option value="">Select Form</option>
                        <option value="Tablet" <?php echo e(old('form') == 'Tablet' ? 'selected' : ''); ?>>Tablet</option>
                        <option value="Syrup" <?php echo e(old('form') == 'Syrup' ? 'selected' : ''); ?>>Syrup</option>
                        <option value="Injection" <?php echo e(old('form') == 'Injection' ? 'selected' : ''); ?>>Injection</option>
                        <option value="Cream" <?php echo e(old('form') == 'Cream' ? 'selected' : ''); ?>>Cream</option>
                        <option value="Ointment" <?php echo e(old('form') == 'Ointment' ? 'selected' : ''); ?>>Ointment</option>
                        <option value="Capsule" <?php echo e(old('form') == 'Capsule' ? 'selected' : ''); ?>>Capsule</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Strength *</label>
                    <input type="text" name="strength" class="form-control" value="<?php echo e(old('strength')); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo e(old('price')); ?>" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Create Item
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/pharmacy/items/create.blade.php ENDPATH**/ ?>