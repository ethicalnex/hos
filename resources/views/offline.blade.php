@extends('layouts.app')

@section('title', 'Offline')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2><i class="fas fa-wifi-slash me-2"></i> You're Offline</h2>
                    <p>Looks like you’re not connected to the internet.</p>
                    <p>Some features may not work until you reconnect.</p>
                    <a href="javascript:location.reload()" class="btn btn-primary">
                        <i class="fas fa-sync me-1"></i> Try Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection