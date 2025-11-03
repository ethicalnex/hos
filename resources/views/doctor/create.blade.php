@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Create Medical Record</h2>
    <form method="POST" action="{{ route('doctor.emr.store') }}">
        @csrf
        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
        <!-- Vital signs fields -->
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="temperature" placeholder="Temperature (°C)" class="form-control">
                <input type="text" name="blood_pressure" placeholder="BP (e.g., 120/80)" class="form-control mt-2">
            </div>
            <div class="col-md-6">
                <textarea name="diagnosis" placeholder="Diagnosis" required class="form-control"></textarea>
                <textarea name="treatment_plan" placeholder="Treatment Plan" required class="form-control mt-2"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>
@endsection