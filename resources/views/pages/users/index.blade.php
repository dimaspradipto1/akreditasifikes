@extends('layouts.dashboarad.template')

@section('title', 'Manajemen Pengguna')

@push('styles')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
    .table-user th { background: #f1f5f9; font-weight: 600; font-size: 0.82rem; color: #374151; }
    .role-admin { background: #dbeafe; color: #1d4ed8; }
    .role-operator { background: #e0f2fe; color: #0369a1; }
    .role-asesor { background: #fef3c7; color: #92400e; }
    .role-prodi { background: #f3f4f6; color: #374151; }
    .btn-action { font-size: 0.78rem; padding: 4px 10px; border-radius: 8px; }
    .modal-header-custom { background: linear-gradient(135deg, #004D2D, #006B3F); color: white; border-radius: 12px 12px 0 0; }
    .modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Manajemen Pengguna</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Pengguna</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius:14px;">
                <div class="card-header d-flex justify-content-between align-items-center py-3 px-4" style="border-bottom:1px solid #f0f0f0;">
                    <h5 class="mb-0" style="font-weight:700;color:#1a2e2a;">
                        <i class="bi bi-people-fill me-2 text-success"></i>
                        Daftar Pengguna Sistem
                    </h5>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal" style="border-radius:10px;">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
                    </button>
                    @endif
                </div>
                <div class="card-body p-4">

                    {{-- Alert --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- DataTable --}}
                    {{ $dataTable->table(['class' => 'table table-hover table-user w-100']) }}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL TAMBAH PENGGUNA --}}
@if(auth()->user()->isAdmin())
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Dr. Budi Santoso" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nama@fikes.uis.ac.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter, huruf besar & angka" required>
                        <div class="form-text">Min. 8 karakter, kombinasi huruf besar, kecil, dan angka.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Administrator</option>
                                <option value="operator">Operator</option>
                                <option value="asesor">Asesor</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-person-check-fill me-1"></i> Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PENGGUNA --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i> Edit Pengguna
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="admin">Administrator</option>
                                <option value="operator">Operator</option>
                                <option value="asesor">Asesor</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-save-fill me-1"></i> Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

<script>
    // Populate Edit Modal
    document.getElementById('editModal')?.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id  = btn.getAttribute('data-id');
        document.getElementById('edit_name').value      = btn.getAttribute('data-name');
        document.getElementById('edit_email').value     = btn.getAttribute('data-email');
        document.getElementById('edit_role').value      = btn.getAttribute('data-role');
        document.getElementById('edit_is_active').value = btn.getAttribute('data-is_active') == 1 ? '1' : '0';
        document.getElementById('editForm').action      = `/users/${id}`;
    });
</script>
@endpush
