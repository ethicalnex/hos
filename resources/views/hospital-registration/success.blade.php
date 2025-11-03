@extends('layouts.app')

@section('title', 'Hospital Registration Success')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Hospital Registration Successful!</h4>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success">
                        <h5>Your hospital has been successfully registered!</h5>
                        <p>You can now log in as a hospital administrator.</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection