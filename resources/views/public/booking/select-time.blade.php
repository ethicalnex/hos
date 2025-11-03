@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Select Time for Dr. {{ $doctor->name }}</h2>
        <a href="{{ route('booking.doctor', ['hospital' => $hospital->slug, 'service' => request('service_id')]) }}" 
           class="btn btn-outline-secondary">
            ← Back to Doctors
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" min="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="service_id" value="{{ request('service_id') }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Check Availability</button>
                </div>
            </form>
        </div>
    </div>

    @if(empty($availableSlots))
        <div class="alert alert-warning">
            No available slots on {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}. Please select another date.
        </div>
    @else
        <div class="row">
            @foreach($availableSlots as $slot)
            <div class="col-md-3 mb-3">
                <a href="#" 
                   onclick="document.getElementById('time-{{ $slot }}').submit()"
                   class="btn btn-outline-primary w-100">
                    {{ \Carbon\Carbon::parse($slot)->format('g:i A') }}
                </a>
                <form id="time-{{ $slot }}" method="POST" action="{{ route('booking.store', $hospital->slug) }}" class="d-none">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ request('service_id') }}">
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="time" value="{{ $slot }}">
                 
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection