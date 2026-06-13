@extends('layouts.app')
@section('title','Browse Jobs — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="linkedin-grid">
        <aside class="profile-mini">
            <div class="cover-img jobs-cover"></div>
            <div class="p-1 mt-2">
                <h1 class="h5 fw-bold mt-2">Job Search</h1>
                <p class="small text-muted">Live AJAX search — results update as you type.</p>
                <div class="mini-stat">
                    <span class="small text-muted">Approved openings</span>
                    <strong>{{ $jobs->total() }}</strong>
                </div>
                <hr>
                <div class="small text-muted fw-semibold mb-2">Employment type</div>
                @foreach(['Full-time','Part-time','Contract','Internship','Remote'] as $type)
                <div class="trend-row py-2 border-0">
                    <a href="{{ route('jobs.index', ['type' => $type]) }}" class="text-decoration-none small {{ request('type') === $type ? 'text-primary fw-semibold' : 'text-muted' }}">
                        {{ $type }}
                    </a>
                </div>
                @endforeach
            </div>
        </aside>

        <div>
            <div class="toolbar mb-4">
                <div class="position-relative">
                    <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input id="liveSearch" class="form-control ps-5" placeholder="Live search by title…" value="{{ request('q') }}">
                </div>
                <select id="categoryFilter" class="form-select">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                <select id="typeFilter" class="form-select">
                    <option value="">Any type</option>
                    @foreach(['Full-time','Part-time','Contract','Internship','Remote'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div id="ajaxJobs" class="feed-stack mb-2"></div>

            <div class="feed-stack" id="staticJobs">
                @foreach($jobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">{{ $jobs->withQueryString()->links() }}</div>
        </div>

        <aside class="insight-panel sticky-side">
            <h2 class="h6 fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-lightbulb text-primary"></i> Search Tips
            </h2>
            <p class="small text-muted">Try searching for skills like <strong>Laravel</strong>, <strong>UI/UX</strong>, <strong>SQL</strong>, or roles like <strong>Remote</strong>, <strong>Internship</strong>.</p>
            <hr>
            <h3 class="h6 fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart text-primary"></i> Categories
            </h3>
            @foreach($categories as $c)
            <div class="trend-row">
                <a href="{{ route('jobs.index', ['category' => $c->id]) }}" class="text-decoration-none small {{ request('category') == $c->id ? 'fw-bold text-primary' : '' }}" style="color:var(--ink-2)">{{ $c->name }}</a>
                <span class="badge bg-primary-subtle text-primary">{{ $c->jobs_count }}</span>
            </div>
            @endforeach
        </aside>
    </div>
</div>
@endsection
