<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\AppNotification;
use App\Models\CandidateProfile;
use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WorkBridgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect(['Software Development','Design','Marketing','Data','Customer Support'])->mapWithKeys(fn ($name) => [$name => Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Open roles for '.$name])]);
        $admin = User::firstOrCreate(['email'=>'admin@workbridge.test'], ['name'=>'WorkBridge Admin','role'=>'admin','password'=>Hash::make('Password123')]);
        $candidate = User::firstOrCreate(['email'=>'candidate@workbridge.test'], ['name'=>'Ayesha Candidate','role'=>'candidate','password'=>Hash::make('Password123')]);
        CandidateProfile::updateOrCreate(['user_id'=>$candidate->id], ['headline'=>'Laravel Developer','location'=>'Lahore','skills'=>['laravel','php','mysql','bootstrap','ajax'],'education'=>['BS Computer Science'],'experience'=>['2 years Laravel development']]);
        $employer = User::firstOrCreate(['email'=>'employer@workbridge.test'], ['name'=>'Hassan Employer','role'=>'employer','password'=>Hash::make('Password123')]);
        $company = Company::updateOrCreate(['user_id'=>$employer->id], ['name'=>'BridgeTech Solutions','industry'=>'Software','location'=>'Karachi','website'=>'https://example.com','description'=>'A software team hiring practical builders.']);
        $jobs = [
            ['Laravel Developer','Build secure business web applications for growing teams.','Laravel PHP MySQL AJAX Bootstrap','Software Development'],
            ['UI UX Designer','Design responsive product interfaces and dashboards.','Figma UI UX HTML CSS communication','Design'],
            ['Data Analyst','Create weekly reports and business dashboards.','SQL Python Excel communication','Data'],
        ];
        foreach ($jobs as $row) {
            Job::firstOrCreate(['title'=>$row[0]], ['company_id'=>$company->id,'category_id'=>$categories[$row[3]]->id,'description'=>$row[1],'requirements'=>$row[2],'salary_range'=>'PKR 120k - 220k','location'=>'Remote','employment_type'=>'Full-time','deadline'=>now()->addMonth()->toDateString(),'status'=>'approved','approved_at'=>now()]);
        }
        $job = Job::first();
        Application::firstOrCreate(['job_id'=>$job->id,'candidate_id'=>$candidate->id], ['cover_letter'=>'I match Laravel, PHP and MySQL requirements.','status'=>'shortlisted']);
        SavedJob::firstOrCreate(['job_id'=>$job->id,'candidate_id'=>$candidate->id]);
        AppNotification::firstOrCreate(['user_id'=>$candidate->id,'title'=>'Welcome to WorkBridge'], ['message'=>'Your account is ready. Try the AI resume analyzer.','type'=>'info']);
        AppNotification::firstOrCreate(['user_id'=>$admin->id,'title'=>'Seed data ready'], ['message'=>'Demo users, jobs and applications have been created.','type'=>'info']);
    }
}
