@extends('super-admin.layouts.app')

@section('title', 'Edit Super Admin Payment Settings')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 fw-bold text-primary">
            <i class="fas fa-edit me-2"></i>Configure {{ ucfirst($gateway) }}
        </h1>
        <a href="{{ route('super-admin.super-admin-payment.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.super-admin-payment.update', $gateway) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Public Key *</label>
                    <input type="text" name="public_key" class="form-control" 
                           value="{{ old('public_key', $setting?->public_key) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Secret Key *</label>
                    <input type="password" name="secret_key" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Webhook Secret</label>
                    <input type="text" name="webhook_secret" class="form-control"
                           value="{{ old('webhook_secret', $setting?->webhook_secret) }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection