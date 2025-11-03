@extends('hospital.layouts.app')

@section('title', 'Lab Tests')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lab Tests</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('hospital.lab.tests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Test
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price (₦)</th>
                <th>Unit</th>
                <th>Normal Range</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tests as $test)
            <tr>
                <td>{{ $test->name }}</td>
                <td>{{ $test->category->name }}</td>
                <td>{{ number_format($test->price, 2) }}</td>
                <td>{{ $test->unit ?? '—' }}</td>
                <td>{{ $test->normal_range ?? '—' }}</td>
                <td>
                    <span class="badge bg-{{ $test->is_active ? 'success' : 'secondary' }}">
                        {{ $test->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('hospital.lab.tests.edit', $test) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $tests->links() }}
@endsection