@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb')
<li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row g-3">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="table-card p-0 overflow-hidden h-100">
            <div style="background:linear-gradient(135deg,#1a3c6e,#2756a8);padding:2rem;text-align:center;">
                <div style="position:relative;display:inline-block;">
                    <img id="avatarPreview"
                        src="{{ $user->profile_picture && $user->profile_picture !== 'default.png'
                            ? asset('uploads/profiles/' . $user->profile_picture)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f4a623&color=fff&size=200' }}"
                        alt="Avatar"
                        style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #f4a623;box-shadow:0 4px 20px rgba(0,0,0,.3);">
                </div>
                <h4 style="color:#fff;font-weight:700;margin-top:1rem;margin-bottom:.2rem;">{{ $user->name }}</h4>
                <p style="color:rgba(255,255,255,.6);font-size:.85rem;margin:0;">{{ $user->email }}</p>
            </div>
            <div class="p-3">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-2" style="background:#f8fafc;">
                    <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-book text-primary"></i>
                    </div>
                    <div>
                        <div style="font-size:.75rem;color:#64748b;">My Subjects</div>
                        <div style="font-weight:700;color:#1a3c6e;font-size:1.1rem;">{{ $user->subjects()->count() }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8fafc;">
                    <div style="width:40px;height:40px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-check text-success"></i>
                    </div>
                    <div>
                        <div style="font-size:.75rem;color:#64748b;">Member Since</div>
                        <div style="font-weight:700;color:#1a3c6e;">{{ $user->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Form --}}
    <div class="col-lg-8">
        <div class="table-card mb-3">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-person-gear me-2 text-primary"></i>Edit Profile</span>
            </div>
            <div class="p-4">
                @if ($errors->any() && old('_form') === 'profile')
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_form" value="profile">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" id="profilePictureInput"
                                class="form-control @error('profile_picture') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/gif,image/webp"
                                onchange="previewImage(this)">
                            <div class="form-text">Accepted: jpg, jpeg, png, gif, webp. Max 2MB.</div>
                            @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="table-card">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-shield-lock me-2 text-warning"></i>Change Password</span>
            </div>
            <div class="p-4">
                @if ($errors->any() && old('_form') === 'password')
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_form" value="password">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Your current password" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                class="form-control" placeholder="Repeat new password" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-lock me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
