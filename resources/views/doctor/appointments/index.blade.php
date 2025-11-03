@extends('layouts.doctor')
@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Patient</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($appointments as $appt)
        <tr>
            <td>{{ $appt->patient->name }}</td>
            <td>{{ $appt->scheduled_time->format('M j, Y g:i A') }}</td>
            <td>
                <span class="badge bg-{{ $appt->status === 'scheduled' ? 'warning' : ($appt->status === 'confirmed' ? 'info' : ($appt->status === 'completed' ? 'success' : 'danger')) }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </td>
            <td>
                @if($appt->status === 'scheduled')
                    <form action="{{ route('doctor.appointments.confirm', $appt) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">Confirm</button>
                    </form>
                @elseif($appt->status === 'confirmed')
                    <form action="{{ route('doctor.appointments.complete', $appt) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">Complete</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection