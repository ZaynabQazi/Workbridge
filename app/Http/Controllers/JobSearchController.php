<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class JobSearchController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('jobs')->where('is_active', true)->get();
        return view('jobs.index', [
            'jobs'       => $this->query($request)->paginate(8),
            'categories' => $categories,
        ]);
    }

    public function search(Request $request)
    {
        $jobs = $this->query($request)->take(12)->get();
        $html = '';
        foreach ($jobs as $job) {
            $html .= view('partials.job-card', compact('job'))->render();
        }
        return response($html);
    }

    public function show(Job $job)
    {
        abort_unless($job->status === 'approved' || auth()->check(), 404);
        return view('jobs.show', ['job' => $job->load('company', 'category')]);
    }

    private function query(Request $request)
    {
        return Job::with('company', 'category')->approved()
            ->when($request->q,        fn ($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->when($request->category, fn ($q, $v) => $q->where('category_id', $v))
            ->when($request->location, fn ($q, $v) => $q->where('location', 'like', "%{$v}%"))
            ->when($request->type,     fn ($q, $v) => $q->where('employment_type', $v))
            ->latest();
    }
}
