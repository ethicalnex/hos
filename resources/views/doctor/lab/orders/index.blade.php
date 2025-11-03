@extends('layouts.doctor')

@section('page-title', 'Lab Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Orders</h2>
    <a href="{{ route('doctor.lab.orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Lab Order
    </a>
</div>

@if($orders->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No lab orders created yet.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Patient</th>
                    <th>Tests</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->patient->name }}</td>
                    <td>{{ $order->tests->count() }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'in_progress' ? 'info' : 'success') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M j, Y') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif
@endsection