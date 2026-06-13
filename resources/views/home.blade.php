@extends('layouts.app')
@section('title','WorkBridge — Find Your Next Opportunity')
@section('content')

{{-- HERO --}}
<section class="hero hero-photo">
    <div class="container py-5" style="position:relative;z-index:2">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-badge mb-3 d-inline-flex">
                    <i class="bi bi-lightning-charge-fill"></i> AI-Powered Job Matching
                </span>
                <h1 class="display-5 fw-bold mb-3">
                    Bridge the gap<br>between talent<br>
                    <span class="accent-word">and opportunity.</span>
                </h1>
                <p class="lead mb-4">Upload your CV, build a professional profile, and let AI match your skills to real openings — instantly.</p>
                <form class="hero-search" action="{{ route('jobs.index') }}">
                    <div class="hero-search-box">
                        <i class="bi bi-search"></i>
                        <input name="q" placeholder="Job title, skill, or company">
                    </div>
                    <div class="hero-search-box">
                        <i class="bi bi-geo-alt"></i>
                        <input name="location" placeholder="City or Remote">
                    </div>
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </form>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    @foreach(['Laravel','UI Designer','Remote','Full-time','Internship'] as $tag)
                    <a href="{{ route('jobs.index', ['q' => $tag]) }}" class="hero-badge" style="font-size:11px;text-decoration:none">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5">
                <div class="social-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-xl">wb</div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">Live network snapshot</h5>
                            <span class="small" style="color:#94a3b8">Updated from live database</span>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach($stats as $label => $value)
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:16px">
                                <div style="font-size:28px;font-weight:800;color:#a5b4fc;font-family:'DM Sans',sans-serif">{{ $value }}</div>
                                <div style="font-size:12px;color:#94a3b8;margin-top:2px">{{ str_replace('_',' ',Str::title($label)) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MAIN CONTENT --}}
<section class="container py-5">
    <div class="linkedin-grid">
        {{-- Left sidebar --}}
        <aside class="profile-mini">
            <div class="cover-img"></div>
            <div class="avatar-overlap">wb</div>
            <div class="p-1 mt-2">
                <h2 class="h5 fw-bold mt-2">Professional Profiles</h2>
                <p class="small text-muted mt-1">Add photo, skills, experience, education, and CV. Employers add branding and logos.</p>
                <a class="btn btn-primary btn-sm w-100 mt-2" href="{{ route('register') }}">
                    <i class="bi bi-person-plus me-1"></i>Join WorkBridge
                </a>
                <div class="mini-stat">
                    <span class="small text-muted">Open roles</span>
                    <strong>{{ $stats['active_jobs'] ?? 0 }}</strong>
                </div>
            </div>
        </aside>

        {{-- Feed --}}
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title h4 mb-0">Featured Jobs</h2>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-sm">
                    View all <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="feed-stack">
                @forelse($featuredJobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-briefcase" style="font-size:2.5rem;opacity:.3"></i>
                        <p class="mt-3">No approved jobs yet. Login as admin to approve seeded jobs.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Right sidebar --}}
        <aside class="insight-panel sticky-side">
            <h3 class="h6 fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-fill text-primary"></i> Top Categories
            </h3>
            @foreach($categories->take(6) as $category)
            <div class="trend-row">
                <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="text-decoration-none" style="color:var(--ink-2)">
                    {{ $category->name }}
                </a>
                <span class="badge bg-primary-subtle text-primary">{{ $category->jobs_count }}</span>
            </div>
            @endforeach
            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-sm w-100 mt-3">Browse all jobs</a>
        </aside>
    </div>
</section>

{{-- CATEGORIES --}}
<section style="background:var(--surface-2);padding:48px 0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="text-primary fw-semibold small mb-1 text-uppercase letter-spacing-1">Explore by field</p>
                <h2 class="section-title h3 mb-0">Browse Categories</h2>
            </div>
        </div>
        <div class="row g-3">
            @foreach($categories as $category)
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="card h-100 text-decoration-none" style="border-radius:12px">
                    <div class="card-body p-4">
                        <div style="width:44px;height:44px;border-radius:10px;background:var(--primary-light);display:grid;place-items:center;margin-bottom:14px">
                            <i class="bi bi-briefcase text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary mb-2">{{ $category->jobs_count }} jobs</span>
                        <h3 class="h6 fw-bold mb-1 text-dark">{{ $category->name }}</h3>
                        @if($category->description)
                        <p class="small text-muted mb-0">{{ Str::limit($category->description, 60) }}</p>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="container py-5">
    <div class="text-center mb-5">
        <p class="text-primary fw-semibold small text-uppercase mb-1">Simple process</p>
        <h2 class="section-title h3">How WorkBridge works</h2>
    </div>
    <div class="row g-4 text-center">
        @foreach([
            ['bi-person-plus','Create a profile','Add your experience, skills, and upload your CV to stand out.'],
            ['bi-robot','AI matching','Our resume analyzer scores your fit against real job postings.'],
            ['bi-send-check','Apply with ease','One-click apply with your stored resume and cover letter.'],
            ['bi-handshake','Get hired','Employers review, shortlist, and connect directly.']
        ] as $i => $step)
        <div class="col-md-3 col-sm-6">
            <div class="card p-4 h-100 text-center">
                <div style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);display:grid;place-items:center;margin:0 auto 16px;font-size:24px;color:var(--primary)">
                    <i class="bi {{ $step[0] }}"></i>
                </div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">Step {{ $i+1 }}</div>
                <h3 class="h6 fw-bold mb-2">{{ $step[1] }}</h3>
                <p class="small text-muted mb-0">{{ $step[2] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="container pb-5">
    <div class="cta-section">
        <div class="row align-items-center g-4" style="position:relative;z-index:1">
            <div class="col-lg-8">
                <p class="text-uppercase small fw-bold mb-2" style="color:#818cf8;letter-spacing:1px">Ready to grow?</p>
                <h2 class="mb-2">Bridge the next opportunity today.</h2>
                <p>Candidate, employer, and administrator workspaces — all included.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="btn btn-light fw-bold me-2" href="{{ route('register') }}">
                    <i class="bi bi-person-plus me-1"></i> Create account
                </a>
                <a class="btn btn-outline-light" href="{{ route('jobs.index') }}">Browse jobs</a>
            </div>
        </div>
    </div>
</section>

@endsection
