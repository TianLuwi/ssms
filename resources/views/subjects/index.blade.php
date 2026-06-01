@extends('layouts.app')
@section('title', 'Subject List')
@section('page-title', 'Subject List Management')
@section('breadcrumb')
<li class="breadcrumb-item active">Subject List</li>
@endsection

@section('styles')
<style>
    .subject-code { font-family: monospace; font-weight: 700; color: #1a3c6e; background: #e8effa; padding: .2em .55em; border-radius: 6px; font-size: .82rem; }
    .units-badge { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 20px; padding: .25em .65em; font-size: .75rem; font-weight: 700; }
    .sem-1 { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .sem-2 { background:#faf5ff; color:#7e22ce; border:1px solid #e9d5ff; }
    .sem-s { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
    .action-btn { width:32px; height:32px; border-radius:7px; display:inline-flex; align-items:center; justify-content:center; border:none; font-size:.85rem; transition:.15s; }
    .btn-view-s  { background:#eff6ff; color:#1d4ed8; }
    .btn-edit-s  { background:#f0fdf4; color:#16a34a; }
    .btn-del-s   { background:#fef2f2; color:#dc2626; }
    .btn-view-s:hover { background:#dbeafe; } .btn-edit-s:hover { background:#dcfce7; } .btn-del-s:hover { background:#fee2e2; }
    .detail-label { font-weight: 700; font-size: .78rem; color: #64748b; text-transform: uppercase; letter-spacing: .04em; }
    .detail-value { font-size: .9rem; color: #1e293b; }
</style>
@endsection

@section('content')

{{-- Page header with Add button --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-book me-2"></i>Subject List</h5>
        <small class="text-muted">Manage your enrolled subjects</small>
    </div>
    <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
        <i class="bi bi-plus-lg me-1"></i>Add Subject
    </button>
</div>

{{-- Table Card --}}
<div class="table-card">
    <div class="table-header">
        <span class="table-title">
            My Subjects
            <span class="badge bg-primary ms-1">{{ $subjects->total() }}</span>
        </span>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Per page --}}
            <form method="GET" action="{{ route('subjects.index') }}" class="d-flex align-items-center gap-1">
                <input type="hidden" name="search" value="{{ $search }}">
                <select name="per_page" class="form-select form-select-sm" style="width:70px" onchange="this.form.submit()">
                    @foreach([5,10,25,50] as $n)
                    <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </form>
            {{-- Search --}}
            <form method="GET" action="{{ route('subjects.index') }}" class="d-flex gap-1">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search subjects…" style="min-width:200px">
                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i></button>
                @if($search)
                <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Description</th>
                    <th>Units</th>
                    <th>Semester</th>
                    <th>Date Added</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $index => $subject)
                <tr>
                    <td class="text-muted small">{{ $subjects->firstItem() + $index }}</td>
                    <td><span class="subject-code">{{ $subject->subject_code }}</span></td>
                    <td class="fw-semibold" style="max-width:200px">{{ $subject->subject_name }}</td>
                    <td style="max-width:220px">
                        <span class="text-muted small" title="{{ $subject->description }}">
                            {{ Str::limit($subject->description, 60) ?: '—' }}
                        </span>
                    </td>
                    <td><span class="units-badge">{{ $subject->units }} unit{{ $subject->units > 1 ? 's' : '' }}</span></td>
                    <td>
                        @php
                            $semClass = match($subject->semester) {
                                '1st Semester' => 'sem-1',
                                '2nd Semester' => 'sem-2',
                                default        => 'sem-s',
                            };
                        @endphp
                        <span class="semester-badge {{ $semClass }}">{{ $subject->semester }}</span>
                    </td>
                    <td class="text-muted small">{{ $subject->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button class="action-btn btn-view-s"
                                onclick="viewSubject({{ $subject->id }})" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn btn-edit-s"
                                onclick="editSubject({{ $subject->id }})" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn btn-del-s"
                                onclick="confirmDelete({{ $subject->id }}, '{{ addslashes($subject->subject_name) }}')"
                                title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-book display-6 d-block mb-2"></i>
                        @if($search)
                            No subjects match "<strong>{{ $search }}</strong>".
                            <a href="{{ route('subjects.index') }}" class="d-block mt-1">Clear search</a>
                        @else
                            No subjects yet. <a href="#" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add your first subject!</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($subjects->hasPages())
    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
        <small class="text-muted">
            Showing {{ $subjects->firstItem() }}–{{ $subjects->lastItem() }} of {{ $subjects->total() }} records
        </small>
        <div>{{ $subjects->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
    </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════ --}}
{{-- ADD SUBJECT MODAL                               --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST" id="addSubjectForm">
                @csrf
                <div class="modal-body p-4">
                    @if ($errors->any() && old('_action') === 'add')
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                    @endif
                    <input type="hidden" name="_action" value="add">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" name="subject_code" value="{{ old('subject_code') }}"
                                class="form-control @error('subject_code') is-invalid @enderror"
                                placeholder="e.g. CS101" maxlength="20">
                            @error('subject_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="subject_name" value="{{ old('subject_name') }}"
                                class="form-control @error('subject_name') is-invalid @enderror"
                                placeholder="e.g. Introduction to Computing">
                            @error('subject_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Units <span class="text-danger">*</span></label>
                            <input type="number" name="units" value="{{ old('units') }}"
                                class="form-control @error('units') is-invalid @enderror"
                                min="1" max="6" placeholder="3">
                            @error('units')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror">
                                <option value="">— Select Semester —</option>
                                <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer"       {{ old('semester') == 'Summer'       ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Brief description of the subject (optional)…"
                                maxlength="1000">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════ --}}
{{-- EDIT SUBJECT MODAL                              --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#16a34a;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSubjectForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" name="subject_code" id="edit_subject_code" class="form-control" maxlength="20" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Units <span class="text-danger">*</span></label>
                            <input type="number" name="units" id="edit_units" class="form-control" min="1" max="6" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="edit_semester" class="form-select" required>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control" maxlength="1000"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════ --}}
{{-- VIEW SUBJECT MODAL                              --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="viewSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#7e22ce;">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Subject Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3" id="viewSubjectContent">
                    <div class="col-12 text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════ --}}
{{-- DELETE CONFIRMATION MODAL                       --}}
{{-- ════════════════════════════════════════════════ --}}
<div class="modal fade" id="deleteSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-trash3 display-5 text-danger mb-3 d-block"></i>
                <p class="mb-1">Are you sure you want to delete</p>
                <strong id="deleteSubjectName" class="text-danger"></strong>
                <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSubjectForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4"><i class="bi bi-trash3 me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Re-open add modal on validation error
@if ($errors->any() && old('_action') === 'add')
new bootstrap.Modal(document.getElementById('addSubjectModal')).show();
@endif

// View Subject
async function viewSubject(id) {
    const modal = new bootstrap.Modal(document.getElementById('viewSubjectModal'));
    const content = document.getElementById('viewSubjectContent');
    content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();

    try {
        const res = await fetch(`/subjects/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const s = await res.json();

        const semColors = { '1st Semester': '#1d4ed8', '2nd Semester': '#7e22ce', 'Summer': '#c2410c' };
        const semBg    = { '1st Semester': '#eff6ff', '2nd Semester': '#faf5ff', 'Summer': '#fff7ed' };

        content.innerHTML = `
            <div class="col-12 text-center mb-3">
                <span style="font-size:1.4rem;font-weight:800;color:#1a3c6e;font-family:monospace;
                    background:#e8effa;padding:.3em .8em;border-radius:10px;">${s.subject_code}</span>
            </div>
            <div class="col-12">
                <div class="detail-label">Subject Name</div>
                <div class="detail-value fw-semibold">${s.subject_name}</div>
            </div>
            <div class="col-6">
                <div class="detail-label">Units</div>
                <div class="detail-value">${s.units} unit${s.units > 1 ? 's' : ''}</div>
            </div>
            <div class="col-6">
                <div class="detail-label">Semester</div>
                <div class="detail-value">
                    <span style="background:${semBg[s.semester]||'#f0f4f8'};color:${semColors[s.semester]||'#374151'};
                        border-radius:20px;padding:.2em .7em;font-size:.78rem;font-weight:700;">${s.semester}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="detail-label">Description</div>
                <div class="detail-value">${s.description || '<span class="text-muted fst-italic">No description provided.</span>'}</div>
            </div>
            <div class="col-6">
                <div class="detail-label">Date Created</div>
                <div class="detail-value small text-muted">${new Date(s.created_at).toLocaleDateString('en-PH', {year:'numeric',month:'long',day:'numeric'})}</div>
            </div>
            <div class="col-6">
                <div class="detail-label">Last Updated</div>
                <div class="detail-value small text-muted">${new Date(s.updated_at).toLocaleDateString('en-PH', {year:'numeric',month:'long',day:'numeric'})}</div>
            </div>
        `;
    } catch (e) {
        content.innerHTML = '<div class="col-12 text-center text-danger py-3">Failed to load subject.</div>';
    }
}

// Edit Subject
async function editSubject(id) {
    try {
        const res = await fetch(`/subjects/${id}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const s   = await res.json();

        document.getElementById('editSubjectForm').action = `/subjects/${id}`;
        document.getElementById('edit_subject_code').value = s.subject_code;
        document.getElementById('edit_subject_name').value = s.subject_name;
        document.getElementById('edit_units').value        = s.units;
        document.getElementById('edit_semester').value     = s.semester;
        document.getElementById('edit_description').value  = s.description ?? '';

        new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
    } catch (e) {
        alert('Failed to load subject data. Please refresh and try again.');
    }
}

// Delete Confirm
function confirmDelete(id, name) {
    document.getElementById('deleteSubjectName').textContent = name;
    document.getElementById('deleteSubjectForm').action = `/subjects/${id}`;
    new bootstrap.Modal(document.getElementById('deleteSubjectModal')).show();
}
</script>
@endsection
