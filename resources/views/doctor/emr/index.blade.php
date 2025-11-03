@extends('layouts.doctor')

@section('page-title', 'EMR Records')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>EMR Records</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('doctor.emr.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus me-2"></i> New EMR Record
                </a>

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
                                    <th>Diagnosis</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->patient->name }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 30) }}</td>
                                    <td>{{ $record->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('doctor.emr.show', $record) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('doctor.emr.edit', $record) }}" class="btn btn-sm btn-outline-secondary">
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
</div>
@endsection