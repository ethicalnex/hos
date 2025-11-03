@extends('hospital.layouts.app')

@section('title', 'Add Department')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Department</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('hospital.departments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Departments
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.departments.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Department Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required
                       placeholder="e.g., Cardiology, Pediatrics, Surgery">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="head_doctor_id" class="form-label">Head Doctor</label>
                <select class="form-control @error('head_doctor_id') is-invalid @enderror" 
                        id="head_doctor_id" name="head_doctor_id">
                    <option value="">Select Head Doctor (Optional)</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('head_doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }} - {{ $doctor->specialization }}
                        </option>
                    @endforeach
                </select>
                @error('head_doctor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3"
                          placeholder="Brief description of the department">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Department
                </button>
                <a href="{{ route('hospital.departments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection