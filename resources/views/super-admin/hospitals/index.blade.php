@extends('super-admin.layouts.app')

@section('title', 'Hospitals Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hospitals Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('super-admin.hospitals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add New Hospital
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Email</th>
                        <th>Staff Count</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hospitals as $hospital)
                    <tr>
                        <td>
                            <strong>{{ $hospital->name }}</strong>
                            @if($hospital->city)
                                <br><small class="text-muted">{{ $hospital->city }}, {{ $hospital->state }}</small>
                            @endif
                        </td>
                        <td><code>{{ $hospital->slug }}</code></td>
                        <td>{{ $hospital->email }}</td>
                        <td>
                            <span class="badge bg-info">{{ $hospital->users_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $hospital->is_active ? 'success' : 'danger' }}">
                                {{ $hospital->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $hospital->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('super-admin.hospitals.edit', $hospital) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('super-admin.hospitals.toggle', $hospital) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $hospital->is_active ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $hospital->is_active ? 'times' : 'check' }}"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hospitals found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $hospitals->links() }}
        </div>
    </div>
</div>
@endsection