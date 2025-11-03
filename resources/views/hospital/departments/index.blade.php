@extends('hospital.layouts.app')

@section('title', 'Departments Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Departments Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('hospital.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add Department
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Head Doctor</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td>
                            <strong>{{ $department->name }}</strong>
                        </td>
                        <td>
                            @if($department->headDoctor)
                                {{ $department->headDoctor->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            @if($department->description)
                                {{ Str::limit($department->description, 50) }}
                            @else
                                <span class="text-muted">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                {{ $department->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('hospital.departments.edit', $department) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No departments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection