<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $approvedCount = Job::approved()->count();
        return view('home', [
            'featuredJobs' => Job::with('company', 'category')->approved()->latest()->take(4)->get(),
            'categories'   => Category::withCount('jobs')->where('is_active', true)->get(),
            'stats'        => [
                'active_jobs'  => $approvedCount,
                'companies'    => User::where('role', 'employer')->count(),
                'candidates'   => User::where('role', 'candidate')->count(),
                'categories'   => Category::where('is_active', true)->count(),
            ],
        ]);
    }
}
