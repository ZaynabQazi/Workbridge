@extends('layouts.app')
@section('title','Company Page — WorkBridge')
@section('content')
<div class="container py-4">
    <form class="card profile-editor" method="post" enctype="multipart/form-data" action="{{ route('employer.company.update') }}">
        @csrf
        <div class="company-cover"></div>
        <div class="card-body p-4">
            <div class="profile-row">
                <div class="profile-photo company-photo">
                    @if($company?->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="Company logo">
                    @else
                        <i class="bi bi-buildings"></i>
                    @endif
                </div>
                <div>
                    <h1 class="h4 fw-bold mb-1">{{ $company->name ?? 'Your Company' }}</h1>
                    <p class="text-muted small mb-0">{{ $company->industry ?? 'Set your company industry and location below' }}</p>
                </div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-12">
                    <p class="dash-section-title">Company Details</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company name <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <i class="bi bi-building" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="name" value="{{ $company->name ?? '' }}" placeholder="Acme Corp" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Industry</label>
                    <div class="position-relative">
                        <i class="bi bi-tag" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="industry" value="{{ $company->industry ?? '' }}" placeholder="e.g. Software, Healthcare">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="location" value="{{ $company->location ?? '' }}" placeholder="Lahore, Pakistan">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <div class="position-relative">
                        <i class="bi bi-globe" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="website" value="{{ $company->website ?? '' }}" placeholder="https://yourcompany.com">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Company description</label>
                    <textarea class="form-control" name="description" rows="5" placeholder="Describe your company, culture, and mission…">{{ $company->description ?? '' }}</textarea>
                </div>
                <div class="col-12 mt-2">
                    <p class="dash-section-title">Branding</p>
                </div>
                <div class="col-md-6">
                    <label class="upload-zone compact w-100">
                        <i class="bi bi-image"></i>
                        <strong>Upload company logo</strong>
                        <span>JPG/PNG · max 2MB</span>
                        <input class="d-none file-preview-input" name="logo" type="file" accept="image/*">
                    </label>
                    <div class="selected-file small text-muted mt-2"></div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 pt-2 border-top">
                <button class="btn btn-primary px-5" style="height:44px">
                    <i class="bi bi-check-lg me-2"></i>Save company page
                </button>
                <a href="{{ route('employer.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
