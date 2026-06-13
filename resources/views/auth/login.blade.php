@extends('layouts.app')
@section('title','Login — WorkBridge')
@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:grid;place-items:center;margin:0 auto 14px;box-shadow:0 4px 16px rgba(79,70,229,.3)">
                <i class="bi bi-layers-fill text-white fs-5"></i>
            </div>
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-sub">Sign in to your WorkBridge account</p>
        </div>
        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <div class="position-relative">
                    <i class="bi bi-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" name="email" type="email" placeholder="you@example.com" required value="{{ old('email') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="position-relative">
                    <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" id="loginPw" name="password" type="password" placeholder="Your password" required>
                    <button type="button" class="btn btn-link p-0" id="togglePw" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--muted)"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember">
                    <span class="form-check-label small">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">Forgot password?</a>
            </div>
            <button class="btn btn-primary w-100" style="height:46px;font-size:15px">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign in
            </button>
        </form>
        <div class="auth-divider">or</div>
        <p class="text-center small text-muted mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Sign up free</a>
        </p>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('togglePw').addEventListener('click',function(){
    var pw=document.getElementById('loginPw');
    var icon=this.querySelector('i');
    if(pw.type==='password'){pw.type='text';icon.className='bi bi-eye-slash';}
    else{pw.type='password';icon.className='bi bi-eye';}
});
</script>
@endpush
@endsection
