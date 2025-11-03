

<?php $__env->startSection('title', 'Hospital Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">Hospital Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Subscription Status</h5>
                </div>
                <div class="card-body">
                    <?php
                        $hospital = auth()->user()->hospital;
                        $subscriptionStatus = [
                            'hasActiveSubscription' => $hospital->hasActiveSubscription(),
                            'isTrialActive' => $hospital->isTrialActive(),
                            'trialEndsAt' => $hospital->trial_ends_at,
                            'subscriptionEndsAt' => $hospital->subscription_ends_at,
                        ];
                    ?>

                    <?php if($subscriptionStatus['hasActiveSubscription']): ?>
                        <p><strong>Plan:</strong> <?php echo e($hospital->subscriptionPlan->name); ?></p>
                        <p><strong>Status:</strong> Active</p>
                        <p><strong>Ends:</strong> 
                            <?php echo e($hospital->subscription_ends_at ? $hospital->subscription_ends_at->format('M j, Y') : '—'); ?>

                        </p>
                    <?php elseif($subscriptionStatus['isTrialActive']): ?>
                        <p><strong>Plan:</strong> Free Trial</p>
                        <p><strong>Ends:</strong> 
                            <?php echo e($hospital->trial_ends_at ? $hospital->trial_ends_at->format('M j, Y') : '—'); ?>

                        </p>
                    <?php else: ?>
                        <p><strong>Plan:</strong> No active subscription</p>
                        <a href="<?php echo e(route('hospital.subscription.index')); ?>" class="btn btn-warning">
                            <i class="fas fa-crown me-1"></i> Upgrade Plan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="<?php echo e(route('hospital.patients.index')); ?>" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-injured me-2"></i>Patient Management
                    </a>
                    <a href="<?php echo e(route('hospital.staff.index')); ?>" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-users me-2"></i>Staff Management
                    </a>
                    <a href="<?php echo e(route('hospital.departments.index')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-hospital me-2"></i>Department Management
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Patients</h5>
                </div>
                <div class="card-body">
                    <?php
                        $patientCount = \App\Models\User::where('hospital_id', auth()->user()->hospital_id)
                            ->where('role', 'patient')
                            ->count();
                    ?>
                    <h2><?php echo e(number_format($patientCount)); ?></h2>
                    <p>Total Patients</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Appointments</h5>
                </div>
                <div class="card-body">
                    <?php
                        $appointmentCount = \App\Models\Appointment::where('hospital_id', auth()->user()->hospital_id)->count();
                    ?>
                    <h2><?php echo e(number_format($appointmentCount)); ?></h2>
                    <p>Total Appointments</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Revenue</h5>
                </div>
                <div class="card-body">
                    <?php
                        $revenue = \App\Models\Payment::where('hospital_id', auth()->user()->hospital_id)->sum('amount');
                    ?>
                    <h2>₦<?php echo e(number_format($revenue, 2)); ?></h2>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('hospital.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\EthicalNex\resources\views/hospital/dashboard/index.blade.php ENDPATH**/ ?>