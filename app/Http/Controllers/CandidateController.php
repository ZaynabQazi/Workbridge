<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Application;
use App\Models\AppNotification;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        return view('candidate.dashboard', [
            'applications' => $user->applications()->with('job.company')->latest()->get(),
            'savedJobs' => $user->savedJobs()->with('job.company')->latest()->get(),
            'notifications' => $user->appNotifications()->latest()->take(6)->get(),
            'stats' => [
                'total' => $user->applications()->count(),
                'pending' => $user->applications()->where('status', 'pending')->count(),
                'shortlisted' => $user->applications()->where('status', 'shortlisted')->count(),
                'saved' => $user->savedJobs()->count(),
            ],
        ]);
    }

    public function profile(Request $request)
    {
        return view('candidate.profile', ['profile' => $request->user()->candidateProfile()->firstOrCreate(['user_id' => $request->user()->id])]);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $data = $request->validated();
        $payload = [
            'headline' => $data['headline'] ?? null,
            'location' => $data['location'] ?? null,
            'summary' => $data['summary'] ?? null,
            'skills' => array_filter(array_map('trim', explode(',', $data['skills'] ?? ''))),
            'education' => array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data['education'] ?? ''))),
            'experience' => array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data['experience'] ?? ''))),
        ];
        if ($request->hasFile('profile_picture')) $payload['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        if ($request->hasFile('resume')) $payload['resume_path'] = $request->file('resume')->store('resumes', 'public');

        $request->user()->candidateProfile()->updateOrCreate(['user_id' => $request->user()->id], $payload);
        return back()->with('success', 'Profile saved.');
    }

    public function apply(Request $request, Job $job)
    {
        abort_unless($job->status === 'approved', 403);
        $data = $request->validate(['cover_letter' => ['nullable', 'string', 'max:2000'], 'resume' => ['nullable', 'file', 'mimes:pdf', 'max:2048']]);
        $path = $request->hasFile('resume') ? $request->file('resume')->store('applications', 'public') : $request->user()->candidateProfile?->resume_path;
        Application::updateOrCreate(['job_id' => $job->id, 'candidate_id' => $request->user()->id], ['cover_letter' => $data['cover_letter'] ?? null, 'resume_path' => $path, 'status' => 'pending']);
        AppNotification::create(['user_id' => $job->company->user_id, 'title' => 'New application', 'message' => $request->user()->name.' applied for '.$job->title, 'type' => 'application']);
        return back()->with('success', 'Application submitted.');
    }

    public function saveJob(Request $request, Job $job)
    {
        SavedJob::firstOrCreate(['job_id' => $job->id, 'candidate_id' => $request->user()->id]);
        return back()->with('success', 'Job saved.');
    }

    public function withdraw(Application $application)
    {
        abort_unless($application->candidate_id === auth()->id(), 403);
        $application->update(['status' => 'withdrawn']);
        return back()->with('success', 'Application withdrawn.');
    }
}
