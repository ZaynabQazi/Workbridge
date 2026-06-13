<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Str;

class ResumeAnalyzer
{
    private array $skillBank = [
        'php', 'laravel', 'mysql', 'ajax', 'bootstrap', 'javascript', 'jquery',
        'html', 'css', 'vue', 'react', 'api', 'git', 'docker', 'testing',
        'sql', 'python', 'excel', 'communication', 'leadership', 'seo',
        'figma', 'ui', 'ux', 'sales', 'marketing', 'aws', 'linux',
    ];

    public function analyze(string $resumeText, ?Job $job = null): array
    {
        $resumeSkills = $this->extractSkills($resumeText);
        $targetJobs = $job ? collect([$job]) : Job::with('company', 'category')->approved()->get();
        $matches = $targetJobs->map(function (Job $item) use ($resumeSkills) {
            $jobSkills = $this->extractSkills($item->requirements.' '.$item->description.' '.$item->title);
            $matched = array_values(array_intersect($resumeSkills, $jobSkills));
            $missing = array_values(array_diff($jobSkills, $resumeSkills));
            $score = count($jobSkills) === 0 ? 0 : (int) round((count($matched) / count($jobSkills)) * 100);

            return [
                'job' => $item,
                'job_skills' => $jobSkills,
                'matched_skills' => $matched,
                'missing_skills' => $missing,
                'match_score' => $score,
            ];
        })->sortByDesc('match_score')->values();

        return [
            'resume_skills' => $resumeSkills,
            'best_match' => $matches->first(),
            'recommendations' => $matches->take(5)->all(),
        ];
    }

    public function extractSkills(string $text): array
    {
        $normalized = Str::lower($text);
        $found = array_filter($this->skillBank, fn (string $skill) => preg_match('/\b'.preg_quote($skill, '/').'\b/i', $normalized));

        return array_values(array_unique($found));
    }
}
