@extends('layouts.doctor')

@section('page-title', 'EMR Record #{{ $record->id }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>EMR Record #{{ $record->id }}</h5>
                <a href="{{ route('doctor.emr.edit', $record) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Patient Information</h6>
                        <p><strong>Name:</strong> {{ $record->patient->name }}</p>
                        <p><strong>MRN:</strong> {{ $record->patient->patient->medical_record_number ?? '—' }}</p>
                        <p><strong>Phone:</strong> {{ $record->patient->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Appointment</h6>
                        @if($record->appointment)
                            <p><strong>Date:</strong> {{ $record->appointment->scheduled_time->format('M j, Y g:i A') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($record->appointment->status) }}</p>
                        @else
                            <p><strong>Appointment:</strong> No appointment</p>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Vital Signs</h6>
                        <p><strong>Temperature:</strong> {{ $record->temperature ?? '—' }}</p>
                        <p><strong>Blood Pressure:</strong> {{ $record->blood_pressure ?? '—' }}</p>
                        <p><strong>Pulse:</strong> {{ $record->pulse ?? '—' }}</p>
                        <p><strong>Respiratory Rate:</strong> {{ $record->respiratory_rate ?? '—' }}</p>
                        <p><strong>Weight:</strong> {{ $record->weight ?? '—' }} kg</p>
                        <p><strong>Height:</strong> {{ $record->height ?? '—' }} cm</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Diagnosis & Treatment</h6>
                        <p><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
                        <p><strong>Treatment Plan:</strong> {{ $record->treatment_plan }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Notes</h6>
                        <p><strong>Doctor Notes:</strong> {{ $record->doctor_notes ?? '—' }}</p>
                        <p><strong>Nurse Notes:</strong> {{ $record->nurse_notes ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Metadata</h6>
                        <p><strong>Created At:</strong> {{ $record->created_at->format('M j, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $record->updated_at->format('M j, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('doctor.emr.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-arrow-left me-2"></i> Back to EMR List
                </a>
                <a href="{{ route('doctor.appointments.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-calendar-check me-2"></i> My Appointments
                </a>
                <a href="{{ route('doctor.lab.orders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-flask me-2"></i> Lab Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection