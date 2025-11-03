@extends('layouts.patient')

@section('page-title', 'My Prescriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Prescriptions</h2>
</div>

@if($prescriptions->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        You have no prescriptions yet.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->doctor->name }}</td>
                    <td>{{ $prescription->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $prescription->status == 'pending' ? 'warning' : ($prescription->status == 'dispensed' ? 'success' : 'danger') }}">
                            {{ ucfirst($prescription->status) }}
                        </span>
                    </td>
                    <td>{{ $prescription->items->count() }}</td>
                    <td>
                        <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $prescriptions->links() }}
@endif
@endsection