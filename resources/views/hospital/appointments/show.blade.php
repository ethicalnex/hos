@extends('hospital.layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Appointment Details</h2>
    <a href="{{ route('hospital.appointments.index') }}" class="btn btn-secondary">← Back to Appointments</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Patient Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $appointment->patient->name }}</p>
                <p><strong>Phone:</strong> {{ $appointment->patient->phone }}</p>
                <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Appointment Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Date & Time:</strong> {{ $appointment->scheduled_time->format('F j, Y g:i A') }}</p>
                <p><strong>Duration:</strong> {{ $appointment->duration }} minutes</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $appointment->status === 'scheduled' ? 'warning' : ($appointment->status === 'confirmed' ? 'info' : ($appointment->status === 'completed' ? 'success' : 'danger')) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </p>
                <p><strong>Reason:</strong> {{ $appointment->reason ?? '—' }}</p>
                <p><strong>Notes:</strong> {{ $appointment->notes ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Staff</h5>
            </div>
            <div class="card-body">
                <p><strong>Doctor:</strong> {{ $appointment->doctor->name }}</p>
                @if($appointment->nurse)
                    <p><strong>Nurse:</strong> {{ $appointment->nurse->name }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection