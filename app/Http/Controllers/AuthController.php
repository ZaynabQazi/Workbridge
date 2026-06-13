<?php

namespace App\Http\Controllers;

use App\Models\CandidateProfile;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function forgot() { return view('auth.forgot'); }
    public function reset() { return view('auth.reset'); }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);

        if (Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->dashboardFor(Auth::user()->role))->with('success', 'Welcome back.');
        }

        return back()->withErrors(['email' => 'Invalid credentials or inactive account.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'role' => ['required', 'in:candidate,employer'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        $user->role === 'candidate'
            ? CandidateProfile::create(['user_id' => $user->id])
            : Company::create(['user_id' => $user->id, 'name' => $user->name."'s Company"]);

        Auth::login($user);
        return redirect($this->dashboardFor($user->role))->with('success', 'Account created successfully.');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        return back()->with('success', 'Demo reset link generated. Configure mail in .env for production.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out.');
    }

    private function dashboardFor(string $role): string
    {
        return route($role.'.dashboard');
    }
}
