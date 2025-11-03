@extends('layouts.pharmacy')

@section('page-title', 'Pharmacy Dashboard')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Pending Prescriptions</h5>
                        @php
                            $pendingPrescriptions = \App\Models\Prescription::where('hospital_id', auth()->user()->hospital_id)
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        <h2>{{ number_format($pendingPrescriptions) }}</h2>
                    </div>
                    <i class="fas fa-prescription fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Today's Sales</h5>
                        @php
                            $todaySales = \App\Models\Sale::where('hospital_id', auth()->user()->hospital_id)
                                ->whereDate('created_at', now())
                                ->sum('total_amount');
                        @endphp
                        <h2>₦{{ number_format($todaySales, 2) }}</h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Low Stock Items</h5>
                        @php
                            $lowStockItems = \App\Models\PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
                                ->whereHas('inventory', function ($query) {
                                    $query->where('quantity', '<', 10);
                                })
                                ->with('inventory')
                                ->get();
                        @endphp
                        <h2>{{ $lowStockItems->count() }}</h2>
                    </div>
                    <i class="fas fa-box-open fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Items</h5>
                        @php
                            $totalItems = \App\Models\PharmacyItem::where('hospital_id', auth()->user()->hospital_id)->count();
                        @endphp
                        <h2>{{ number_format($totalItems) }}</h2>
                    </div>
                    <i class="fas fa-capsules fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Low Stock Items</h5>
            </div>
            <div class="card-body">
                @if($lowStockItems->isEmpty())
                    <div class="alert alert-info">No low stock items.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Available Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->available_quantity }}</td>
                                    <td>{{ $item->inventory?->expiry_date ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('hospital.pharmacy.inventory.add', $item) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-plus me-1"></i> Add Stock
                                        </a>
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
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('pharmacy.dispense.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-box-open me-2"></i>Dispense Prescriptions
                </a>
                <a href="{{ route('pharmacy.sales.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-money-bill-wave me-2"></i>Record Sale
                </a>
                <a href="{{ route('pharmacy.reports.generate') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-file-pdf me-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection