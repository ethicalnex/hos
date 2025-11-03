@extends('layouts.lab-technician')

@section('page-title', 'Lab Technician Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Welcome, {{ auth()->user()->name }}!</h5>
            </div>
            <div class="card-body">
                <p>You are logged in as a lab technician at <strong>{{ auth()->user()->hospital->name }}</strong>.</p>
                
                @php
                    $pendingOrders = \App\Models\LabOrder::where('hospital_id', auth()->user()->hospital_id)
                        ->where('status', 'pending')
                        ->count();
                    $completedToday = \App\Models\LabOrder::where('hospital_id', auth()->user()->hospital_id)
                        ->where('status', 'completed')
                        ->whereDate('updated_at', now())
                        ->count();
                    $totalTests = \App\Models\LabTest::where('hospital_id', auth()->user()->hospital_id)->count();
                @endphp

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    You have <strong>{{ $pendingOrders }}</strong> pending lab orders that need results.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('lab-technician.lab.results.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-flask me-2"></i>View Lab Orders
                </a>
                <a href="{{ route('lab-technician.appointments.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-calendar-check me-2"></i>Today's Appointments
                </a>
                <a href="#" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-file-medical me-2"></i>View Test Catalog
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Today's Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Pending Orders</span>
                    <strong>{{ $pendingOrders }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Completed Today</span>
                    <strong>{{ $completedToday }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Tests</span>
                    <strong>{{ $totalTests }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection