@extends('layouts.app')
@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('breadcrumb')
<li class="breadcrumb-item active">Users</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Users Management</h5>
        <small class="text-muted">Manage system users</small>
    </div>
    <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i>Add User
    </button>
</div>

<div class="table-card">
    <div class="table-header">
        <span class="table-title">All Users <span class="badge bg-primary ms-1">{{ $users->total() }}</span></span>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" class="d-flex align-items-center gap-1">
                <input type="hidden" name="search" value="{{ $search }}">
                <select name="per_page" class="form-select form-select-sm" style="width:70px" onchange="this.form.submit()">
                    @foreach([5,10,25,50] as $n)
                    <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </form>
            <form method="GET" class="d-flex gap-1">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search users…" style="min-width:200px">
                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i></button>
                @if($search)<a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>@endif
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subjects</th>
                    <th>Registered</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                <tr>
                    <td class="text-muted small">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2756a8&color=fff&size=40"
                                alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if($user->id === Auth::id())
                                <span class="badge bg-primary-subtle text-primary" style="font-size:.65rem;">You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $user->subjects_count ?? $user->subjects()->count() }}</span>
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button class="action-btn btn-edit-s" onclick="editUser({{ $user->id }})" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @if($user->id !== Auth::id())
                            <button class="action-btn btn-del-s" onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                            @else
                            <button class="action-btn" style="background:#f1f5f9;color:#94a3b8;" title="Cannot delete yourself" disabled>
                                <i class="bi bi-trash3"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people display-6 d-block mb-2"></i>No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
        <small class="text-muted">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</small>
        <div>{{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>

{{-- ADD USER MODAL --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Juan Dela Cruz" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="user@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT USER MODAL --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#16a34a;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_user_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_user_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-muted fw-normal">(leave blank to keep)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE USER MODAL --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-person-x display-5 text-danger mb-3 d-block"></i>
                <p class="mb-1">Delete user</p>
                <strong id="deleteUserName" class="text-danger"></strong>?
                <p class="text-muted small mt-2 mb-0">This will also delete all their subjects.</p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
.action-btn { width:32px;height:32px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;border:none;font-size:.85rem;transition:.15s; }
.btn-edit-s  { background:#f0fdf4;color:#16a34a; }
.btn-del-s   { background:#fef2f2;color:#dc2626; }
.btn-edit-s:hover { background:#dcfce7; } .btn-del-s:hover { background:#fee2e2; }
</style>
<script>
async function editUser(id) {
    const res = await fetch(`/users/${id}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const u   = await res.json();
    document.getElementById('editUserForm').action = `/users/${id}`;
    document.getElementById('edit_user_name').value  = u.name;
    document.getElementById('edit_user_email').value = u.email;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
function confirmDeleteUser(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteUserForm').action = `/users/${id}`;
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
}
</script>
@endsection
