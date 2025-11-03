@extends('layouts.patient')

@section('page-title', 'Lab Report')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Lab Report #{{ $order->id }}</h5>
                <p class="mb-0">
                    <strong>Date:</strong> {{ $order->created_at->format('M j, Y') }}<br>
                    <strong>Doctor:</strong> {{ $order->doctor->name }}
                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Unit</th>
                                <th>Normal Range</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->tests as $orderTest)
                            <tr>
                                <td>{{ $orderTest->test->name }}</td>
                                <td>{{ $orderTest->result_value ?? '—' }}</td>
                                <td>{{ $orderTest->test->unit ?? '—' }}</td>
                                <td>{{ $orderTest->test->normal_range ?? '—' }}</td>
                                <td>
                                    @if($orderTest->result_value)
                                        <span class="badge bg-{{ $orderTest->result_status == 'Normal' ? 'success' : ($orderTest->result_status == 'High' ? 'danger' : 'warning') }}">
                                            {{ $orderTest->result_status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Actions</h6>
            </div>
            <div class="card-body">
                @if($order->status == 'completed')
                    <a href="{{ route('patient.lab.reports.download', $order) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                @endif
                <a href="{{ route('patient.lab.reports.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>
@endsection