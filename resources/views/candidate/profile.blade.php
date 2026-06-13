@extends('layouts.app')
@section('title','Edit Profile — WorkBridge')
@section('content')
<div class="container py-4">
    <form class="card profile-editor" method="post" enctype="multipart/form-data" action="{{ route('candidate.profile.update') }}">
        @csrf
        <div class="profile-cover"></div>
        <div class="card-body p-4">
            <div class="profile-row">
                <div class="profile-photo">
                    @if($profile->profile_picture)
                        <img src="{{ asset('storage/'.$profile->profile_picture) }}" alt="Profile">
                    @else
                        <i class="bi bi-person"></i>
                    @endif
                </div>
                <div>
                    <h1 class="h4 fw-bold mb-1">{{ auth()->user()->name }}</h1>
                    <p class="text-muted small mb-0">{{ $profile->headline ?: 'Add a professional headline' }}</p>
                </div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-12">
                    <p class="dash-section-title">Basic Info</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Professional headline</label>
                    <input class="form-control" name="headline" value="{{ $profile->headline }}" placeholder="e.g. Senior Laravel Developer">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <div class="position-relative">
                        <i class="bi bi-geo-alt" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                        <input class="form-control ps-5" name="location" value="{{ $profile->location }}" placeholder="City, Country">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">About / Summary</label>
                    <textarea class="form-control" name="summary" rows="4" placeholder="Write a short professional summary…">{{ $profile->summary }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Skills <span class="text-muted fw-normal small">(comma-separated)</span></label>
                    <input class="form-control" name="skills" value="{{ implode(', ', $profile->skills ?? []) }}" placeholder="e.g. PHP, Laravel, Vue.js, MySQL">
                </div>

                <div class="col-12 mt-2">
                    <p class="dash-section-title">Experience & Education</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Education <span class="text-muted fw-normal small">(one per line)</span></label>
                    <textarea class="form-control" name="education" rows="5" placeholder="BSc Computer Science — FAST NUCES (2022)">{{ implode("\n", $profile->education ?? []) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Experience <span class="text-muted fw-normal small">(one per line)</span></label>
                    <textarea class="form-control" name="experience" rows="5" placeholder="Laravel Developer — TechCorp (2022–2024)">{{ implode("\n", $profile->experience ?? []) }}</textarea>
                </div>

                <div class="col-12 mt-2">
                    <p class="dash-section-title">Media & Documents</p>
                </div>
                <div class="col-md-6">
                    <label class="upload-zone compact w-100">
                        <i class="bi bi-image"></i>
                        <strong>Profile photo</strong>
                        <span>JPG/PNG · max 2MB</span>
                        <input class="d-none file-preview-input" name="profile_picture" type="file" accept="image/*">
                    </label>
                    <div class="selected-file small text-muted mt-2"></div>
                </div>
                <div class="col-md-6">
                    <label class="upload-zone compact w-100">
                        <i class="bi bi-file-pdf"></i>
                        <strong>CV / Resume PDF</strong>
                        <span>PDF · max 2MB</span>
                        <input class="d-none file-preview-input" name="resume" type="file" accept="application/pdf">
                    </label>
                    <div class="selected-file small text-muted mt-2"></div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 pt-2 border-top">
                <button class="btn btn-primary px-5" style="height:44px">
                    <i class="bi bi-check-lg me-2"></i>Save profile
                </button>
                <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
