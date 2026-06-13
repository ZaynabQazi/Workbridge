<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResumeAnalyzeRequest;
use App\Models\Job;
use App\Services\ResumeAnalyzer;

class ResumeAnalyzerController extends Controller
{
    public function index()
    {
        return view('candidate.analyzer', ['jobs' => Job::approved()->latest()->get(), 'result' => null]);
    }

    public function analyze(ResumeAnalyzeRequest $request, ResumeAnalyzer $analyzer)
    {
        $text = $request->string('resume_text')->toString();
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            auth()->user()->candidateProfile()->updateOrCreate(['user_id' => auth()->id()], ['resume_path' => $path]);
            $text .= ' '.$request->file('resume')->getClientOriginalName();
            if ($request->file('resume')->getClientOriginalExtension() === 'txt') {
                $text .= ' '.file_get_contents($request->file('resume')->getRealPath());
            }
        }

        $profileSkills = auth()->user()->candidateProfile?->skills ?? [];
        $text .= ' '.implode(' ', $profileSkills);

        $result = $analyzer->analyze($text, $request->job_id ? Job::find($request->job_id) : null);

        return $request->expectsJson()
            ? response()->json($result)
            : view('candidate.analyzer', ['jobs' => Job::approved()->latest()->get(), 'result' => $result])->with('success', 'Resume analyzed successfully.');
    }
}
