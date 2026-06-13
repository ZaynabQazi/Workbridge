<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobSearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeAnalyzerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobSearchController::class, 'index'])->name('jobs.index');
Route::get('/jobs/search', [JobSearchController::class, 'search'])->name('jobs.search');
Route::get('/jobs/{job}', [JobSearchController::class, 'show'])->name('jobs.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'forgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'reset'])->name('password.reset');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
    Route::post('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');
    Route::post('/jobs/{job}/apply', [CandidateController::class, 'apply'])->name('apply');
    Route::post('/jobs/{job}/save', [CandidateController::class, 'saveJob'])->name('save');
    Route::post('/applications/{application}/withdraw', [CandidateController::class, 'withdraw'])->name('withdraw');
    Route::get('/resume-analyzer', [ResumeAnalyzerController::class, 'index'])->name('analyzer');
    Route::post('/resume-analyzer', [ResumeAnalyzerController::class, 'analyze'])->name('analyzer.run');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerController::class, 'dashboard'])->name('dashboard');
    Route::get('/company', [EmployerController::class, 'company'])->name('company');
    Route::post('/company', [EmployerController::class, 'updateCompany'])->name('company.update');
    Route::get('/jobs/create', [EmployerController::class, 'createJob'])->name('jobs.create');
    Route::post('/jobs', [EmployerController::class, 'storeJob'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [EmployerController::class, 'editJob'])->name('jobs.edit');
    Route::put('/jobs/{job}', [EmployerController::class, 'updateJob'])->name('jobs.update');
    Route::delete('/jobs/{job}', [EmployerController::class, 'destroyJob'])->name('jobs.destroy');
    Route::post('/jobs/{job}/toggle', [EmployerController::class, 'toggleJob'])->name('jobs.toggle');
    Route::get('/jobs/{job}/applicants', [EmployerController::class, 'applicants'])->name('jobs.applicants');
    Route::post('/applications/{application}/status', [EmployerController::class, 'status'])->name('applications.status');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::post('/jobs/{job}/approve', [AdminController::class, 'approveJob'])->name('jobs.approve');
    Route::post('/jobs/{job}/reject', [AdminController::class, 'rejectJob'])->name('jobs.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
});
Route::get('/init-db', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        return '<h1>Database migrated successfully!</h1>';
    } catch (\Exception $e) {
        return '<h1>Error:</h1><pre>' . $e->getMessage() . '</pre>';
    }
});