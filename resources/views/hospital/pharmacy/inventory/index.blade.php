@extends('hospital.layouts.app')

@section('title', 'Pharmacy Inventory')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pharmacy Inventory</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Form</th>
                <th>Strength</th>
                <th>Available Quantity</th>
                <th>Expiry Date</th>
                <th>Batch Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->form }}</td>
                <td>{{ $item->strength }}</td>
                <td>{{ $item->available_quantity }}</td>
                <td>{{ $item->inventory?->expiry_date ?? '—' }}</td>
                <td>{{ $item->inventory?->batch_number ?? '—' }}</td>
                <td>
                    <a href="{{ route('hospital.pharmacy.inventory.add', $item) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-box"></i> Add Stock
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $items->links() }}
@endsection