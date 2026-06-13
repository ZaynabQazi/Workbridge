<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use App\Services\ResumeAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkBridgeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_job_search_load(): void
    {
        $this->seed();

        $this->get('/')->assertOk()->assertSee('WorkBridge');
        $this->getJson('/jobs/search?q=Laravel')->assertOk()->assertJsonFragment(['title' => 'Laravel Developer']);
    }

    public function test_role_middleware_blocks_candidate_from_admin(): void
    {
        $this->seed();

        $candidate = User::where('email', 'candidate@workbridge.test')->firstOrFail();
        $this->actingAs($candidate)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_candidate_can_run_resume_analyzer(): void
    {
        $this->seed();

        $candidate = User::where('email', 'candidate@workbridge.test')->firstOrFail();
        $job = Job::where('title', 'Laravel Developer')->firstOrFail();

        $this->actingAs($candidate)->postJson('/candidate/resume-analyzer', [
            'resume_text' => 'Laravel PHP MySQL Bootstrap developer',
            'job_id' => $job->id,
        ])->assertOk()->assertJsonPath('best_match.match_score', 100);
    }

    public function test_resume_analyzer_detects_missing_skills(): void
    {
        $this->seed();

        $job = Job::where('title', 'Laravel Developer')->firstOrFail();
        $result = app(ResumeAnalyzer::class)->analyze('Laravel PHP MySQL', $job);

        $this->assertContains('ajax', $result['best_match']['missing_skills']);
        $this->assertContains('bootstrap', $result['best_match']['missing_skills']);
        $this->assertEquals(60, $result['best_match']['match_score']);
    }
}
