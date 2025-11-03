@extends('hospital.layouts.app')

@section('title', 'Patient Profile - ' . $patient->first_name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient Profile</h1>
    <a href="{{ route('hospital.patients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Patients
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <p><strong>MRN:</strong> {{ $patient->medical_record_number }}</p>
                <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth?->format('F j, Y') }} ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years)</p>
                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                <p><strong>Phone:</strong> {{ $patient->phone }}</p>
                <p><strong>Email:</strong> {{ $patient->email }}</p>
                <p><strong>Address:</strong> {{ $patient->address ?? '—' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Medical Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Blood Type:</strong> {{ $patient->blood_type ?? 'Not specified' }}</p>
                <p><strong>Allergies:</strong> {{ $patient->allergies ?? 'None reported' }}</p>
                <p><strong>Insurance Provider:</strong> {{ $patient->insurance_provider ?? '—' }}</p>
                <p><strong>Insurance Number:</strong> {{ $patient->insurance_number ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Emergency Contact</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $patient->emergency_contact_name }}</p>
                <p><strong>Phone:</strong> {{ $patient->emergency_contact_phone }}</p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <!-- Future: Edit, Add Appointment, View Medical History -->
                <a href="{{ route('hospital.patients.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="fas fa-list me-1"></i> All Patients
                </a>
            </div>
        </div>
    </div>
</div>
@endsection