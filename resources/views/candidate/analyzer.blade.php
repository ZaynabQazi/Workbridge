@extends('layouts.app')
@section('title','AI Resume Analyzer — WorkBridge')
@section('content')
<div class="container py-4">
    <div class="dash-head">
        <div>
            <h1 class="mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-robot text-primary"></i> AI Resume Analyzer
            </h1>
            <p class="text-muted small mb-0">Upload your CV and get instant job-match scores and recommendations</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <form class="card" method="post" enctype="multipart/form-data" action="{{ route('candidate.analyzer.run') }}">
                @csrf
                <div class="card-body p-4">
                    <span class="badge bg-primary-subtle text-primary mb-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-stars"></i> AI-Powered
                    </span>
                    <h2 class="h4 fw-bold mb-2">Match your CV to jobs</h2>
                    <p class="text-muted small mb-4">Upload your resume, pick a target job (or match against all), and get a score plus skill gap analysis.</p>

                    <div class="mb-3">
                        <label class="form-label">Target job <span class="text-muted fw-normal">(optional)</span></label>
                        <select class="form-select" name="job_id">
                            <option value="">Match against all approved jobs</option>
                            @foreach($jobs as $job)
                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload CV</label>
                        <label class="upload-zone w-100" id="analyzerZone">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                            <strong>Drop CV here or click to browse</strong>
                            <span>PDF or TXT · max 2MB</span>
                            <div class="selected-file small text-success mt-2"></div>
                            <input class="d-none file-preview-input" id="resumeInput" name="resume" type="file" accept=".pdf,.txt">
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Extra skills / context <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea class="form-control" name="resume_text" rows="4" placeholder="List additional skills, certifications, or paste text from your CV…"></textarea>
                    </div>

                    <button class="btn btn-primary w-100" style="height:48px;font-size:16px">
                        <i class="bi bi-cpu me-2"></i>Analyze CV
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-7">
            @if(isset($result))
            <div class="card result-card">
                <div class="card-header">
                    <span><i class="bi bi-bar-chart-steps me-2 text-primary"></i>Analysis Results</span>
                    <span class="badge bg-primary-subtle text-primary">{{ count($result['recommendations']) }} matches</span>
                </div>
                <div class="card-body p-4">
                    @if(!empty($result['resume_skills']))
                    <div class="mb-4">
                        <div class="fw-semibold mb-2 small text-muted text-uppercase">Detected Skills</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($result['resume_skills'] as $skill)
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning small mb-4">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        No recognized skills detected. Try adding skills in the text box or update your profile.
                    </div>
                    @endif

                    <div class="fw-semibold mb-3 small text-muted text-uppercase">Job Matches</div>
                    @forelse($result['recommendations'] as $rec)
                    <div class="match-row">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $rec['job']->title }}</div>
                            <div class="small text-muted mb-1">{{ $rec['job']->company->name ?? '' }} · {{ $rec['job']->location }}</div>
                            @if(!empty($rec['matched_skills']))
                            <div class="small text-success d-flex align-items-start gap-1 mb-1">
                                <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
                                <span>{{ implode(', ', $rec['matched_skills']) }}</span>
                            </div>
                            @endif
                            @if(!empty($rec['missing_skills']))
                            <div class="small text-danger d-flex align-items-start gap-1">
                                <i class="bi bi-x-circle-fill mt-1 flex-shrink-0"></i>
                                <span>Missing: {{ implode(', ', $rec['missing_skills']) }}</span>
                            </div>
                            @endif
                        </div>
                        <div>
                            <div class="score-ring" style="background:conic-gradient(var(--primary) {{ $rec['match_score'] }}%,#e8edf5 0)">
                                {{ $rec['match_score'] }}%
                            </div>
                            <div class="text-center mt-1">
                                <a href="{{ route('jobs.show', $rec['job']) }}" class="btn btn-sm btn-outline-primary mt-2">View</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-search fs-2 opacity-30 d-block mb-2"></i>
                        No matching jobs found for your skills.
                    </div>
                    @endforelse
                </div>
            </div>
            @else
            <div class="empty-state analyzer-empty">
                <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80" alt="Resume review">
                <div>
                    <h2 class="h4 fw-bold">Your AI agent is ready</h2>
                    <p class="text-muted">Upload a CV to get realistic job match scores, skill gap analysis, and personalized recommendations.</p>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        @foreach(['Instant score','Skill gap','Top matches'] as $f)
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-check-circle-fill text-success"></i> {{ $f }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
