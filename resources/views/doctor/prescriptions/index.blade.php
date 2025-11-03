@extends('layouts.doctor')

@section('page-title', 'Prescriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Prescriptions</h2>
    <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Prescription
    </a>
</div>

@if($prescriptions->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No prescriptions created yet.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->patient->name }}</td>
                    <td>{{ $prescription->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $prescription->status == 'pending' ? 'warning' : ($prescription->status == 'dispensed' ? 'success' : 'danger') }}">
                            {{ ucfirst($prescription->status) }}
                        </span>
                    </td>
                    <td>{{ $prescription->items->count() }}</td>
                    <td>
                        <a href="{{ route('doctor.prescriptions.show', $prescription) }}" class="btn btn-sm btn-outline-primary">
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