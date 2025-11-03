@extends('layouts.pharmacy')

@section('page-title', 'Prescriptions to Dispense')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Prescriptions to Dispense</h2>
</div>

@if($prescriptions->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        No pending prescriptions to dispense.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->patient->name }}</td>
                    <td>{{ $prescription->doctor->name }}</td>
                    <td>{{ $prescription->created_at->format('M j, Y') }}</td>
                    <td>{{ $prescription->items->count() }}</td>
                    <td>
                        <a href="{{ route('pharmacy.dispense.show', $prescription) }}" class="btn btn-sm btn-outline-primary">
                            Dispense
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