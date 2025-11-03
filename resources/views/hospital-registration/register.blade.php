@extends('layouts.app')

@section('title', 'Register Your Hospital')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Register Your Hospital</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Hospital Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug (URL) *</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Email *</label>
                            <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Password *</label>
                            <input type="password" name="admin_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subscription Plan *</label>
                            <select name="subscription_plan_id" class="form-control" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ₦{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}
                                        @if($plan->trial_days > 0)
                                            ({{ $plan->trial_days }} days free trial)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_paystack" value="paystack" checked>
                                <label class="form-check-label" for="payment_paystack">
                                    <i class="fab fa-paystack me-2"></i>Paystack
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_flutterwave" value="flutterwave">
                                <label class="form-check-label" for="payment_flutterwave">
                                    <i class="fab fa-cc-visa me-2"></i>Flutterwave
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-lock me-2"></i>Register & Pay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection