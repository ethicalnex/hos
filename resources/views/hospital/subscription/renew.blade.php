@extends('hospital.layouts.app')

@section('title', 'Renew Subscription')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">Renew Subscription</h1>
        <a href="{{ route('hospital.subscription.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p>You are about to renew your subscription for:</p>
            <h4>{{ $renewal->plan->name }}</h4>
            <p><strong>Amount:</strong> ₦{{ number_format($renewal->amount, 2) }}</p>
            <p><strong>Renewal Date:</strong> {{ $renewal->renewal_date->format('M j, Y') }}</p>

            <form method="POST" action="{{ route('hospital.subscription.renew.callback', $renewal) }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-money-bill-wave me-1"></i> Pay Now
                </button>
            </form>
        </div>
    </div>
</div>
@endsection