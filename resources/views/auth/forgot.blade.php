@extends('layouts.app')
@section('content')<div class="auth-wrap"><form class="auth-card" method="post" action="{{ route('password.email') }}">@csrf<h1 class="h4">Forgot Password</h1><input class="form-control my-3" name="email" type="email" placeholder="Email" required><button class="btn btn-primary w-100">Send reset link</button></form></div>@endsection
