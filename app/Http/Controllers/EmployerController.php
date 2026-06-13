<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Application;
use App\Models\AppNotification;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function dashboard(Request $request)
    {
        $company = $request->user()->company()->firstOrCreate(['user_id' => $request->user()->id], ['name' => $request->user()->name."'s Company"]);
        $jobs = $company->jobs()->withCount('applications')->latest()->get();
        return view('employer.dashboard', [
            'company' => $company,
            'jobs' => $jobs,
            'stats' => [
                'total' => $jobs->count(),
                'active' => $jobs->where('status', 'approved')->count(),
                'closed' => $jobs->where('status', 'closed')->count(),
                'applicants' => $jobs->sum('applications_count'),
            ],
        ]);
    }

    public function company(Request $request) { return view('employer.company', ['company' => $request->user()->company]); }

    public function updateCompany(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:140'], 'industry' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'], 'website' => ['nullable', 'url', 'max:180'],
            'description' => ['nullable', 'string', 'max:1500'], 'logo' => ['nullable', 'image', 'max:2048'],
        ]);
        if ($request->hasFile('logo')) $data['logo'] = $request->file('logo')->store('logos', 'public');
        $request->user()->company()->updateOrCreate(['user_id' => $request->user()->id], $data);
        return back()->with('success', 'Company saved.');
    }

    public function createJob() { return view('employer.job-form', ['job' => new Job(), 'categories' => Category::all()]); }
    public function editJob(Job $job) { $this->own($job); return view('employer.job-form', ['job' => $job, 'categories' => Category::all()]); }

    public function storeJob(JobRequest $request)
    {
        $company = $request->user()->company()->firstOrCreate(['user_id' => $request->user()->id], ['name' => $request->user()->name."'s Company"]);
        $company->jobs()->create($request->validated() + ['status' => 'pending']);
        return redirect()->route('employer.dashboard')->with('success', 'Job submitted for approval.');
    }

    public function updateJob(JobRequest $request, Job $job)
    {
        $this->own($job);
        $job->update($request->validated() + ['status' => 'pending', 'approved_at' => null]);
        return redirect()->route('employer.dashboard')->with('success', 'Job updated and sent for review.');
    }

    public function destroyJob(Job $job) { $this->own($job); $job->delete(); return back()->with('success', 'Job deleted.'); }
    public function toggleJob(Job $job) { $this->own($job); $job->update(['status' => $job->status === 'closed' ? 'pending' : 'closed']); return back()->with('success', 'Job status changed.'); }
    public function applicants(Job $job) { $this->own($job); return view('employer.applicants', ['job' => $job->load('applications.candidate.candidateProfile')]); }

    public function status(Request $request, Application $application)
    {
        $this->own($application->job);
        $data = $request->validate(['status' => ['required', 'in:pending,reviewed,shortlisted,rejected']]);
        $application->update($data);
        AppNotification::create(['user_id' => $application->candidate_id, 'title' => 'Application updated', 'message' => 'Your application for '.$application->job->title.' is now '.$data['status'], 'type' => 'status']);
        return back()->with('success', 'Application updated.');
    }

    private function own(Job $job): void
    {
        abort_unless($job->company?->user_id === auth()->id(), 403);
    }
}
