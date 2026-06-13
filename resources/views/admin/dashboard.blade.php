@extends('layouts.app')
@section('title','Admin Dashboard — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1">Admin Dashboard</h1>
            <p class="text-muted small mb-0">Platform overview and moderation</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-people me-1"></i>Users
            </a>
            <a href="{{ route('admin.categories') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-tags me-1"></i>Categories
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @php $icons=['people','briefcase','send','building']; $i=0; @endphp
        @foreach($stats as $k => $v)
        <div class="col-md-3 col-6">
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-{{ $icons[$i++] ?? 'bar-chart' }}"></i></div>
                <strong>{{ $v }}</strong>
                <span class="metric-label">{{ Str::headline($k) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-briefcase me-2 text-primary"></i>Pending Jobs</span>
                    <span class="badge bg-warning text-dark">{{ $jobs->count() }} pending</span>
                </div>
                @if($jobs->isEmpty())
                <div class="p-4 text-center text-muted small">
                    <i class="bi bi-check-circle fs-2 opacity-30 d-block mb-2"></i>
                    All jobs reviewed!
                </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Job</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @foreach($jobs as $job)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $job->title }}</div>
                                    <div class="small text-muted">{{ $job->company->name }} · {{ $job->employment_type }}</div>
                                </td>
                                <td><span class="badge status-{{ $job->status }}">{{ ucfirst($job->status) }}</span></td>
                                <td class="text-end">
                                    <form class="d-inline" method="post" action="{{ route('admin.jobs.approve', $job) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Approve</button>
                                    </form>
                                    <form class="d-inline ms-1" method="post" action="{{ route('admin.jobs.reject', $job) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg me-1"></i>Reject</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-graph-up me-2 text-primary"></i>Weekly Applications</span>
                </div>
                <div class="card-body">
                    <canvas id="weeklyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
window.weeklyLabels = @json($weekly->keys());
window.weeklyValues = @json($weekly->values());
</script>
@endpush
