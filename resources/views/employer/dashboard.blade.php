@extends('layouts.app')
@section('title','Employer Dashboard — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1">Employer Dashboard</h1>
            <p class="text-muted small mb-0">Manage your postings and review applicants</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('employer.company') }}">
                <i class="bi bi-building me-1"></i>Company
            </a>
            <a class="btn btn-primary" href="{{ route('employer.jobs.create') }}">
                <i class="bi bi-plus-lg me-1"></i>Post Job
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @php $icons = ['briefcase','people','hourglass-split','check2-circle']; $i=0; @endphp
        @foreach($stats as $k => $v)
        <div class="col-md-3 col-6">
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-{{ $icons[$i++] ?? 'bar-chart' }}"></i></div>
                <strong>{{ $v }}</strong>
                <span class="metric-label">{{ Str::title(str_replace('_',' ',$k)) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <span><i class="bi bi-briefcase me-2 text-primary"></i>Your Job Postings</span>
        </div>
        @if($jobs->isEmpty())
        <div class="p-5 text-center text-muted">
            <i class="bi bi-briefcase fs-1 opacity-30 d-block mb-3"></i>
            <p>No jobs posted yet.</p>
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary btn-sm">Post your first job</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Job title</th>
                        <th>Status</th>
                        <th>Applicants</th>
                        <th>Deadline</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $job->title }}</div>
                            <div class="small text-muted">{{ $job->employment_type }} · {{ $job->location }}</div>
                        </td>
                        <td><span class="badge status-{{ $job->status }}">{{ ucfirst($job->status) }}</span></td>
                        <td>
                            <span class="fw-semibold">{{ $job->applications_count }}</span>
                            <span class="text-muted small"> applied</span>
                        </td>
                        <td class="text-muted small">{{ $job->deadline->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end flex-wrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('employer.jobs.applicants', $job) }}">
                                    <i class="bi bi-people"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('employer.jobs.edit', $job) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('employer.jobs.toggle', $job) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning" title="{{ $job->status === 'closed' ? 'Reopen' : 'Close' }}">
                                        <i class="bi bi-{{ $job->status === 'closed' ? 'play-circle' : 'pause-circle' }}"></i>
                                    </button>
                                </form>
                                <form method="post" action="{{ route('employer.jobs.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Delete this job?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
