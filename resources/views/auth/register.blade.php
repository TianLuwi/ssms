@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="auth-logo"><i class="bi bi-person-plus-fill"></i></div>
<h1 class="auth-title text-center">Create Account</h1>
<p class="auth-subtitle text-center mb-4">Student Subject Management System</p>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 px-3 small" role="alert">
    <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('register') }}" method="POST" novalidate>
    @csrf

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
            <input type="text" name="name" value="{{ old('name') }}"
                class="form-control border-start-0 @error('name') is-invalid @enderror"
                placeholder="Juan Dela Cruz" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control border-start-0 @error('email') is-invalid @enderror"
                placeholder="you@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password <small class="text-muted fw-normal">(min. 8 chars)</small></label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="pwd1"
                class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                placeholder="••••••••" required>
            <button class="btn btn-outline-secondary border" type="button" onclick="togglePwd('pwd1',this)">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
            <input type="password" name="password_confirmation" id="pwd2"
                class="form-control border-start-0 border-end-0"
                placeholder="••••••••" required>
            <button class="btn btn-outline-secondary border" type="button" onclick="togglePwd('pwd2',this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-person-check me-2"></i>Create Account
    </button>
</form>

<div class="divider">or</div>
<p class="text-center text-muted small mb-0">
    Already have an account?
    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign In</a>
</p>

<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
@endsection
