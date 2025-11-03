@extends('hospital.layouts.app')

@section('title', 'Pharmacy Items')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pharmacy Items</h1>
    <a href="{{ route('hospital.pharmacy.items.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Item
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Form</th>
                <th>Strength</th>
                <th>Price (₦)</th>
                <th>Available Quantity</th>
                <th>Status</th>
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
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->available_quantity }}</td>
                <td>
                    <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('hospital.pharmacy.items.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
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