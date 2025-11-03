

<?php $__env->startSection('title', 'Doctor Schedules'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Schedules</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo e(route('hospital.doctor-schedules.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Schedule
        </a>
    </div>
</div>

<div class="row">
<?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Dr. <?php echo e($doctor->name); ?></h5>
            </div>
            <div class="card-body">
                <?php if($doctor->schedules->isEmpty()): ?>
                    <p class="text-muted">No schedule set</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $doctor->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(ucfirst($schedule->day_of_week)); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('g:i A')); ?></td>
                                <td><?php echo e($schedule->slot_duration); ?> min</td>
                                <td>
                                    <span class="badge bg-<?php echo e($schedule->is_available ? 'success' : 'secondary'); ?>">
                                        <?php echo e($schedule->is_available ? 'Available' : 'Unavailable'); ?>

                                    </span>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('hospital.doctor-schedules.toggle', $schedule)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-<?php echo e($schedule->is_available ? 'danger' : 'success'); ?>">
                                            <?php echo e($schedule->is_available ? 'Disable' : 'Enable'); ?>

                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('hospital.doctor-schedules.destroy', $schedule)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this schedule?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/doctor-schedules/index.blade.php ENDPATH**/ ?>