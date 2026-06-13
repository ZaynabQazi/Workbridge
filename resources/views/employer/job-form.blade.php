@extends('layouts.app')
@section('title', ($job->exists ? 'Edit' : 'Post a') . ' Job — WorkBridge')
@section('content')
<div class="container py-4" style="max-width:760px">
    <div class="dash-head">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}" class="text-decoration-none text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $job->exists ? 'Edit Job' : 'Post Job' }}</li>
                </ol>
            </nav>
            <h1 class="mb-0">{{ $job->exists ? 'Edit Job Posting' : 'Post a New Job' }}</h1>
        </div>
    </div>

    <form class="card" method="post" action="{{ $job->exists ? route('employer.jobs.update', $job) : route('employer.jobs.store') }}">
        @csrf
        @if($job->exists) @method('put') @endif
        <div class="card-body p-4">
            <p class="dash-section-title">Job Details</p>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Job title <span class="text-danger">*</span></label>
                    <input class="form-control" name="title" value="{{ $job->title }}" placeholder="e.g. Senior Laravel Developer" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected($job->category_id == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Employment type</label>
                    <select class="form-select" name="employment_type">
                        @foreach(['Full-time','Part-time','Contract','Internship','Remote'] as $t)
                        <option @selected(($job->employment_type ?? 'Full-time') === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="location" value="{{ $job->location }}" placeholder="Lahore or Remote" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Salary range <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="position-relative">
                        <i class="bi bi-cash-stack" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="salary_range" value="{{ $job->salary_range }}" placeholder="e.g. PKR 80k–120k/mo">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Application deadline <span class="text-danger">*</span></label>
                    <input class="form-control" name="deadline" type="date" value="{{ optional($job->deadline)->format('Y-m-d') }}" required>
                </div>
            </div>

            <p class="dash-section-title mt-4">Description & Requirements</p>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Job description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" rows="6" placeholder="Describe the role, responsibilities, and team…" required>{{ $job->description }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Requirements <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="requirements" rows="5" placeholder="List required skills, experience, qualifications…" required>{{ $job->requirements }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 pt-3 border-top">
                <button class="btn btn-primary px-5" style="height:44px">
                    <i class="bi bi-send me-2"></i>
                    {{ $job->exists ? 'Update Job' : 'Submit for Approval' }}
                </button>
                <a href="{{ route('employer.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
            @if(!$job->exists)
            <p class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i>Your job will be reviewed by an admin before going live.</p>
            @endif
        </div>
    </form>
</div>
@endsection
