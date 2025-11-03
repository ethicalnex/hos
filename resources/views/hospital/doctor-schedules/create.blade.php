@extends('hospital.layouts.app')

@section('title', 'Add Doctor Schedule')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Doctor Schedule</h1>
    <a href="{{ route('hospital.doctor-schedules.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Schedules
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('hospital.doctor-schedules.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Doctor *</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Day of Week *</label>
                <select name="day_of_week" class="form-control" required>
                    <option value="">Select Day</option>
                    @foreach($days as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time *</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time *</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Slot Duration (minutes) *</label>
                <select name="slot_duration" class="form-control" required>
                    <option value="15">15 minutes</option>
                    <option value="30" selected>30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">60 minutes</option>
                    <option value="90">90 minutes</option>
                    <option value="120">120 minutes</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Schedule
            </button>
        </form>
    </div>
</div>
@endsection