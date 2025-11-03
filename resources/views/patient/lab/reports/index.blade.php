@extends('layouts.patient')

@section('page-title', 'Lab Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Reports</h2>
</div>

@if($orders->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No lab reports available yet.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Report #</th>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Tests</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('M j, Y') }}</td>
                    <td>{{ $order->doctor->name }}</td>
                    <td>{{ $order->tests->count() }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        @if($order->status == 'completed')
                            <a href="{{ route('patient.lab.reports.show', $order) }}" class="btn btn-sm btn-primary">
                                View Report
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif
@endsection