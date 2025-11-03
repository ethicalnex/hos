@extends('hospital.layouts.app')

@section('title', 'EMR Record #{{ $record->id }}')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">EMR Record #{{ $record->id }}</h1>
        <a href="{{ route('hospital.emr.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Records
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5>Patient Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $record->patient->name }}</p>
                    <p><strong>MRN:</strong> {{ $record->patient->patient->medical_record_number }}</p>
                    <p><strong>Phone:</strong> {{ $record->patient->phone }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5>Doctor Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $record->doctor->name }}</p>
                    <p><strong>Department:</strong> {{ $record->doctor->department?->name ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header">
            <h5>Medical Record</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Diagnosis</label>
                <p>{{ $record->diagnosis ?? '—' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label">Treatment</label>
                <p>{{ $record->treatment ?? '—' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <p>{{ $record->notes ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection