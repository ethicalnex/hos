@extends('layouts.doctor')

@section('page-title', 'Create Lab Order')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Lab Order</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.lab.orders.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Patient *</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Lab Tests *</label>
                <div class="row">
                    @foreach($tests as $test)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="test_ids[]" value="{{ $test->id }}" id="test-{{ $test->id }}">
                            <label class="form-check-label" for="test-{{ $test->id }}">
                                <strong>{{ $test->name }}</strong><br>
                                <small>{{ $test->category->name }} - ₦{{ number_format($test->price, 2) }}</small>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Lab Order</button>
        </form>
    </div>
</div>
@endsection