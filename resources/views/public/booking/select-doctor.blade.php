@extends('layouts.app')

@section('title', 'Select Doctor')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Select Doctor for {{ $service->name }}</h2>
        <a href="{{ route('booking.service', ['hospital' => $hospital->slug, 'department' => $service->department_id]) }}" 
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Services
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Service:</strong> {{ $service->name }} | 
        <strong>Price:</strong> ₦{{ number_format($service->price, 2) }} | 
        <strong>Duration:</strong> {{ $service->duration }} minutes
    </div>

    @if($doctors->isEmpty())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No doctors available for this service at the moment. Please try again later.
        </div>
    @else
        <div class="row">
            @foreach($doctors as $doctor)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Dr. {{ $doctor->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-stethoscope me-1"></i>
                                    {{ $doctor->specialization ?? 'General Practitioner' }}
                                </p>
                                @if($doctor->license_number)
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>
                                        License: {{ $doctor->license_number }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        
                        @if($doctor->phone)
                            <p class="text-muted mb-3">
                                <i class="fas fa-phone me-1"></i> {{ $doctor->phone }}
                            </p>
                        @endif

                        <div class="mt-auto">
                            <a href="{{ route('booking.time', ['hospital' => $hospital->slug, 'doctor' => $doctor->id]) }}?service_id={{ $service->id }}" 
                               class="btn btn-primary w-100 py-2">
                                <i class="fas fa-clock me-2"></i>Select Time Slot
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection