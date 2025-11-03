@extends('hospital.layouts.app')

@section('title', 'Payment Settings')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Payment Gateway Settings</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('hospital.settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Settings
        </a>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Configure your Paystack or Flutterwave test/live keys below. Use <strong>test keys</strong> during development.
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gateway Configuration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital.payment.save') }}">
                    @csrf

                    <!-- Gateway Selection -->
                    <div class="mb-3">
                        <label for="payment_gateway" class="form-label">Payment Gateway *</label>
                        <select class="form-control @error('payment_gateway') is-invalid @enderror" 
                                id="payment_gateway" name="payment_gateway" required>
                            <option value="">Select a gateway</option>
                            <option value="paystack" {{ old('payment_gateway', $paymentSettings->first()?->payment_gateway) == 'paystack' ? 'selected' : '' }}>
                                Paystack
                            </option>
                            <option value="flutterwave" {{ old('payment_gateway', $paymentSettings->first()?->payment_gateway) == 'flutterwave' ? 'selected' : '' }}>
                                Flutterwave
                            </option>
                        </select>
                        @error('payment_gateway')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Public Key -->
                    <div class="mb-3">
                        <label for="public_key" class="form-label">Public Key *</label>
                        <input type="text" 
                               class="form-control @error('public_key') is-invalid @enderror" 
                               id="public_key" 
                               name="public_key" 
                               value="{{ old('public_key') ?? ($paymentSettings->first()?->public_key ?? '') }}" 
                               placeholder="e.g., pk_test_xxx or FLWPUBK_TEST-xxx"
                               required>
                        @error('public_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Paystack:</strong> Starts with <code>pk_test_</code> (test) or <code>pk_live_</code> (live)<br>
                            <strong>Flutterwave:</strong> Found in your dashboard under API Keys
                        </div>
                    </div>

                    <!-- Secret Key -->
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Secret Key *</label>
                        <input type="password" 
                               class="form-control @error('secret_key') is-invalid @enderror" 
                               id="secret_key" 
                               name="secret_key" 
                               value="{{ old('secret_key') }}" 
                               placeholder="••••••••••••••••"
                               required>
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Never share this key. Used only by your server.
                        </div>
                    </div>

                    <!-- Webhook Secret -->
                    <div class="mb-3">
                        <label for="webhook_secret" class="form-label">Webhook Secret</label>
                        <input type="password" 
                               class="form-control" 
                               id="webhook_secret" 
                               name="webhook_secret" 
                               value="{{ old('webhook_secret') }}" 
                               placeholder="Optional, but recommended">
                        <div class="form-text">
                            <strong>Paystack:</strong> Set in Dashboard → Webhooks → Edit → "Add Secret Key"<br>
                            <strong>Flutterwave:</strong> Called "Verify hash" in Webhook settings
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Save Payment Settings
                        </button>
                        <a href="{{ route('hospital.settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Settings Preview -->
        @if($paymentSettings->isNotEmpty())
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Active Payment Settings</h5>
            </div>
            <div class="card-body">
                @foreach($paymentSettings as $setting)
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <span class="badge bg-primary">
                                        {{ ucfirst($setting->payment_gateway) }}
                                    </span>
                                    @if($setting->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </h6>
                                <p class="mb-1"><strong>Public Key:</strong> {{ substr($setting->public_key, 0, 8) }}{{ str_repeat('*', max(0, strlen($setting->public_key) - 8)) }}</p>
                                <p class="mb-1"><strong>Secret Key:</strong> {{ $setting->secret_key ? '••••••••' : 'Not set' }}</p>
                                <p class="mb-0"><strong>Webhook Secret:</strong> {{ $setting->webhook_secret ? '••••••••' : 'Not set' }}</p>
                            </div>
                            <div>
                                <!-- Future: Add "Make Default" or "Deactivate" button -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">How to Find Your Keys</h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Paystack</h6>
                <ol>
                    <li>Go to <a href="https://dashboard.paystack.com" target="_blank">Paystack Dashboard</a></li>
                    <li>Click <strong>Settings → API Keys & Webhooks</strong></li>
                    <li>Copy <strong>Test Secret Key</strong> and <strong>Test Public Key</strong></li>
                    <li>Set a <strong>Webhook URL</strong>: <code>{{ url('/webhooks/paystack') }}</code></li>
                    <li>Enable and set a <strong>Secret Key</strong> for the webhook</li>
                </ol>

                <h6 class="text-primary mt-3">Flutterwave</h6>
                <ol>
                    <li>Go to <a href="https://dashboard.flutterwave.com" target="_blank">Flutterwave Dashboard</a></li>
                    <li>Click <strong>Settings → API</strong></li>
                    <li>Copy <strong>Test Secret Key</strong> and <strong>Test Public Key</strong></li>
                    <li>Go to <strong>Webhooks</strong>, add URL: <code>{{ url('/webhooks/flutterwave') }}</code></li>
                    <li>Set a <strong>Verify hash</strong> (this is your webhook secret)</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection