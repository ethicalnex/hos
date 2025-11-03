@extends('hospital.layouts.app')

@section('title', 'Create Medical Record')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Create Medical Record</h2>
    <a href="{{ route('hospital.emr.index') }}" class="btn btn-secondary">← Back to EMR</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.emr.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Patient *</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} (MRN: {{ $patient->patient->medical_record_number ?? '—' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Appointment</label>
                        <select name="appointment_id" class="form-control">
                            <option value="">No Appointment</option>
                            @foreach(\App\Models\Appointment::where('patient_id', old('patient_id'))->where('status', 'confirmed')->get() as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->scheduled_time->format('M j, Y g:i A') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Symptoms</label>
                        <textarea class="form-control" name="symptoms" rows="3">{{ old('symptoms') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Temperature (°C)</label>
                            <input type="text" class="form-control" name="temperature" value="{{ old('temperature') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Pressure</label>
                            <input type="text" class="form-control" name="blood_pressure" value="{{ old('blood_pressure') }}" placeholder="e.g., 120/80">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pulse (BPM)</label>
                            <input type="text" class="form-control" name="pulse" value="{{ old('pulse') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Respiratory Rate</label>
                            <input type="text" class="form-control" name="respiratory_rate" value="{{ old('respiratory_rate') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="text" class="form-control" name="weight" value="{{ old('weight') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="text" class="form-control" name="height" value="{{ old('height') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Diagnosis *</label>
                        <textarea class="form-control" name="diagnosis" rows="4" required>{{ old('diagnosis') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treatment Plan *</label>
                        <textarea class="form-control" name="treatment_plan" rows="4" required>{{ old('treatment_plan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Doctor Notes</label>
                        <textarea class="form-control" name="doctor_notes" rows="3">{{ old('doctor_notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nurse Notes</label>
                        <textarea class="form-control" name="nurse_notes" rows="3">{{ old('nurse_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Medical Record</button>
        </form>
    </div>
</div>
@endsection