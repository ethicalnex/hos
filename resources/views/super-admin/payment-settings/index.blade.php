@extends('super-admin.layouts.app')
@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-credit-card me-2"></i>Payment Settings
        </h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hospital</th>
                            <th>Paystack</th>
                            <th>Flutterwave</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hospitals as $hospital)
                        <tr>
                            <td>{{ $hospital->name }}</td>
                            <td>
                                @if($hospital->paymentSettings->where('payment_gateway', 'paystack')->first())
                                    <span class="badge bg-success">Configured</span>
                                @else
                                    <span class="badge bg-secondary">Not set</span>
                                @endif
                            </td>
                            <td>
                                @if($hospital->paymentSettings->where('payment_gateway', 'flutterwave')->first())
                                    <span class="badge bg-success">Configured</span>
                                @else
                                    <span class="badge bg-secondary">Not set</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('super-admin.payment-settings.edit', $hospital) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection