@extends('layouts.pharmacy')

@section('page-title', 'Sale #{{ $sale->id }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Sale #{{ $sale->id }}</h5>
                <p class="mb-0">
                    <strong>Patient:</strong> {{ $sale->patient->name }}<br>
                    <strong>Pharmacist:</strong> {{ $sale->pharmacist->name }}<br>
                    <strong>Date:</strong> {{ $sale->created_at->format('M j, Y') }}
                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price (₦)</th>
                                <th>Total (₦)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <strong>Total Amount:</strong>
                    <strong>₦{{ number_format($sale->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Sale Details</h6>
            </div>
            <div class="card-body">
                <p><strong>Payment Method:</strong> {{ $sale->payment_method ?? '—' }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $sale->status == 'completed' ? 'success' : 'danger' }}">
                        {{ ucfirst($sale->status) }}
                    </span>
                </p>
                <p><strong>Notes:</strong> {{ $sale->notes ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection