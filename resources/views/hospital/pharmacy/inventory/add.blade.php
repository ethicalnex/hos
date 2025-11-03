@extends('hospital.layouts.app')

@section('title', 'Add Stock to ' . $item->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Stock to {{ $item->name }}</h1>
    <a href="{{ route('hospital.pharmacy.inventory.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.pharmacy.inventory.store', $item) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Quantity to Add *</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Batch Number</label>
                <input type="text" name="batch_number" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Stock
            </button>
        </form>
    </div>
</div>
@endsection