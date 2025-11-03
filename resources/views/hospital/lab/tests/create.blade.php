@extends('hospital.layouts.app')

@section('title', 'Create Lab Test')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Lab Test</h1>
    <a href="{{ route('hospital.lab.tests.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Tests
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.lab.tests.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Test Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Normal Range</label>
                    <input type="text" name="normal_range" class="form-control" value="{{ old('normal_range') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Specimen Type</label>
                    <input type="text" name="specimen_type" class="form-control" value="{{ old('specimen_type') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Turnaround Time (hours) *</label>
                <input type="number" name="turnaround_time" class="form-control" value="{{ old('turnaround_time', 24) }}" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Create Test
            </button>
        </form>
    </div>
</div>
@endsection