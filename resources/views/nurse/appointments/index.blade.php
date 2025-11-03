@extends('layouts.nurse')

@section('page-title', 'Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Today's Appointments</h2>
</div>

@if($appointments->isEmpty())
    <div class="alert alert-info">No appointments scheduled for today.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                <tr>
                    <td>{{ $appt->patient->name }}</td>
                    <td>{{ $appt->doctor->name }}</td>
                    <td>{{ $appt->scheduled_time->format('g:i A') }}</td>
                    <td>
                        <span class="badge bg-{{ $appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger')) }}">
                            {{ ucfirst($appt->status) }}
                        </span>
                    </td>
                    <td>{{ $appt->reason ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $appointments->links() }}
@endif
@endsection