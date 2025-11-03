@extends('layouts.pharmacy')

@section('page-title', 'Pharmacy Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Pharmacy Sales</h2>
    <a href="{{ route('pharmacy.sales.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Record Sale
    </a>
</div>

@if($sales->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No sales recorded yet.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sale #</th>
                    <th>Patient</th>
                    <th>Pharmacist</th>
                    <th>Amount (₦)</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->patient->name }}</td>
                    <td>{{ $sale->pharmacist->name }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ $sale->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $sale->status == 'completed' ? 'success' : 'danger' }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pharmacy.sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $sales->links() }}
@endif
@endsection