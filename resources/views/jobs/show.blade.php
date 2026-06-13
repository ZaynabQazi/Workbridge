@extends('layouts.app')
@section('title', $job->title . ' — WorkBridge')
@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}" class="text-decoration-none text-primary">Jobs</a></li>
            <li class="breadcrumb-item active text-muted">{{ Str::limit($job->title, 40) }}</li>
        </ol>
    </nav>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex gap-4 align-items-start mb-4">
                        <div class="company-logo" style="width:72px;height:72px;border-radius:14px;flex:0 0 72px;font-size:26px">
                            @if($job->company->logo)
                                <img src="{{ asset('storage/'.$job->company->logo) }}" alt="{{ $job->company->name }}">
                            @else
                                {{ Str::of($job->company->name)->substr(0,1) }}
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-2 mb-1 align-items-center">
                                <span class="badge bg-primary-subtle text-primary">{{ $job->employment_type }}</span>
                                <span class="badge status-{{ $job->status }}">{{ ucfirst($job->status) }}</span>
                            </div>
                            <h1 class="h3 fw-bold mb-1">{{ $job->title }}</h1>
                            <p class="text-muted mb-0 d-flex flex-wrap gap-3">
                                <span><i class="bi bi-building me-1"></i>{{ $job->company->name }}</span>
                                <span><i class="bi bi-geo-alt me-1"></i>{{ $job->location }}</span>
                                <span><i class="bi bi-calendar3 me-1"></i>Deadline {{ $job->deadline->format('M d, Y') }}</span>
                                @if($job->salary_range)
                                <span><i class="bi bi-cash-stack me-1 text-success"></i>{{ $job->salary_range }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h2 class="h5 fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-text-paragraph text-primary"></i> Job Description
                    </h2>
                    <p style="line-height:1.8;white-space:pre-line;color:var(--ink-2)">{{ $job->description }}</p>

                    <h2 class="h5 fw-bold mb-3 mt-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check2-square text-primary"></i> Requirements
                    </h2>
                    <p style="line-height:1.8;white-space:pre-line;color:var(--ink-2)">{{ $job->requirements }}</p>

                    <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                        <span class="job-meta-chip"><i class="bi bi-tag"></i> {{ $job->category->name }}</span>
                        <span class="job-meta-chip"><i class="bi bi-briefcase"></i> {{ $job->employment_type }}</span>
                        <span class="job-meta-chip"><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-side">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4">Apply for this job</h2>
                    @auth
                        @if(auth()->user()->role === 'candidate')
                        <form method="post" enctype="multipart/form-data" action="{{ route('candidate.apply', $job) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Cover letter <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea class="form-control" name="cover_letter" rows="5" placeholder="Tell the employer why you're a great fit…"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="upload-zone compact w-100">
                                    <i class="bi bi-file-pdf"></i>
                                    <strong>Upload Resume</strong>
                                    <span>PDF only · max 2MB</span>
                                    <input class="d-none file-preview-input" name="resume" type="file" accept="application/pdf">
                                </label>
                                <div class="selected-file small text-muted mt-1"></div>
                            </div>
                            <button class="btn btn-primary w-100" style="height:46px">
                                <i class="bi bi-send me-2"></i>Submit Application
                            </button>
                        </form>
                        <form method="post" action="{{ route('candidate.save', $job) }}" class="mt-2">
                            @csrf
                            <button class="btn btn-outline-primary w-100">
                                <i class="bi bi-bookmark me-2"></i>Save Job
                            </button>
                        </form>
                        @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-person-x fs-2 opacity-50 d-block mb-2"></i>
                            Use a candidate account to apply.
                        </div>
                        @endif
                    @else
                    <div class="text-center py-3">
                        <i class="bi bi-lock fs-2 text-muted opacity-50 d-block mb-3"></i>
                        <p class="text-muted small mb-3">Sign in to apply for this job</p>
                        <a class="btn btn-primary w-100 mb-2" href="{{ route('login') }}">Login to apply</a>
                        <a class="btn btn-outline-primary w-100" href="{{ route('register') }}">Create account</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
