@extends('hospital.layouts.app')

@section('title', 'Patients')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patients</h1>
</div>

<!-- Search Form -->
<form action="{{ route('hospital.patients.index') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="q" placeholder="Search by name, MRN, phone, or email..." 
               value="{{ request('q') }}">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
</form>

@if($patients->isEmpty())
    <div class="alert alert-info">
        No patients found. @if(request('q')) Try a different search term. @endif
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>MRN</th>
                    <th>Patient Name</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Blood Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                <tr>
                    <td>{{ $patient->medical_record_number }}</td>
                    <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->date_of_birth?->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($patient->gender) }}</td>
                    <td>{{ $patient->blood_type ?? '—' }}</td>
                    <td>
                        <a href="{{ route('hospital.patients.show', $patient) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $patients->appends(['q' => request('q')])->links() }}
@endif
@endsection