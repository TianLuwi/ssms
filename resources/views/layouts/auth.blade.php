<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth') – SSMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#1a3c6e; --primary-light:#2756a8; --accent:#f4a623; }
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2447 0%, #1a3c6e 50%, #2756a8 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.4rem 2rem;
            width: 100%; max-width: 450px;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
        }
        .auth-logo {
            width: 60px; height: 60px;
            background: var(--primary);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff; margin: 0 auto .8rem;
        }
        .auth-title { font-weight: 800; font-size: 1.4rem; color: var(--primary); }
        .auth-subtitle { color: #64748b; font-size: .85rem; }
        .form-label { font-weight: 600; font-size: .82rem; color: #374151; }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: .6rem .9rem;
            font-size: .875rem;
        }
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(39,86,168,.12);
        }
        .btn-primary { background: var(--primary-light); border-color: var(--primary-light); border-radius: 8px; font-weight: 600; }
        .btn-primary:hover { background: var(--primary); border-color: var(--primary); }
        .divider { color: #94a3b8; font-size: .8rem; text-align: center; position: relative; margin: 1.2rem 0; }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%;
            width: 42%; height: 1px; background: #e2e8f0;
        }
        .divider::before { left: 0; } .divider::after { right: 0; }
        .toast-container { z-index: 9999; }
    </style>
</head>
<body>

<div class="auth-card">
    @yield('content')
</div>

{{-- Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    @if (session('toast_success'))
    <div class="toast align-items-center text-bg-success border-0 show" role="alert" data-bs-autohide="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body fw-semibold"><i class="bi bi-check-circle-fill me-2"></i>{{ session('toast_success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 5000 }).show());
</script>
</body>
</html>
