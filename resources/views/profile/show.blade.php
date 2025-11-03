@extends(auth()->user()->isSuperAdmin() ? 'super-admin.layouts.app' : 'layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" 
                                 alt="Avatar" class="rounded-circle" width="120" height="120">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <span class="text-white fw-bold" style="font-size: 2rem;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    
                    <div class="badge bg-primary mb-2">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </div>
                    
                    @if(auth()->user()->hospital)
                        <p class="text-muted">
                            <i class="fas fa-hospital me-1"></i>
                            {{ auth()->user()->hospital->name }}
                        </p>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Profile Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Full Name:</strong>
                            <p>{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email Address:</strong>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Phone Number:</strong>
                            <p>{{ auth()->user()->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Account Status:</strong>
                            <p>
                                <span class="badge bg-{{ auth()->user()->is_active ? 'success' : 'danger' }}">
                                    {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if(auth()->user()->license_number)
                    <div class="row">
                        <div class="col-md-6">
                            <strong>License Number:</strong>
                            <p>{{ auth()->user()->license_number }}</p>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->specialization)
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Specialization:</strong>
                            <p>{{ auth()->user()->specialization }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Member Since:</strong>
                            <p>{{ auth()->user()->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <p>{{ auth()->user()->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Security</h5>
                </div>
                <div class="card-body">
                    <p>Last password change: 
                        <strong>{{ auth()->user()->updated_at->diffForHumans() }}</strong>
                    </p>
                    
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-1"></i>Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection