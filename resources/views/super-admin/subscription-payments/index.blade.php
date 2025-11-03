@extends('super-admin.layouts.app')

@section('title', 'Subscription Payments')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-coins me-2"></i>Subscription Payments
        </h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hospital</th>
                            <th>Plan</th>
                            <th>Amount (₦)</th>
                            <th>Gateway</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->hospital->name }}</td>
                            <td>{{ $payment->plan->name }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->payment_gateway == 'paystack' ? 'primary' : 'warning' }}">
                                    {{ ucfirst($payment->payment_gateway) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $payment->status == 'successful' ? 'success' : 'warning' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('M j, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection