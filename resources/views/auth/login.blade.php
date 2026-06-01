@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-logo"><i class="bi bi-mortarboard-fill"></i></div>
<h1 class="auth-title text-center">Welcome Back</h1>
<p class="auth-subtitle text-center mb-4">Student Subject Management System</p>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 px-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-1"></i>
    {{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('login') }}" method="POST" novalidate>
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control border-start-0 @error('email') is-invalid @enderror"
                placeholder="you@example.com" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="passwordInput"
                class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                placeholder="••••••••" required>
            <button class="btn btn-outline-secondary border" type="button" onclick="togglePwd('passwordInput', this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label text-muted small" for="remember">Remember me</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
</form>

<div class="divider">or</div>
<p class="text-center text-muted small mb-0">
    Don't have an account?
    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create Account</a>
</p>
@endsection

@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
@endpush
