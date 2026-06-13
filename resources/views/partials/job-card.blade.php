<div class="job-card card">
    <div class="card-body">
        <div class="d-flex gap-3">
            <div class="company-logo">
                @if($job->company->logo)
                    <img src="{{ asset('storage/'.$job->company->logo) }}" alt="{{ $job->company->name }}">
                @else
                    {{ Str::of($job->company->name)->substr(0,1) }}
                @endif
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex justify-content-between gap-2 align-items-start flex-wrap">
                    <div>
                        <h3 class="h5 mb-1 fw-bold">
                            <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none text-dark stretched-link-custom">{{ $job->title }}</a>
                        </h3>
                        <p class="mb-1 text-muted small d-flex align-items-center gap-2">
                            <span><i class="bi bi-building me-1"></i>{{ $job->company->name }}</span>
                            <span>·</span>
                            <span><i class="bi bi-geo-alt me-1"></i>{{ $job->location }}</span>
                        </p>
                    </div>
                    <span class="badge status-{{ strtolower($job->employment_type) == 'remote' ? 'shortlisted' : '' }} bg-primary-subtle text-primary">
                        {{ $job->employment_type }}
                    </span>
                </div>
                <p class="small text-muted mb-3 mt-1">{{ Str::limit($job->description, 130) }}</p>
                <div class="job-actions">
                    <span class="job-meta-chip">
                        <i class="bi bi-tag"></i> {{ $job->category->name }}
                    </span>
                    @if($job->salary_range)
                    <span class="job-meta-chip">
                        <i class="bi bi-cash-stack"></i> {{ $job->salary_range }}
                    </span>
                    @endif
                    <span class="job-meta-chip">
                        <i class="bi bi-calendar3"></i> {{ $job->deadline->format('M d') }}
                    </span>
                    <a class="btn btn-primary btn-sm ms-auto" href="{{ route('jobs.show', $job) }}">
                        View <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
