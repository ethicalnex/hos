@extends('hospital.layouts.app')

@section('title', 'Subscription Management')

@section('content')


<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel your subscription?</p>
                <p>This will immediately disable premium features.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                <form method="POST" action="{{ route('hospital.subscription.cancel') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i> Yes, Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-subscription-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('cancelSubscriptionModal'));
            modal.show();
        });
    });
});
</script>


<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">Subscription Management</h1>
        @if($subscriptionStatus['isTrialActive'])
            <a href="{{ route('hospital.subscription.cancel') }}" class="btn btn-warning">
                <i class="fas fa-ban me-1"></i> Cancel Trial
            </a>
        @else
                <form method="POST" action="{{ route('hospital.subscription.cancel') }}">
        @csrf
        <button class="btn btn-danger cancel-subscription-btn">
    <i class="fas fa-ban me-1"></i> Cancel Subscription
</button>    </form>
        @endif
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Subscription Status</h5>
                </div>
                <div class="card-body">
                    @if($subscriptionStatus['hasActiveSubscription'])
                        <p><strong>Plan:</strong> {{ auth()->user()->hospital->subscriptionPlan->name }}</p>
                        <p><strong>Status:</strong> Active</p>
                        <p><strong>Ends:</strong> 
                            {{ auth()->user()->hospital->subscription_ends_at ? auth()->user()->hospital->subscription_ends_at->format('M j, Y') : '—' }}
                        </p>
                    @elseif($subscriptionStatus['isTrialActive'])
                        <p><strong>Plan:</strong> Free Trial</p>
                        <p><strong>Ends:</strong> 
                            {{ auth()->user()->hospital->trial_ends_at ? auth()->user()->hospital->trial_ends_at->format('M j, Y') : '—' }}
                        </p>
                    @else
                        <p><strong>Plan:</strong> No active subscription</p>
                        <a href="{{ route('hospital.subscription.index') }}" class="btn btn-warning">
                            <i class="fas fa-crown me-1"></i> Upgrade Plan
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Upcoming Renewals</h5>
                </div>
                <div class="card-body">
                    @if($upcomingRenewals->isEmpty())
                        <p>No upcoming renewals.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Renewal Date</th>
                                        <th>Amount (₦)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingRenewals as $renewal)
                                    <tr>
                                        <td>{{ $renewal->plan->name }}</td>
                                        <td>{{ $renewal->renewal_date->format('M j, Y') }}</td>
                                        <td>{{ number_format($renewal->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $renewal->is_paid ? 'success' : 'warning' }}">
                                                {{ $renewal->is_paid ? 'Paid' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!$renewal->is_paid)
                                                <a href="{{ route('hospital.subscription.renew', $renewal) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-money-bill-wave me-1"></i> Pay Now
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h3>Subscription History</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Plan</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\SubscriptionLog::where('hospital_id', auth()->user()->hospital_id)->latest()->limit(10)->get() as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M j, Y H:i') }}</td>
                            <td>{{ ucfirst($log->action) }}</td>
                            <td>{{ $log->plan->name }}</td>
                            <td>{{ $log->notes ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection