@extends('layouts.pharmacy')

@section('page-title', 'Dispense Prescription #{{ $prescription->id }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Prescription #{{ $prescription->id }}</h5>
                <p class="mb-0">
                    <strong>Patient:</strong> {{ $prescription->patient->name }}<br>
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
                                <th>Available Stock</th>
                                <th>Actions</th>
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
                                <td>{{ $item->item->available_quantity }}</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="items[{{ $item->item_id }}][quantity]" 
                                               value="{{ $item->quantity }}" id="item-{{ $item->item_id }}" checked>
                                        <label class="form-check-label" for="item-{{ $item->item_id }}">
                                            Dispense
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <form method="POST" action="{{ route('pharmacy.dispense.dispense', $prescription) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-box-open me-1"></i> Dispense Prescription
                    </button>
                </form>
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