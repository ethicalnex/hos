@extends('layouts.app')

@section('title', 'Booking Confirmed')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-3">Appointment Confirmed!</h2>
                    <p class="text-muted mb-4">
                        Your appointment at <strong>{{ $hospital->name }}</strong> has been successfully booked.
                        @if($payment_method === 'hospital')
                            Please pay at the hospital reception when you arrive.
                        @endif
                    </p>
                    <div class="d-grid gap-2">
                        <!-- ✅ FIXED: Use existing login route -->
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-home me-2"></i>Go to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection