@extends('layouts.patient')

@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Personal Information</h5>
            </div>
            <div class="card-body">
                <p><strong>MRN:</strong> {{ $patient->medical_record_number }}</p>
                <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth?->format('F j, Y') }} 
                   ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years)</p>
                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                <p><strong>Phone:</strong> {{ $patient->phone }}</p>
                <p><strong>Email:</strong> {{ $patient->email }}</p>
                <p><strong>Address:</strong> {{ $patient->address ?? 'Not provided' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Medical Information</h5>
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
                <h5>Emergency Contact</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $patient->emergency_contact_name }}</p>
                <p><strong>Phone:</strong> {{ $patient->emergency_contact_phone }}</p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body text-center">
                <a href="{{ route('patient.home') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection