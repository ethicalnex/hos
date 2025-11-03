@extends('hospital.layouts.app')

@section('title', 'Edit Pharmacy Item')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Pharmacy Item</h1>
    <a href="{{ route('hospital.pharmacy.items.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Items
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.pharmacy.items.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Item Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Antibiotics" {{ old('category', $item->category) == 'Antibiotics' ? 'selected' : '' }}>Antibiotics</option>
                        <option value="Painkillers" {{ old('category', $item->category) == 'Painkillers' ? 'selected' : '' }}>Painkillers</option>
                        <option value="Vitamins" {{ old('category', $item->category) == 'Vitamins' ? 'selected' : '' }}>Vitamins</option>
                        <option value="Antihistamines" {{ old('category', $item->category) == 'Antihistamines' ? 'selected' : '' }}>Antihistamines</option>
                        <option value="Antacids" {{ old('category', $item->category) == 'Antacids' ? 'selected' : '' }}>Antacids</option>
                        <option value="Others" {{ old('category', $item->category) == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Form *</label>
                    <select name="form" class="form-control" required>
                        <option value="">Select Form</option>
                        <option value="Tablet" {{ old('form', $item->form) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Syrup" {{ old('form', $item->form) == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                        <option value="Injection" {{ old('form', $item->form) == 'Injection' ? 'selected' : '' }}>Injection</option>
                        <option value="Cream" {{ old('form', $item->form) == 'Cream' ? 'selected' : '' }}>Cream</option>
                        <option value="Ointment" {{ old('form', $item->form) == 'Ointment' ? 'selected' : '' }}>Ointment</option>
                        <option value="Capsule" {{ old('form', $item->form) == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Strength *</label>
                    <input type="text" name="strength" class="form-control" value="{{ old('strength', $item->strength) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price) }}" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Update Item
            </button>
        </form>
    </div>
</div>
@endsection