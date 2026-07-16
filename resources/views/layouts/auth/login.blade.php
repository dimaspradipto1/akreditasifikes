<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Akreditasi FIKES UIS</title>
    <meta name="description" content="Portal login Sistem Informasi Akreditasi Fakultas Ilmu Kesehatan Universitas Islam Syekh-Yusuf">

    <!-- Favicon -->
    <link href="{{ asset('assets/img/logouis.png') }}" rel="icon" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --purple:        #5520B8;
            --purple-dark:   #3D1788;
            --purple-light:  #6B35D4;
            --purple-pale:   #EDE7FF;
            --yellow:        #F5C518;
            --yellow-dark:   #D4A800;
            --yellow-pale:   #FFF8DC;
            --white:         #ffffff;
            --gray-50:       #f8f9fa;
            --gray-100:      #f1f3f5;
            --gray-200:      #e9ecef;
            --gray-400:      #9ca3af;
            --gray-600:      #6b7280;
            --gray-900:      #1a1a2e;
            --shadow-card:   0 20px 60px rgba(85, 32, 184, 0.20);
        }

        /* ===== RESET ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* ===== FULL PAGE ===== */
        .page {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        /* ===========================
           LEFT PANEL
        =========================== */
        .left-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 15% 35%, rgba(107, 53, 212, 0.35) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 75%, rgba(245, 197, 24, 0.10) 0%, transparent 50%),
                linear-gradient(145deg, #1a0a3d 0%, #2c1276 40%, #3D1788 70%, #1e0c50 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 5% 0 6%;
        }

        /* Dot grid overlay */
        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.04) 1px, transparent 0);
            background-size: 30px 30px;
            pointer-events: none;
        }

        /* Decorative blobs */
        .blob-1, .blob-2 {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .blob-1 {
            width: 400px; height: 400px;
            top: -140px; right: -100px;
            background: radial-gradient(circle, rgba(107,53,212,0.25), transparent 70%);
            animation: floatBlob 10s ease-in-out infinite;
        }

        .blob-2 {
            width: 260px; height: 260px;
            bottom: -80px; left: -40px;
            background: radial-gradient(circle, rgba(245,197,24,0.10), transparent 70%);
            animation: floatBlob 8s ease-in-out infinite reverse;
        }

        @keyframes floatBlob {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-18px) scale(1.05); }
        }

        /* Decorative ring top-right */
        .deco-ring {
            position: absolute;
            top: 30px; right: 30px;
            width: 90px; height: 90px;
            border-radius: 50%;
            border: 2px solid rgba(245,197,24,0.20);
            display: flex; align-items: center; justify-content: center;
        }

        .deco-ring::before {
            content: '';
            width: 66px; height: 66px;
            border-radius: 50%;
            border: 1.5px solid rgba(245,197,24,0.12);
            position: absolute;
        }

        .deco-ring i {
            color: rgba(245,197,24,0.35);
            font-size: 1.4rem;
        }

        /* ===== LEFT CONTENT ===== */
        .left-content {
            position: relative;
            z-index: 2;
            max-width: 470px;
        }

        .univ-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(245,197,24,0.12);
            border: 1px solid rgba(245,197,24,0.25);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--yellow);
            margin-bottom: 20px;
        }

        .left-content h1 {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(1.55rem, 2.4vw, 2.35rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .left-content h1 .hl {
            background: linear-gradient(90deg, var(--yellow), #ffa31a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .left-content > p {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.65;
            margin-bottom: 24px;
        }

        /* Feature pills */
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-bottom: 24px;
        }

        .feature-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 0.74rem;
            color: rgba(255,255,255,0.75);
        }

        .feature-pill i { color: var(--yellow); font-size: 0.88rem; flex-shrink: 0; }

        /* Stats */
        .stats-row {
            display: flex;
            gap: 26px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.09);
        }

        .stat-item .val {
            font-family: 'Poppins', sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .stat-item .val sup { color: var(--yellow); font-size: 0.95rem; }
        .stat-item .lbl { font-size: 0.68rem; color: rgba(255,255,255,0.45); margin-top: 3px; }

        /* ===========================
           RIGHT PANEL
        =========================== */
        .right-panel {
            width: 420px;
            flex-shrink: 0;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 38px;
            position: relative;
            overflow: hidden;
        }

        /* Top accent bar */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-dark), var(--purple), var(--yellow));
        }

        /* ===== LOGO AREA ===== */
        .logo-area {
            text-align: center;
            margin-bottom: 18px;
        }

        .logo-img-wrap {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 4px 20px rgba(85,32,184,0.18), 0 0 0 3px rgba(85,32,184,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 11px;
            overflow: hidden;
            padding: 6px;
        }

        .logo-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-area h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 2px;
        }

        .logo-area p { font-size: 0.70rem; color: var(--gray-600); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .divider hr { flex: 1; border: none; border-top: 1px solid var(--gray-200); }
        .divider span { font-size: 0.70rem; color: var(--gray-400); font-weight: 500; white-space: nowrap; }

        /* ===== FORM ===== */
        .form-heading {
            font-family: 'Poppins', sans-serif;
            font-size: 1.12rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 3px;
        }

        .form-sub { font-size: 0.76rem; color: var(--gray-600); margin-bottom: 16px; }

        .field { margin-bottom: 13px; }

        .field label {
            display: block;
            font-size: 0.76rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }

        .input-wrap { position: relative; }

        .input-wrap i.icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.88rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap input {
            width: 100%;
            height: 41px;
            padding: 0 36px 0 35px;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.84rem;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background: #fff;
            outline: none;
            transition: all 0.22s ease;
        }

        .input-wrap input:focus {
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(85,32,184,0.10);
        }

        .input-wrap:focus-within i.icon { color: var(--purple); }

        .input-wrap input.is-invalid { border-color: #ef4444; }
        .input-wrap input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.10); }

        .btn-eye {
            position: absolute;
            right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 0.88rem;
            padding: 4px;
            line-height: 1;
            transition: color 0.2s;
        }

        .btn-eye:hover { color: var(--purple); }

        .err-text {
            font-size: 0.72rem;
            color: #ef4444;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Remember */
        .options-row {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.76rem;
            color: var(--gray-600);
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: var(--purple);
            cursor: pointer;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            height: 43px;
            background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple) 60%, var(--purple-light) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.87rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.28s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.14), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(85,32,184,0.40);
        }

        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.70; cursor: not-allowed; transform: none; }

        /* Spinner */
        .spin {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===== ALERTS ===== */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 10px 13px;
            border-radius: 9px;
            font-size: 0.76rem;
            margin-bottom: 13px;
            line-height: 1.45;
        }

        .alert i { font-size: 0.92rem; flex-shrink: 0; margin-top: 1px; }

        .alert-error  { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-success{ background: var(--purple-pale); border: 1px solid rgba(85,32,184,0.20); color: var(--purple-dark); }

        /* ===== CARD FOOTER ===== */
        .right-footer {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid var(--gray-200);
            margin-top: 14px;
        }

        .right-footer p { font-size: 0.67rem; color: var(--gray-400); }
        .right-footer strong { color: var(--purple); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100vw; padding: 0 26px; }
        }

        @media (max-height: 620px) {
            .logo-img-wrap { width: 56px; height: 56px; margin-bottom: 8px; }
            .logo-area { margin-bottom: 10px; }
            .form-heading { font-size: 1rem; }
            .form-sub { margin-bottom: 10px; }
            .field { margin-bottom: 8px; }
            .options-row { margin-bottom: 9px; }
            .right-footer { padding-top: 9px; margin-top: 9px; }
            .left-content h1 { font-size: 1.4rem; }
            .feature-grid { gap: 6px; margin-bottom: 16px; }
        }
    </style>
