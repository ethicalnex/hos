@extends('hospital.layouts.app')

@section('title', 'Hospital Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hospital Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('hospital.staff.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-user-plus me-1"></i> Add Staff
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Staff</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_staff'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Patients</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_patients'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Doctors</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_doctors'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Today's Appointments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_appointments'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Hospital Overview</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>Welcome to your Hospital Dashboard!</h6>
                    <p class="mb-0">Manage your hospital operations, staff, patients, and appointments from this centralized dashboard.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Quick Stats:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Hospital:</strong> {{ auth()->user()->hospital->name }}</li>
                            <li><strong>Email:</strong> {{ auth()->user()->hospital->email }}</li>
                            <li><strong>Location:</strong> {{ auth()->user()->hospital->city }}, {{ auth()->user()->hospital->state }}</li>
                            <li><strong>Subscription:</strong> 
                                @if(auth()->user()->hospital->isSubscribed())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Trial</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Staff Distribution:</h6>
                        @if(isset($staffByRole) && $staffByRole->count() > 0)
                            @foreach($staffByRole as $role => $count)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-capitalize">{{ str_replace('_', ' ', $role) }}</span>
                                <span class="badge bg-primary">{{ $count }}</span>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">No staff data available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('hospital.staff.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Add Staff Member
                    </a>
                    <a href="{{ route('hospital.staff.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-1"></i> Manage Staff
                    </a>
                    <a href="{{ route('hospital.settings.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cog me-1"></i> Hospital Settings
                    </a>
                    <a href="{{ route('hospital.payment.settings') }}" class="btn btn-outline-info">
                        <i class="fas fa-credit-card me-1"></i> Payment Settings
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-0">No recent activity</p>
                <div class="text-center mt-2">
                    <small class="text-muted">Activity tracking will be available soon</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection