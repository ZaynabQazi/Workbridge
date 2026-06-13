@extends('layouts.app')
@section('title','Categories — Admin')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1">Job Categories</h1>
            <p class="text-muted small mb-0">{{ $categories->count() }} categories</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Category</div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category name</label>
                            <input class="form-control" name="name" placeholder="e.g. Software Engineering" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Short description…"></textarea>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-plus me-2"></i>Add category
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><i class="bi bi-list-ul me-2 text-primary"></i>All Categories</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>Jobs</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $cat->name }}</div>
                                    @if($cat->description)
                                    <div class="small text-muted">{{ Str::limit($cat->description, 60) }}</div>
                                    @endif
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $cat->jobs_count }}</span></td>
                                <td><span class="badge {{ $cat->is_active ? 'status-approved' : 'status-rejected' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
