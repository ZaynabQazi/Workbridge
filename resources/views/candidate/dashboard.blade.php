@extends('layouts.app')
@section('title','Candidate Dashboard — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1">My Dashboard</h1>
            <p class="text-muted small mb-0">Welcome back, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <a class="btn btn-primary" href="{{ route('candidate.analyzer') }}">
            <i class="bi bi-robot me-2"></i>AI Resume Analyzer
        </a>
    </div>

    <div class="row g-3 mb-4">
        @php
            $icons = ['briefcase', 'bookmark', 'clock-history', 'check-circle'];
            $i = 0;
        @endphp
        @foreach($stats as $k => $v)
        <div class="col-md-3 col-6">
            <div class="metric">
                <div class="metric-icon"><i class="bi bi-{{ $icons[$i++] ?? 'bar-chart' }}"></i></div>
                <strong>{{ $v }}</strong>
                <span class="metric-label">{{ Str::title(str_replace('_', ' ', $k)) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-briefcase me-2 text-primary"></i>My Applications</span>
                    <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-primary">Browse more jobs</a>
                </div>
                @if($applications->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-briefcase fs-1 opacity-30 d-block mb-3"></i>
                    <p>You haven't applied to any jobs yet.</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">Find jobs</a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Job</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                            <tr>
                                <td>
                                    <a href="{{ route('jobs.show', $app->job) }}" class="text-decoration-none fw-semibold text-dark">{{ $app->job->title }}</a>
                                </td>
                                <td class="text-muted">{{ $app->job->company->name }}</td>
                                <td>
                                    <span class="badge status-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                                </td>
                                <td class="text-muted small">{{ $app->created_at->diffForHumans() }}</td>
                                <td>
                                    <form method="post" action="{{ route('candidate.withdraw', $app) }}" onsubmit="return confirm('Withdraw this application?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Withdraw</button>
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

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span><i class="bi bi-bell me-2 text-primary"></i>Notifications</span>
                </div>
                @if($notifications->isEmpty())
                <div class="p-4 text-center text-muted small">
                    <i class="bi bi-bell-slash fs-2 opacity-30 d-block mb-2"></i>
                    No notifications yet
                </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach($notifications as $n)
                    <div class="list-group-item px-4 py-3">
                        <strong class="small d-block">{{ $n->title }}</strong>
                        <p class="small mb-0 text-muted">{{ $n->message }}</p>
                        <span class="text-muted" style="font-size:11px">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3">Quick links</h3>
                    <div class="d-grid gap-2">
                        <a href="{{ route('candidate.profile') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="bi bi-person me-2"></i>Edit profile
                        </a>
                        <a href="{{ route('candidate.analyzer') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="bi bi-robot me-2"></i>Resume analyzer
                        </a>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="bi bi-search me-2"></i>Browse jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
