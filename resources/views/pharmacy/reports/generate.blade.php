@extends('layouts.pharmacy')

@section('page-title', 'Generate Pharmacy Report')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Generate Pharmacy Report</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('pharmacy.reports.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Report Type *</label>
                <select name="report_type" class="form-control" required>
                    <option value="">Select Report Type</option>
                    <option value="daily">Daily Report</option>
                    <option value="weekly">Weekly Report</option>
                    <option value="monthly">Monthly Report</option>
                    <option value="inventory">Inventory Report</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date *</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date *</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-file-pdf me-1"></i> Generate Report
            </button>
        </form>
    </div>
</div>
@endsection