@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Update Password</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Data User</a></li>
            <li class="breadcrumb-item active">Update Password</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm" style="border-radius: 14px; overflow: hidden;">

                {{-- Card Header --}}
                <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #3D1788, #5520B8); border:none;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-key-fill text-white" style="font-size:1.1rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold" style="font-size:1rem;">Update Password Pengguna</h5>
                            <small class="text-white-50">{{ $user->name }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    {{-- User Info --}}
                    <div class="alert alert-light border d-flex align-items-center gap-3 mb-4" style="border-radius:10px;">
                        <div style="width:44px;height:44px;border-radius:50%;background:#EDE7FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-fill" style="font-size:1.2rem;color:#5520B8;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="color:#1a1a2e;">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size:0.82rem;">{{ $user->email }} &bull; <span class="text-capitalize">{{ $user->role }}</span></div>
                        </div>
                    </div>

                    <form action="{{ route('user.updatePassword', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#f8f9fa;border-color:#dee2e6;">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 6 karakter"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleNew" tabindex="-1">
                                    <i class="bi bi-eye-slash-fill" id="iconNew"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Minimal 6 karakter.</div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#f8f9fa;border-color:#dee2e6;">
                                    <i class="bi bi-lock-check-fill text-muted"></i>
                                </span>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Ulangi password baru"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirm" tabindex="-1">
                                    <i class="bi bi-eye-slash-fill" id="iconConfirm"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Password Match Indicator --}}
                        <div id="matchIndicator" class="mb-3 d-none">
                            <small id="matchText" class="fw-semibold"></small>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary px-4" style="border-radius:8px;">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4" style="background:#5520B8;border-color:#5520B8;border-radius:8px;">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function toggleVisibility(btnId, inputId, iconId) {
        document.getElementById(btnId).addEventListener('click', function () {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type     = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
        });
    }

    toggleVisibility('toggleNew',     'password',              'iconNew');
    toggleVisibility('toggleConfirm', 'password_confirmation', 'iconConfirm');

    // Real-time password match indicator
    const pwdNew     = document.getElementById('password');
    const pwdConfirm = document.getElementById('password_confirmation');
    const indicator  = document.getElementById('matchIndicator');
    const matchText  = document.getElementById('matchText');

    function checkMatch() {
        if (!pwdConfirm.value) {
            indicator.classList.add('d-none');
            return;
        }
        indicator.classList.remove('d-none');
        if (pwdNew.value === pwdConfirm.value) {
            matchText.textContent = '✔ Password cocok';
            matchText.style.color = '#16a34a';
        } else {
            matchText.textContent = '✖ Password tidak cocok';
            matchText.style.color = '#dc2626';
        }
    }

    pwdNew.addEventListener('input', checkMatch);
    pwdConfirm.addEventListener('input', checkMatch);
</script>
@endpush
