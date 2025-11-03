@extends('super-admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-crown me-2"></i>Subscription Plans
        </h1>
        <a href="{{ route('super-admin.subscription-plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Plan
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($plans->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No subscription plans found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price (₦)</th>
                                <th>Trial Days</th>
                                <th>Billing Cycle</th>
                                <th>Features</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr>
                                <td><strong>{{ $plan->name }}</strong></td>
                                <td>{{ number_format($plan->price, 2) }}</td>
                                <td>{{ $plan->trial_days }}</td>
                                <td>{{ ucfirst($plan->billing_cycle) }}</td>
                                <td>
                                    @foreach($plan->features as $feature => $enabled)
                                        @if($enabled)
                                            <span class="badge bg-success me-1">{{ $plan->getFeatureName($feature) }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('super-admin.subscription-plans.edit', $plan) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('super-admin.subscription-plans.destroy', $plan) }}" 
                                          method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $plans->links() }}
            @endif
        </div>
    </div>
</div>
@endsection