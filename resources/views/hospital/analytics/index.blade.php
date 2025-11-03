@extends('hospital.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">Analytics Dashboard</h1>
        <a href="{{ route('hospital.analytics.reports') }}" class="btn btn-primary">
            <i class="fas fa-file-alt me-1"></i> View Reports
        </a>
    </div>

    <!-- Key Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Total Patients</h5>
                    <h2>{{ number_format($stats['total_patients']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Total Staff</h5>
                    <h2>{{ number_format($stats['total_staff']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Total Appointments</h5>
                    <h2>{{ number_format($stats['total_appointments']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <h2>₦{{ number_format($stats['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Metrics -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Today's Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>New Patients:</strong> {{ number_format($metric->new_patients) }}</p>
                            <p><strong>Total Patients:</strong> {{ number_format($metric->total_patients) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Appointments:</strong> {{ number_format($metric->total_appointments) }}</p>
                            <p><strong>Completed:</strong> {{ number_format($metric->completed_appointments) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Revenue:</strong> ₦{{ number_format($metric->total_revenue, 2) }}</p>
                            <p><strong>Staff:</strong> {{ number_format($metric->active_staff) }} / {{ number_format($metric->total_staff) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Generate Report</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.analytics.generate-report') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Report Type</label>
                            <select name="type" class="form-control" required>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-file-alt me-1"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Last 7 Days -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h3>Last 7 Days</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patients</th>
                            <th>Appointments</th>
                            <th>Revenue (₦)</th>
                            <th>Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($last7Days as $day)
                        <tr>
                            <td>{{ $day->date->format('M j') }}</td>
                            <td>{{ number_format($day->new_patients) }} / {{ number_format($day->total_patients) }}</td>
                            <td>{{ number_format($day->total_appointments) }} ({{ number_format($day->completed_appointments) }} completed)</td>
                            <td>{{ number_format($day->total_revenue, 2) }}</td>
                            <td>{{ number_format($day->active_staff) }} / {{ number_format($day->total_staff) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection