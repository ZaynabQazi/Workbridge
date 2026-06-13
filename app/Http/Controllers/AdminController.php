<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AppNotification;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'users' => User::latest()->take(8)->get(),
            'jobs' => Job::with('company', 'category')->latest()->take(10)->get(),
            'categories' => Category::all(),
            'stats' => [
                'users' => User::count(),
                'jobs' => Job::count(),
                'applications' => Application::count(),
                'pending_jobs' => Job::where('status', 'pending')->count(),
            ],
            'weekly' => Application::selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->orderBy('day')->take(7)->pluck('total', 'day'),
        ]);
    }

    public function users() { return view('admin.users', ['users' => User::latest()->paginate(15)]); }
    public function categories() { return view('admin.categories', ['categories' => Category::latest()->get()]); }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name'], 'description' => ['nullable', 'string', 'max:500']]);
        Category::create($data + ['slug' => Str::slug($data['name']), 'is_active' => true]);
        return back()->with('success', 'Category created.');
    }

    public function approveJob(Job $job)
    {
        $job->update(['status' => 'approved', 'approved_at' => now()]);
        AppNotification::create(['user_id' => $job->company->user_id, 'title' => 'Job approved', 'message' => $job->title.' is live.', 'type' => 'approval']);
        return back()->with('success', 'Job approved.');
    }

    public function rejectJob(Job $job)
    {
        $job->update(['status' => 'rejected']);
        AppNotification::create(['user_id' => $job->company->user_id, 'title' => 'Job rejected', 'message' => $job->title.' needs changes.', 'type' => 'approval']);
        return back()->with('success', 'Job rejected.');
    }

    public function toggleUser(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'You cannot suspend yourself.');
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function deleteUser(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'You cannot delete yourself.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
