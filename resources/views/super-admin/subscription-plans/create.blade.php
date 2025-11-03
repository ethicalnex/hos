@extends('super-admin.layouts.app')

@section('title', 'Create Subscription Plan')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-plus-circle me-2"></i>Create Subscription Plan
        </h1>
        <a href="{{ route('super-admin.subscription-plans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.subscription-plans.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plan Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (₦) *</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                               name="price" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trial Days *</label>
                        <input type="number" class="form-control @error('trial_days') is-invalid @enderror" 
                               name="trial_days" value="{{ old('trial_days', 30) }}" required>
                        @error('trial_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Billing Cycle *</label>
                        <select class="form-control @error('billing_cycle') is-invalid @enderror" name="billing_cycle" required>
                            <option value="">Select cycle</option>
                            <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('billing_cycle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Max Staff *</label>
                        <input type="number" class="form-control @error('max_staff') is-invalid @enderror" 
                               name="max_staff" value="{{ old('max_staff', 5) }}" required>
                        @error('max_staff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Max Patients *</label>
                        <input type="number" class="form-control @error('max_patients') is-invalid @enderror" 
                               name="max_patients" value="{{ old('max_patients', 100) }}" required>
                        @error('max_patients')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Max Departments *</label>
                        <input type="number" class="form-control @error('max_departments') is-invalid @enderror" 
                               name="max_departments" value="{{ old('max_departments', 1) }}" required>
                        @error('max_departments')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Features</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[emr]" id="feature-emr" 
                                       {{ old('features.emr') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-emr">
                                    EMR
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[lab]" id="feature-lab" 
                                       {{ old('features.lab') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-lab">
                                    Lab Management
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[pharmacy]" id="feature-pharmacy" 
                                       {{ old('features.pharmacy') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-pharmacy">
                                    Pharmacy
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[billing]" id="feature-billing" 
                                       {{ old('features.billing') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-billing">
                                    Billing & Invoicing
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[appointments]" id="feature-appointments" 
                                       {{ old('features.appointments') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-appointments">
                                    Appointment Booking
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[reports]" id="feature-reports" 
                                       {{ old('features.reports') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-reports">
                                    Reports & Analytics
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[ai]" id="feature-ai" 
                                       {{ old('features.ai') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-ai">
                                    AI Diagnostics
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[mobile_app]" id="feature-mobile_app" 
                                       {{ old('features.mobile_app') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-mobile_app">
                                    Mobile App Access
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[sms]" id="feature-sms" 
                                       {{ old('features.sms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-sms">
                                    SMS Reminders
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[api]" id="feature-api" 
                                       {{ old('features.api') ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature-api">
                                    API Integration
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Plan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection