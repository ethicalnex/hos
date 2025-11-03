@extends('layouts.lab-technician')

@section('page-title', 'Today\'s Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Today's Appointments</h2>
</div>

@if($appointments->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No appointments scheduled for today.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->name }}</td>
                    <td>{{ $appt->doctor->name }}</td>
                    <td>{{ $appt->scheduled_time->format('g:i A') }}</td>
                    <td>{{ $appt->service?->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : 'success') }}">
                            {{ ucfirst($appt->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('lab-technician.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $appointments->links() }}
@endif
@endsection