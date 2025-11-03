@extends('super-admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Payment Settings</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Hospital</th>
                        <th>Active Payment Gateways</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hospitals as $hospital)
                    <tr>
                        <td>{{ $hospital->name }}</td>
                        <td>{{ $hospital->active_payment_settings_count }}</td>
                        <td>
                            <span class="badge bg-{{ $hospital->is_active ? 'success' : 'danger' }}">
                                {{ $hospital->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('super-admin.payments.show', $hospital) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hospitals found</td>
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