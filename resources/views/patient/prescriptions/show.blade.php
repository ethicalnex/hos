@extends('layouts.patient')

@section('page-title', 'Prescription #{{ $prescription->id }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Prescription #{{ $prescription->id }}</h5>
                <p class="mb-0">
                    <strong>Doctor:</strong> {{ $prescription->doctor->name }}<br>
                    <strong>Created:</strong> {{ $prescription->created_at->format('M j, Y') }}
                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Quantity</th>
                                <th>Instructions</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                            <tr>
                                <td>{{ $item->item->name }}</td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->frequency }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->instructions ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_dispensed ? 'success' : 'secondary' }}">
                                        {{ $item->is_dispensed ? 'Dispensed' : 'Pending' }}
                                    </span>
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
                <h6>Prescription Details</h6>
            </div>
            <div class="card-body">
                <p><strong>Diagnosis:</strong> {{ $prescription->diagnosis ?? '—' }}</p>
                <p><strong>Valid Until:</strong> {{ $prescription->valid_until ?? '—' }}</p>
                <p><strong>Notes:</strong> {{ $prescription->notes ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection