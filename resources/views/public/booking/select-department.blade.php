@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Book Appointment at {{ $hospital->name }}</h2>
    <div class="row">
        @foreach($departments as $dept)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $dept->name }}</h5>
                    <a href="{{ route('booking.service', ['hospital' => $hospital->slug, 'department' => $dept]) }}" 
                       class="btn btn-primary">Select</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection