@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Edit Pengguna</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Data User</a></li>
            <li class="breadcrumb-item active">Edit Pengguna</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow-sm" style="border-radius:14px;overflow:hidden;">

                {{-- Card Header --}}
                <div class="card-header py-3 px-4"
                     style="background:linear-gradient(135deg,#3D1788,#5520B8);border:none;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.15);
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-pencil-fill text-white" style="font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold" style="font-size:1rem;">Edit Data Pengguna</h5>
                            <small class="text-white-50">{{ $user->name }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Nama Lengkap --}}
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person-fill text-muted"></i>
                                    </span>
                                    <input type="text" id="name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-envelope-fill text-muted"></i>
                                    </span>
                                    <input type="email" id="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Hak Akses --}}
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Hak Akses / Role <span class="text-danger">*</span>
                                </label>
                                <select id="role" name="role"
                                        class="form-select @error('role') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Hak Akses --</option>
                                    @foreach(\App\Models\User::availableRoles() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label for="is_active" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Status Akun
                                </label>
                                <select id="is_active" name="is_active" class="form-select">
                                    <option value="1"
                                        {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>
                                        ✅ Aktif
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>
                                        ❌ Nonaktif
                                    </option>
                                </select>
                            </div>

                            {{-- Info password --}}
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2 p-3 rounded"
                                     style="background:#EDE7FF;border:1px solid #c4b5fd;">
                                    <i class="bi bi-info-circle-fill" style="color:#5520B8;font-size:1rem;flex-shrink:0;"></i>
                                    <small class="text-muted">
                                        Untuk mengubah password, gunakan tombol
                                        <strong style="color:#5520B8;">
                                            <i class="bi bi-key-fill"></i> Update Password
                                        </strong>
                                        pada halaman daftar pengguna.
                                    </small>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="col-12 d-flex gap-2 justify-content-between pt-2 border-top mt-1">
                                <a href="{{ route('user.updatePasswordForm', $user->id) }}"
                                   class="btn btn-info text-white px-3"
                                   style="border-radius:8px;">
                                    <i class="bi bi-key-fill me-1"></i> Update Password
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('user.index') }}"
                                       class="btn btn-secondary px-4"
                                       style="border-radius:8px;">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="submit"
                                            class="btn px-4 text-white"
                                            style="background:#5520B8;border-color:#5520B8;border-radius:8px;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>

                        </div>{{-- end row --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
