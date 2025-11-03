@extends('layouts.patient')

@section('page-title', 'My Appointments')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Appointments</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-1"></i> Book New Appointment
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($appointments->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        You don't have any appointments yet. 
        <a href="{{ route('patient.appointments.create') }}" class="alert-link">Book your first appointment</a>.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->doctor->name }}</td>
                    <td>{{ $appointment->scheduled_time->format('M j, Y g:i A') }}</td>
                    <td>{{ $appointment->reason ?? '—' }}</td>
                    <td>
                        @php
                            $statusClass = match($appointment->status) {
                                'scheduled' => 'warning',
                                'confirmed' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'no_show' => 'secondary',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($appointment->status === 'scheduled')
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                            </form>
                        @elseif($appointment->status === 'completed')
                            <a href="{{ route('patient.emr.index') }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-file-medical me-1"></i> View EMR
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $appointments->links() }}
@endif
@endsection