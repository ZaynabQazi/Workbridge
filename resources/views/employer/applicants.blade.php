@extends('layouts.app')
@section('title','Applicants — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active">Applicants</li>
                </ol>
            </nav>
            <h1 class="mb-0">{{ $job->title }}</h1>
            <p class="text-muted small">{{ $job->applications->count() }} total applicants</p>
        </div>
        <a href="{{ route('employer.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <span><i class="bi bi-people me-2 text-primary"></i>Applications</span>
        </div>
        @if($job->applications->isEmpty())
        <div class="p-5 text-center text-muted">
            <i class="bi bi-people fs-1 opacity-30 d-block mb-3"></i>
            <p>No applications yet for this job.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th>Resume</th>
                        <th>Update status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($job->applications as $app)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $app->candidate->name }}</div>
                            <div class="small text-muted">{{ $app->candidate->email }}</div>
                        </td>
                        <td class="text-muted small">{{ $app->created_at->format('M d, Y') }}</td>
                        <td><span class="badge status-{{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
                        <td>
                            @if($app->resume_path)
                            <a href="{{ asset('storage/'.$app->resume_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-pdf me-1"></i>View
                            </a>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <form method="post" action="{{ route('employer.applications.status', $app) }}" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:130px">
                                    @foreach(['pending','reviewed','shortlisted','rejected'] as $s)
                                    <option value="{{ $s }}" {{ $app->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
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
@endsection
