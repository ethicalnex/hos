@extends('layouts.patient')
@section('page-title', 'Book Appointment')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Book New Appointment</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('patient.appointments.store') }}">
            @csrf
            <div class="mb-3">
                <label>Doctor *</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Select a doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Appointment Date & Time *</label>
                <input type="datetime-local" name="scheduled_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Reason for Visit</label>
                <textarea name="reason" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
    </div>
</div>
@endsection