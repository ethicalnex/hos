@extends('layouts.patient')

@section('page-title', 'My Medical Records')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Medical Records</h5>
    </div>
    <div class="card-body">
        @if($records->isEmpty())
            <p class="text-muted">No medical records available.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Diagnosis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td>{{ $record->created_at->format('M j, Y') }}</td>
                            <td>{{ $record->doctor->name }}</td>
                            <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#recordModal{{ $record->id }}">
                                    View
                                </a>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="recordModal{{ $record->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Medical Record - {{ $record->created_at->format('M j, Y') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Diagnosis</h6>
                                        <p>{{ $record->diagnosis }}</p>
                                        
                                        <h6 class="mt-3">Treatment Plan</h6>
                                        <p>{{ $record->treatment_plan }}</p>
                                        
                                        @if($record->symptoms)
                                            <h6 class="mt-3">Symptoms</h6>
                                            <p>{{ $record->symptoms }}</p>
                                        @endif
                                        
                                        <h6 class="mt-3">Vital Signs</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Temperature:</strong> {{ $record->temperature ?? '—' }}</p>
                                                <p><strong>Blood Pressure:</strong> {{ $record->blood_pressure ?? '—' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Weight:</strong> {{ $record->weight ?? '—' }}</p>
                                                <p><strong>Height:</strong> {{ $record->height ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $records->links() }}
        @endif
    </div>
</div>
@endsection