</head>

<body>
<div class="page">

    {{-- ===== LEFT PANEL ===== --}}
    <div class="left-panel">
        <div class="dot-grid"></div>
        <div class="blob-1"></div>
        <div class="blob-2"></div>

        <div class="deco-ring">
            <i class="bi bi-heart-pulse-fill"></i>
        </div>

        <div class="left-content">
            <div class="univ-badge">
                <i class="bi bi-geo-alt-fill"></i>
                Universitas Ibnu Sina — UIS
            </div>

            <h1>
                Sistem Akreditasi<br>
                <span class="hl">FIKES UIS</span>
            </h1>

            <p>
                Platform digital terintegrasi untuk pengelolaan dokumen
                akreditasi Fakultas Ilmu Kesehatan secara efisien,
                transparan, dan terstandarisasi.
            </p>

            <div class="feature-grid">
                <div class="feature-pill">
                    <i class="bi bi-folder2-open"></i>
                    <span>Manajemen Dokumen</span>
                </div>
                <div class="feature-pill">
                    <i class="bi bi-people-fill"></i>
                    <span>Multi Pengguna</span>
                </div>
                <div class="feature-pill">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Monitoring Real-time</span>
                </div>
                <div class="feature-pill">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Standar LAMDIK</span>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-item">
                    <div class="val">5<sup>+</sup></div>
                    <div class="lbl">Program Studi</div>
                </div>
                <div class="stat-item">
                    <div class="val">100<sup>%</sup></div>
                    <div class="lbl">Digital</div>
                </div>
                <div class="stat-item">
                    <div class="val">24<sup>/7</sup></div>
                    <div class="lbl">Akses Online</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT PANEL ===== --}}
    <div class="right-panel">

        {{-- Logo --}}
        <div class="logo-area">
            <div class="logo-img-wrap">
                <img src="{{ asset('assets/img/logouis.png') }}"
                     alt="Logo Universitas Ibnu Sina"
                     onerror="this.parentElement.innerHTML='<i class=\'bi bi-hospital-fill\' style=\'font-size:2rem;color:var(--purple)\'></i>'">
            </div>
            <h4>Fakultas Ilmu Kesehatan</h4>
            <p>Universitas Ibnu Sina — Batam</p>
        </div>

        <div class="divider">
            <hr>
            <span><i class="bi bi-shield-lock-fill"></i>&nbsp; Portal Akreditasi</span>
            <hr>
        </div>

        <div class="form-heading">Selamat Datang 👋</div>
        <p class="form-sub">Masuk menggunakan akun yang telah terdaftar</p>

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="alert alert-success" id="successAlert">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Form --}}
        <form id="loginForm" action="{{ route('login.proses') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="field">
                <label for="email">Alamat Email</label>
                <div class="input-wrap">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="nama@fikes.uis.ac.id"
                        autocomplete="email"
                        autofocus
                        required>
                    <i class="bi bi-envelope-fill icon"></i>
                </div>
                @error('email')
                    <div class="err-text"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required>
                    <i class="bi bi-lock-fill icon"></i>
                    <button type="button" class="btn-eye" id="togglePwd">
                        <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="err-text"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="bi bi-box-arrow-in-right" id="btnIcon"></i>
                <span id="btnText">Masuk ke Sistem</span>
                <div class="spin" id="spinner"></div>
            </button>
        </form>

        {{-- Footer --}}
        <div class="right-footer">
            <p><strong>FIKES UIS</strong> &copy; {{ date('Y') }} &mdash; Sistem Informasi Akreditasi v1.0</p>
        </div>

    </div>

</div>

<script>
    // Toggle show/hide password
    document.getElementById('togglePwd').addEventListener('click', () => {
        const pwd  = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        const show = pwd.type === 'password';
        pwd.type        = show ? 'text' : 'password';
        icon.className  = show ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('btnSubmit').disabled    = true;
        document.getElementById('btnIcon').style.display = 'none';
        document.getElementById('btnText').textContent   = 'Memproses...';
        document.getElementById('spinner').style.display = 'block';
    });

    // Auto-dismiss success alert
    const sa = document.getElementById('successAlert');
    if (sa) setTimeout(() => {
        sa.style.transition = 'opacity .4s';
        sa.style.opacity = '0';
        setTimeout(() => sa.remove(), 400);
    }, 4000);
</script>

</body>
</html>
