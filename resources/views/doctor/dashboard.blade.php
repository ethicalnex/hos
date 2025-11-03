@extends('layouts.doctor')

@section('page-title', 'Doctor Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Welcome, Dr. {{ auth()->user()->name }}!</h5>
            </div>
            <div class="card-body">
                <p>You are logged in as a doctor at <strong>{{ auth()->user()->hospital->name }}</strong>.</p>

                @php
                    $today = \Carbon\Carbon::today();
                    $appointments = \App\Models\Appointment::where('doctor_id', auth()->id())
                        ->whereDate('scheduled_time', $today)
                        ->with(['patient' => function ($query) {
                            $query->with('patient');
                        }])
                        ->orderBy('scheduled_time')
                        ->get();
                @endphp

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Today you have <strong>{{ $appointments->count() }} appointment{{ $appointments->count() == 1 ? '' : 's' }}</strong>. Don't forget to complete EMR records after each visit.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('doctor.appointments.index') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i>My Appointments
                    </a>
                    <a href="{{ route('doctor.lab.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-flask me-2"></i>Lab Orders
                    </a>
                    <a href="#" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar-alt me-2"></i>Calendar View
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Today's Schedule</h6>
            </div>
            <div class="card-body">
                @if($appointments->isEmpty())
                    <p class="text-muted">No appointments today.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($appointments as $appointment)
                            <li class="list-group-item">
                                {{ $appointment->scheduled_time->format('g:i A') }} - 
                                {{ $appointment->patient->name }} 
                                (MRN: {{ $appointment->patient->patient->medical_record_number ?? '—' }})
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection