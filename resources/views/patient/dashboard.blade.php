@extends('layouts.patient')

@section('page-title', 'Patient Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Welcome, {{ auth()->user()->name }}!</h5>
            </div>
            <div class="card-body">
                <p>You are logged in as a patient at <strong>{{ auth()->user()->hospital->name }}</strong>.</p>
                <p><strong>MRN:</strong> {{ auth()->user()->patient->medical_record_number }}</p>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Your next appointment is on <strong>Oct 25, 2025 at 10:00 AM</strong> with Dr. Smith.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="card-body">
    <a href="{{ route('patient.appointments.index') }}" class="btn btn-primary w-100 mb-2">
        <i class="fas fa-calendar-check me-2"></i>My Appointments
    </a>
    <a href="{{ route('patient.appointments.create') }}" class="btn btn-outline-primary w-100 mb-2">
        <i class="fas fa-calendar-plus me-2"></i>Book New Appointment
    </a>
    <a href="{{ route('patient.lab.reports.index') }}" class="btn btn-outline-primary w-100 mb-2">
        <i class="fas fa-file-medical me-2"></i>Lab Reports
    </a>
    <a href="{{ route('patient.emr.index') }}" class="btn btn-outline-secondary w-100">
        <i class="fas fa-file-medical me-2"></i>View Medical Records
    </a>
</div>
                <a href="#" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-file-invoice me-2"></i>View Billing History
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Medical Summary</h6>
            </div>
            <div class="card-body">
                <p><strong>Blood Type:</strong> {{ auth()->user()->patient->blood_type ?? 'Not specified' }}</p>
                <p><strong>Allergies:</strong> {{ auth()->user()->patient->allergies ?? 'None reported' }}</p>
                <p><strong>Insurance:</strong> {{ auth()->user()->patient->insurance_provider ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection