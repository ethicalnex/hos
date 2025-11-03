@extends('layouts.lab-technician')

@section('page-title', 'Appointment Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Appointment #{{ $appointment->id }}</h5>
        <p class="mb-0">
            <strong>Date:</strong> {{ $appointment->scheduled_time->format('M j, Y') }}<br>
            <strong>Time:</strong> {{ $appointment->scheduled_time->format('g:i A') }}
        </p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Patient Information</h6>
                <p><strong>Name:</strong> {{ $appointment->patient->name }}</p>
                <p><strong>MRN:</strong> {{ $appointment->patient->patient->medical_record_number }}</p>
                <p><strong>Phone:</strong> {{ $appointment->patient->phone }}</p>
            </div>
            <div class="col-md-6">
                <h6>Doctor Information</h6>
                <p><strong>Name:</strong> {{ $appointment->doctor->name }}</p>
                <p><strong>Department:</strong> {{ $appointment->doctor->department?->name ?? '—' }}</p>
            </div>
        </div>
        
        <div class="mt-3">
            <h6>Service</h6>
            <p>{{ $appointment->service?->name ?? '—' }}</p>
        </div>
        
        <div class="mt-3">
            <h6>Notes</h6>
            <p>{{ $appointment->reason ?? 'No notes' }}</p>
        </div>
        
        <div class="mt-3">
            <h6>Status</h6>
            <span class="badge bg-{{ $appointment->status === 'scheduled' ? 'warning' : ($appointment->status === 'confirmed' ? 'info' : 'success') }}">
                {{ ucfirst($appointment->status) }}
            </span>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('lab-technician.appointments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Appointments
        </a>
    </div>
</div>
@endsection