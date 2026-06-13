<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WorkBridge')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/workbridge.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg app-nav sticky-top">
    <div class="container">
        <a class="navbar-brand brand-mark" href="{{ route('home') }}">
            <span>wb</span>WorkBridge
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse">
            <form class="nav-search mx-lg-4 my-3 my-lg-0" action="{{ route('jobs.index') }}">
                <i class="bi bi-search"></i>
                <input name="q" placeholder="Search jobs, skills, companies…" value="{{ request('q') }}">
            </form>
            <ul class="navbar-nav me-auto nav-icons">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
                        <i class="bi bi-briefcase{{ request()->routeIs('jobs.*') ? '-fill' : '' }}"></i>
                        <span>Jobs</span>
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs(auth()->user()->role.'.*') ? 'active' : '' }}" href="{{ route(auth()->user()->role.'.dashboard') }}">
                        <i class="bi bi-grid{{ request()->routeIs(auth()->user()->role.'.*') ? '-fill' : '' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <button class="icon-button position-relative" id="notificationBell" type="button" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger rounded-pill bell-count" style="display:none">0</span>
                    </button>
                    <span class="nav-avatar" title="{{ auth()->user()->name }}">{{ Str::of(auth()->user()->name)->substr(0,1) }}</span>
                    <form method="post" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                @else
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Notification dropdown -->
<div class="notif-dropdown" id="notifDropdown">
    <div class="notif-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell me-2"></i>Notifications</span>
        <button class="btn btn-sm btn-link text-primary p-0" id="markAllRead">Mark all read</button>
    </div>
    <div id="notifList"><div class="p-4 text-center text-muted small">No new notifications</div></div>
</div>

<main>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1100">
        <div id="liveToast" class="toast align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
    </div>
    @endif

    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <span class="footer-brand">
                <i class="bi bi-layers-fill me-1"></i>WorkBridge
            </span>
            <small>Online Job Portal · Laravel MVC · AJAX · AI Resume Matching</small>
            <small>© {{ date('Y') }} WorkBridge</small>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/workbridge.js') }}"></script>
@stack('scripts')
</body>
</html>
