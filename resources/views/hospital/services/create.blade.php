@extends('hospital.layouts.app')

@section('title', 'Create Service')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Service</h1>
    <a href="{{ route('hospital.services.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Services
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.services.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Department *</label>
                <select name="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Service Name *</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (₦) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration (minutes) *</label>
                    <input type="number" name="duration" class="form-control" min="10" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Create Service
            </button>
        </form>
    </div>
</div>
@endsection