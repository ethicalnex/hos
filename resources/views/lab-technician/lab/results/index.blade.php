@extends('layouts.lab-technician')

@section('page-title', 'Lab Results')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Lab Results</h2>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Patient</th>
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
                <td>{{ $order->patient->name }}</td>
                <td>{{ $order->doctor->name }}</td>
                <td>{{ $order->tests->count() }}</td>
                <td>
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'in_progress' ? 'info' : 'success') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    @if($order->status != 'completed')
                        <a href="{{ route('lab-technician.lab.results.create', $order) }}" class="btn btn-sm btn-primary">
                            Enter Results
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $orders->links() }}
@endsection