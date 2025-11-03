@extends('layouts.doctor')

@section('page-title', 'Create Prescription')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Prescription</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.prescriptions.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Patient *</label>
                <select name="patient_id" class="form-control" required>
    <option value="">Select Patient</option>
    @foreach($patients as $patient)
        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
    @endforeach
</select>
            </div>
            <div class="mb-3">
                <label class="form-label">Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Valid Until</label>
                <input type="date" name="valid_until" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Medications *</label>
                <div id="medication-list">
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select name="items[0][item_id]" class="form-control medication-item" required>
                                <option value="">Select Medication</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->category }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][dosage]" class="form-control" placeholder="Dosage" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][frequency]" class="form-control" placeholder="Frequency" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][quantity]" class="form-control" min="1" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="items[0][instructions]" class="form-control" placeholder="Instructions">
                        </div>
                    </div>
                </div>
                <button type="button" id="add-medication" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-plus me-1"></i> Add Another Medication
                </button>
            </div>
            <button type="submit" class="btn btn-primary">Create Prescription</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let medicationCount = 1;
    
    document.getElementById('add-medication').addEventListener('click', function() {
        const medicationList = document.getElementById('medication-list');
        const newMedication = document.createElement('div');
        newMedication.className = 'row mb-2';
        newMedication.innerHTML = `
            <div class="col-md-4">
                <select name="items[${medicationCount}][item_id]" class="form-control medication-item" required>
                    <option value="">Select Medication</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->category }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][dosage]" class="form-control" placeholder="Dosage" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][frequency]" class="form-control" placeholder="Frequency" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${medicationCount}][quantity]" class="form-control" min="1" placeholder="Quantity" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${medicationCount}][instructions]" class="form-control" placeholder="Instructions">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-medication">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        medicationList.appendChild(newMedication);
        medicationCount++;
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-medication')) {
            e.target.closest('.row').remove();
        }
    });
});
</script>
@endsection