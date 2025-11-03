@extends('hospital.layouts.app')

@section('title', 'Doctor Schedules')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Schedules</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('hospital.doctor-schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Schedule
        </a>
    </div>
</div>

<div class="row">
@foreach($doctors as $doctor)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Dr. {{ $doctor->name }}</h5>
            </div>
            <div class="card-body">
                @if($doctor->schedules->isEmpty())
                    <p class="text-muted">No schedule set</p>
                @else
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->schedules as $schedule)
                            <tr>
                                <td>{{ ucfirst($schedule->day_of_week) }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</td>
                                <td>{{ $schedule->slot_duration }} min</td>
                                <td>
                                    <span class="badge bg-{{ $schedule->is_available ? 'success' : 'secondary' }}">
                                        {{ $schedule->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('hospital.doctor-schedules.toggle', $schedule) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $schedule->is_available ? 'danger' : 'success' }}">
                                            {{ $schedule->is_available ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('hospital.doctor-schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this schedule?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>
@endsection