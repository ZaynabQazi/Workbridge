@extends('layouts.app')
@section('title','Register — WorkBridge')
@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:grid;place-items:center;margin:0 auto 14px;box-shadow:0 4px 16px rgba(79,70,229,.3)">
                <i class="bi bi-layers-fill text-white fs-5"></i>
            </div>
            <h1 class="auth-title">Create account</h1>
            <p class="auth-sub">Join WorkBridge — it's free</p>
        </div>
        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full name</label>
                <div class="position-relative">
                    <i class="bi bi-person" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" name="name" placeholder="John Smith" required value="{{ old('name') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <div class="position-relative">
                    <i class="bi bi-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" name="email" type="email" placeholder="you@example.com" required value="{{ old('email') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone <span class="text-muted fw-normal">(optional)</span></label>
                <div class="position-relative">
                    <i class="bi bi-phone" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" name="phone" placeholder="+92 300 0000000" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">I am a…</label>
                <div class="d-flex gap-3">
                    <label class="d-flex align-items-center gap-2 p-3 border rounded flex-fill cursor-pointer" style="cursor:pointer;border-radius:var(--radius-sm)!important;transition:border-color .15s" id="roleCard-candidate">
                        <input type="radio" name="role" value="candidate" checked class="form-check-input mt-0" onchange="highlightRole(this)">
                        <div>
                            <div class="fw-semibold small">Job Seeker</div>
                            <div class="text-muted" style="font-size:11px">Find & apply for jobs</div>
                        </div>
                    </label>
                    <label class="d-flex align-items-center gap-2 p-3 border rounded flex-fill cursor-pointer" style="cursor:pointer;border-radius:var(--radius-sm)!important;transition:border-color .15s" id="roleCard-employer">
                        <input type="radio" name="role" value="employer" class="form-check-input mt-0" onchange="highlightRole(this)">
                        <div>
                            <div class="fw-semibold small">Employer</div>
                            <div class="text-muted" style="font-size:11px">Post jobs & hire</div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <div class="position-relative">
                    <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input id="password" class="form-control ps-5" name="password" type="password" placeholder="At least 8 characters" required>
                </div>
            </div>
            <div class="mb-1">
                <div class="progress mb-1" style="height:4px;border-radius:4px">
                    <div id="strength" class="progress-bar" style="width:0;transition:.3s"></div>
                </div>
                <div id="strengthLabel" class="text-muted" style="font-size:11px"></div>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirm password</label>
                <div class="position-relative">
                    <i class="bi bi-lock-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted)"></i>
                    <input class="form-control ps-5" name="password_confirmation" type="password" placeholder="Repeat password" required>
                </div>
            </div>
            <button class="btn btn-primary w-100" style="height:46px;font-size:15px">
                <i class="bi bi-person-check me-2"></i>Create account
            </button>
        </form>
        <div class="auth-divider">already have an account?</div>
        <p class="text-center small mb-0">
            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign in instead</a>
        </p>
    </div>
</div>
@push('scripts')
<script>
function highlightRole(radio){
    document.querySelectorAll('[id^=roleCard-]').forEach(el=>el.style.borderColor='');
    document.getElementById('roleCard-'+radio.value).style.borderColor='var(--primary)';
}
highlightRole(document.querySelector('input[name=role]:checked'));
document.getElementById('password').addEventListener('input',function(){
    var v=this.value,s=0,l=[''],c=[''];
    if(v.length>=8)s+=25;
    if(/[A-Z]/.test(v))s+=25;
    if(/[0-9]/.test(v))s+=25;
    if(/[^A-Za-z0-9]/.test(v))s+=25;
    var bar=document.getElementById('strength');
    var lbl=document.getElementById('strengthLabel');
    bar.style.width=s+'%';
    if(s<50){bar.className='progress-bar bg-danger';lbl.textContent='Weak password';}
    else if(s<75){bar.className='progress-bar bg-warning';lbl.textContent='Fair — add uppercase & symbols';}
    else if(s<100){bar.className='progress-bar bg-info';lbl.textContent='Good password';}
    else{bar.className='progress-bar bg-success';lbl.textContent='Strong password ✓';}
});
</script>
@endpush
@endsection
