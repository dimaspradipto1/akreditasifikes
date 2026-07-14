@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Tambah Pengguna</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Data User</a></li>
            <li class="breadcrumb-item active">Tambah Pengguna</li>
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
                            <i class="bi bi-person-plus-fill text-white" style="font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold" style="font-size:1rem;">
                                Tambah Pengguna Baru
                            </h5>
                            <small class="text-white-50">Isi data akun pengguna sistem akreditasi</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

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
                                           value="{{ old('name') }}"
                                           placeholder="Contoh: Dr. Budi Santoso"
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
                                           value="{{ old('email') }}"
                                           placeholder="nama@fikes.uis.ac.id"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" id="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimal 6 karakter"
                                           autocomplete="new-password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash-fill" id="iconPassword"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Minimal 6 karakter.</div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold" style="font-size:0.85rem;">
                                    Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock-check-fill text-muted"></i>
                                    </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password"
                                           autocomplete="new-password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                        <i class="bi bi-eye-slash-fill" id="iconConfirm"></i>
                                    </button>
                                </div>
                                <div id="matchMsg" class="form-text d-none"></div>
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
                                        <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
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
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                                        ✅ Aktif
                                    </option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                                        ❌ Nonaktif
                                    </option>
                                </select>
                            </div>

                            {{-- Tombol --}}
                            <div class="col-12 d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                                <a href="{{ route('user.index') }}"
                                   class="btn btn-secondary px-4"
                                   style="border-radius:8px;">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                                <button type="submit"
                                        id="btnSubmit"
                                        class="btn px-4 text-white"
                                        style="background:#5520B8;border-color:#5520B8;border-radius:8px;">
                                    <i class="bi bi-person-check-fill me-1"></i> Simpan Pengguna
                                </button>
                            </div>

                        </div>{{-- end row --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Toggle show/hide password
    function toggleVis(btnId, inputId, iconId) {
        document.getElementById(btnId).addEventListener('click', function () {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type     = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
        });
    }
    toggleVis('togglePassword', 'password',              'iconPassword');
    toggleVis('toggleConfirm',  'password_confirmation', 'iconConfirm');

    // Real-time password match
    const pwd1    = document.getElementById('password');
    const pwd2    = document.getElementById('password_confirmation');
    const matchEl = document.getElementById('matchMsg');

    function checkMatch() {
        if (!pwd2.value) { matchEl.classList.add('d-none'); return; }
        matchEl.classList.remove('d-none');
        if (pwd1.value === pwd2.value) {
            matchEl.textContent  = '✔ Password cocok';
            matchEl.style.color  = '#16a34a';
        } else {
            matchEl.textContent  = '✖ Password tidak cocok';
            matchEl.style.color  = '#dc2626';
        }
    }
    pwd1.addEventListener('input', checkMatch);
    pwd2.addEventListener('input', checkMatch);
</script>
@endpush
