<!-- @extends('layouts.app') -->
@extends('layouts.patient')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $department->name }} Services</h2>
        <a href="{{ route('booking.department', $hospital->slug) }}" class="btn btn-outline-secondary">
            ← Back to Departments
        </a>
    </div>

    <div class="row">
        @foreach($services as $service)
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5>{{ $service->name }}</h5>
                    <p class="text-muted">{{ $service->description ?? 'No description' }}</p>
                    <div class="mt-auto">
                        <p class="h5 text-primary">₦{{ number_format($service->price, 2) }}</p>
                        <a href="{{ route('booking.doctor', ['hospital' => $hospital->slug, 'service' => $service->id]) }}" 
   class="btn btn-primary w-100">Select Doctor</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection