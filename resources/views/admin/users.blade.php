@extends('layouts.app')
@section('title','Users — Admin')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1">Manage Users</h1>
            <p class="text-muted small mb-0">{{ $users->total() }} total users</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Dashboard
        </a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'employer' ? 'bg-primary-subtle text-primary' : 'bg-success bg-opacity-10 text-success') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'status-approved' : 'status-rejected' }}">
                                {{ $user->is_active ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form class="d-inline" method="post" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                            @if(auth()->id() !== $user->id)
                            <form class="d-inline ms-1" method="post" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $users->links() }}</div>
</div>
@endsection
