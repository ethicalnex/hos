@extends('hospital.layouts.app')

@section('title', 'EMR System')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h1 class="h2">EMR System</h1>
        <a href="{{ route('hospital.emr.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Record
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($records->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>No EMR records found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                            <tr>
                                <td>{{ $record->patient->name }}</td>
                                <td>{{ $record->doctor->name }}</td>
                                <td>{{ $record->diagnosis ?? '—' }}</td>
                                <td>{{ $record->created_at->format('M j, Y') }}</td>
                                <td>
                                    <a href="{{ route('hospital.emr.show', $record) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('hospital.emr.edit', $record) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $records->links() }}
            @endif
        </div>
    </div>
</div>
@endsection