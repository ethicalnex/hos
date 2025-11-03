@extends('hospital.layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">All Appointments</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appt)
            <tr>
                <td>{{ $appt->patient->name }}</td>
                <td>{{ $appt->doctor->name }}</td>
                <td>{{ $appt->scheduled_time->format('M j, Y g:i A') }}</td>
                <td>
                    <span class="badge bg-{{ $appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger')) }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('hospital.appointments.show', $appt) }}" class="btn btn-sm btn-outline-info">
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $appointments->links() }}
@endsection