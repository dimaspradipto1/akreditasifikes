@extends('layouts.dashboard.template')

@section('content')
<style>
    .kriteria-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px 20px;
        border: none;
        height: 100%;
    }
    .nav-pills-kriteria .nav-link {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-radius: 0;
    }
    .nav-pills-kriteria .nav-link.active {
        color: #185fa5;
        border-bottom: 2px solid #185fa5;
        background: transparent;
    }
    .nav-pills-kriteria .nav-link i {
        font-size: 0.5rem;
        vertical-align: middle;
        margin-right: 5px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f1f5f9;
        color: #1e293b;
        box-shadow: none;
    }
    .accordion-button {
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: #334155;
    }
    .eu-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        margin-left: auto;
        margin-right: 15px;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .section-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 1rem;
    }
</style>

<div class="pagetitle d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav>
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">S1 Kesehatan Lingkungan</li>
                <li class="breadcrumb-item active">K3 — Penilaian</li>
            </ol>
        </nav>
        <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Kriteria 3 — Penilaian</h1>
        <small class="text-muted">S1 Kesehatan Lingkungan (Sarjana) - {{ $subKriterias->count() }} sub-kriteria pada kriteria ini</small>
    </div>
    <div class="text-end">
        <div class="text-muted mb-1" style="font-size: 0.85rem;">Status simulasi kriteria</div>
        <div class="fs-4 fw-bold text-success" style="line-height: 1;">Baik</div>
    </div>
</div>

<section class="section">
    <!-- Ringkasan Metrik -->
    <div class="row g-3 mb-4">
        <!-- Progress Narasi -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #eef2ff; color: #4f46e5;">
                        <i class="bi bi-card-text fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted mb-1" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Progress Narasi</div>
                        <div class="fs-4 fw-bold text-dark" style="line-height: 1;">{{ $pctNarasi }}%</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bukti Tersedia -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #eff6ff; color: #2563eb;">
                        <i class="bi bi-folder-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted mb-1" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Bukti Tersedia</div>
                        <div class="fs-4 fw-bold text-dark" style="line-height: 1;">{{ $pctBukti }}%</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sub Memenuhi -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #ecfdf5; color: #16a34a;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted mb-1" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Sub Memenuhi</div>
                        <div class="fs-4 fw-bold text-success" style="line-height: 1;">{{ $narasis->where('status', 'Memenuhi')->count() }} <span class="text-muted fw-normal fs-6">dari {{ $subKriterias->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wajib Belum -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #fef2f2; color: #dc2626;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted mb-1" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Wajib Belum</div>
                        <div class="fs-4 fw-bold text-danger" style="line-height: 1;">{{ $narasis->where('status', '!=', 'Memenuhi')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Horizontal Navigation -->
    <ul class="nav nav-pills nav-pills-kriteria mb-4 border-bottom pb-3">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('vmts.*') ? 'active' : '' }}" href="{{ route('vmts.index') }}"><i class="bi bi-circle-fill text-success"></i> K1 — VMTS</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('kurikulum.*') ? 'active' : '' }}" href="{{ route('kurikulum.index') }}"><i class="bi bi-circle-fill text-success"></i> K2 — Kurikulum</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}" href="{{ route('penilaian.index') }}"><i class="bi bi-circle-fill text-success"></i> K3 — Penilaian</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}" href="{{ route('mahasiswa.index') }}"><i class="bi bi-circle-fill text-warning"></i> K4 — Mahasiswa</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('doenpkm.*') ? 'active' : '' }}" href="{{ route('doenpkm.index') }}"><i class="bi bi-circle-fill text-warning"></i> K5 — Dosen & PkM</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('sarpraskeuangan.*') ? 'active' : '' }}" href="{{ route('sarpraskeuangan.index') }}"><i class="bi bi-circle-fill text-success"></i> K6 — Sarpras & Keu</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('mutu.*') ? 'active' : '' }}" href="{{ route('mutu.index') }}"><i class="bi bi-circle-fill text-success"></i> K7 — Mutu</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('tatakelola.*') ? 'active' : '' }}" href="{{ route('tatakelola.index') }}"><i class="bi bi-circle-fill text-warning"></i> K8 — Tata Kelola</a></li>
    </ul>

    <div class="accordion mb-5" id="accordionPenilaian">
        @foreach($subKriterias as $kode => $sub)
        @php
            $euKriterias = $narasis->filter(fn($n, $k) => str_starts_with($k, $sub->kriteria_kode . '_EU'));
            $hasEU = $euKriterias->isNotEmpty();
        @endphp
        <div class="accordion-item mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; background-color: #fff; border: 1px solid #e2e8f0 !important;">
            
            <h2 class="accordion-header d-flex align-items-center" id="headingSub{{ $sub->id }}" style="border-bottom: 1px solid #e2e8f0;">
                <button class="accordion-button collapsed flex-grow-1 shadow-none bg-white py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub{{ $sub->id }}" aria-expanded="false" aria-controls="collapseSub{{ $sub->id }}">
                    <span class="text-primary fw-bold me-3" style="font-size: 1.1rem;">{{ $sub->kriteria_kode }}</span>
                    <span class="text-dark fw-bold flex-grow-1" style="font-size: 1.1rem;">{{ $sub->kriteria_nama }}</span>
                    <div class="ms-3 d-flex align-items-center gap-3" style="z-index: 2;">
                        @if($sub->kriteria_kode == '3.2')
                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">BOLEH SEBAGIAN</span>
                        @else
                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">WAJIB</span>
                        @endif
                        <span class="badge rounded-pill {{ $sub->status == 'Memenuhi' ? 'bg-success bg-opacity-10 text-success' : ($sub->status == 'Memenuhi Sebagian' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-danger bg-opacity-10 text-danger') }}" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;"><i class="bi {{ $sub->status == 'Memenuhi' ? 'bi-check-circle-fill' : ($sub->status == 'Memenuhi Sebagian' ? 'bi-circle-fill' : 'bi-x') }} me-1"></i> {{ $sub->status == 'Belum Memenuhi' ? 'Tidak Memenuhi' : $sub->status }}</span>
                        
                        <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                            <span class="me-2">Narasi {{ $sub->narasi_persen ?? 0 }}%</span>
                            <span class="bukti-pct-display">Bukti {{ $sub->bukti_persen ?? 0 }}%</span>
                        </div>
                        
                        <div class="progress" style="width: 80px; height: 6px; border-radius: 4px;">
                            <div class="progress-bar bg-success progress-bar-combined" data-narasi-pct="{{ $sub->narasi_persen ?? 0 }}" role="progressbar" style="width: {{ (($sub->narasi_persen ?? 0) + ($sub->bukti_persen ?? 0)) / 2 }}%"></div>
                        </div>
                    </div>
                </button>
            </h2>

            <div id="collapseSub{{ $sub->id }}" class="accordion-collapse collapse" aria-labelledby="headingSub{{ $sub->id }}" data-bs-parent="#accordionPenilaian">
                <div class="accordion-body p-4" style="background-color: #f8fafc;">
                    
                    @if($hasEU)
                        <!-- Bagian A untuk Elemen Utama -->
                        <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #1e3a8a;">Bagian A — Panduan Elemen Utama (EU) & Draf Narasi</h5>
                        <p class="text-muted mb-4" style="font-size: 0.85rem;">{{ $euKriterias->count() }} Elemen Utama pada sub-kriteria ini. Setiap EU memuat form narasi 5 blok (A-E) beserta status & simulasi pemenuhan otomatis.</p>

                        <div class="accordion mb-5" id="accordionEU{{ $sub->id }}">
                            @foreach($euKriterias as $euKode => $euNarasi)
                            @php
                                $displayEuKode = explode('_', $euNarasi->kriteria_kode)[1] ?? $euNarasi->kriteria_kode;
                            @endphp
                            <div class="accordion-item mb-2" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                <h2 class="accordion-header d-flex align-items-center" id="headingEU{{ $euNarasi->id }}" style="background-color: #fff;">
                                    <button class="accordion-button collapsed flex-grow-1 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEU{{ $euNarasi->id }}" aria-expanded="false" aria-controls="collapseEU{{ $euNarasi->id }}" style="background: transparent;">
                                        <span class="badge bg-primary bg-opacity-10 text-primary me-3">{{ $displayEuKode }}</span>
                                        <span class="text-dark" style="font-weight: 500; flex-grow: 1;">{{ $euNarasi->kriteria_nama }}</span>
                                    </button>
                                    <div class="me-3" style="min-width: 170px; z-index: 2;">
                                        <select name="status" form="form-narasi-{{ $euNarasi->id }}" class="form-select form-select-sm" style="font-size: 0.85rem; font-weight: 500; border-radius: 6px; border-color: #cbd5e1; cursor: pointer; color: #334155; {{ $euNarasi->status == 'Lengkap' ? 'background-color: #f0fdf4; color: #166534; border-color: #bbf7d0;' : '' }}">
                                            <option value="Lengkap" {{ $euNarasi->status == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                            <option value="Draft" {{ $euNarasi->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="Belum Diisi" {{ $euNarasi->status == 'Belum Diisi' ? 'selected' : '' }}>Belum Diisi</option>
                                        </select>
                                    </div>
                                </h2>
                                <div id="collapseEU{{ $euNarasi->id }}" class="accordion-collapse collapse" aria-labelledby="headingEU{{ $euNarasi->id }}" data-bs-parent="#accordionEU{{ $sub->id }}">
                                    <div class="accordion-body bg-white p-4">
                                        <form id="form-narasi-{{ $euNarasi->id }}" action="{{ route('penilaian.update', $euNarasi->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="narasi">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">A. Deskripsi Kondisi Saat Ini</label>
                                                <textarea class="form-control" name="kondisi_saat_ini" rows="2" placeholder="Tulis deskripsi kondisi saat ini...">{{ $euNarasi->kondisi_saat_ini }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">B. Data & Fakta Pendukung</label>
                                                <textarea class="form-control" name="data_fakta" rows="2" placeholder="Tulis data & fakta pendukung...">{{ $euNarasi->data_fakta }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">C. Analisis Capaian vs Standar</label>
                                                <textarea class="form-control" name="analisis" rows="2" placeholder="Tulis analisis capaian vs standar...">{{ $euNarasi->analisis }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">D. Permasalahan/Kelemahan</label>
                                                <textarea class="form-control" name="permasalahan" rows="2" placeholder="Tulis permasalahan/kelemahan...">{{ $euNarasi->permasalahan }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">E. Rencana Perbaikan & Pengembangan</label>
                                                <textarea class="form-control" name="rencana_perbaikan" rows="2" placeholder="Tulis rencana perbaikan & pengembangan...">{{ $euNarasi->rencana_perbaikan }}</textarea>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-3 mt-4 border-top">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="simulasi{{ $euNarasi->id }}" checked disabled>
                                                    <label class="form-check-label fw-bold {{ $euNarasi->status == 'Lengkap' ? 'text-success' : 'text-warning' }}" for="simulasi{{ $euNarasi->id }}" style="font-size: 0.85rem;">
                                                        Simulasi Pemenuhan EU: {{ $euNarasi->status == 'Lengkap' ? 'Memenuhi' : 'Belum Lengkap' }}
                                                    </label>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <button type="submit" class="btn btn-sm text-white" style="background:#185fa5;">Simpan Narasi</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Form Standar A-E untuk sub-kriteria tanpa EU -->
                        <div class="bg-white p-4 rounded shadow-sm mb-5" style="border: 1px solid #e2e8f0;">
                            <form action="{{ route('penilaian.update', $sub->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="narasi">
                                
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">Status Pemenuhan</label>
                                    <select name="status" class="form-select" style="max-width: 300px;">
                                        <option value="Memenuhi" {{ $sub->status == 'Memenuhi' ? 'selected' : '' }}>Memenuhi</option>
                                        <option value="Memenuhi Sebagian" {{ $sub->status == 'Memenuhi Sebagian' ? 'selected' : '' }}>Memenuhi Sebagian</option>
                                        <option value="Belum Memenuhi" {{ $sub->status == 'Belum Memenuhi' ? 'selected' : '' }}>Belum Memenuhi</option>
                                        <option value="Belum Diisi" {{ $sub->status == 'Belum Diisi' ? 'selected' : '' }}>Belum Diisi</option>
                                    </select>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">Persentase Narasi (%)</label>
                                        <input type="number" name="narasi_persen" class="form-control" value="{{ $sub->narasi_persen }}" min="0" max="100">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">Persentase Bukti (%)</label>
                                        <input type="number" name="bukti_persen" class="form-control" value="{{ $sub->bukti_persen }}" min="0" max="100">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">A. Deskripsi Kondisi Saat Ini</label>
                                    <textarea class="form-control" name="kondisi_saat_ini" rows="2" placeholder="Jelaskan kondisi saat ini...">{{ $sub->kondisi_saat_ini }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">B. Data & Fakta Pendukung</label>
                                    <textarea class="form-control" name="data_fakta" rows="2" placeholder="Sebutkan data/fakta...">{{ $sub->data_fakta }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">C. Analisis Capaian vs Standar</label>
                                    <textarea class="form-control" name="analisis" rows="2" placeholder="Analisis capaian terhadap standar LAM-PTKes...">{{ $sub->analisis }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">D. Permasalahan/Kelemahan</label>
                                    <textarea class="form-control" name="permasalahan" rows="2" placeholder="Permasalahan yang ditemukan...">{{ $sub->permasalahan }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">E. Rencana Perbaikan & Pengembangan</label>
                                    <textarea class="form-control" name="rencana_perbaikan" rows="2" placeholder="Rencana tindak lanjut...">{{ $sub->rencana_perbaikan }}</textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end align-items-center pt-3 mt-4 border-top">
                                    <button type="submit" class="btn btn-sm text-white" style="background:#185fa5;">Simpan Narasi</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Bagian B untuk Daftar Bukti Pendukung (Ditampilkan untuk semua sub-kriteria) -->
                    <h5 class="fw-bold mb-1 mt-4" style="font-size: 1rem; color: #1e3a8a;">Bagian B — Daftar Bukti Pendukung</h5>
                    @php
                        $docCount = 8;
                        $plusCount = 4;
                    @endphp
                    <p class="text-muted mb-4" style="font-size: 0.85rem;">{{ $docCount }} dokumen bukti diperlukan · badge level menandai siapa yang mengisi (PRODI = tim prodi, FIKES/UNIV = otomatis dari Dokumen Bersama).</p>
                    
                    <div class="bg-white p-3 rounded shadow-sm" style="border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-sm text-white" style="background: #185fa5;" data-bs-toggle="modal" data-bs-target="#modalTambahBukti{{ $sub->id }}">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Bukti
                            </button>
                        </div>
                        <div class="table-responsive">
                            @if($penilaian->buktis->where('kriteria_kode', $sub->kriteria_kode)->isEmpty())
                                <table class="table table-borderless table-hover align-middle" style="font-size: 0.85rem; margin-bottom: 0;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <th class="py-3">Nama Bukti</th>
                                            <th class="py-3">Level</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3">Link</th>
                                            <th class="py-3">PIC</th>
                                            <th class="py-3">Deadline</th>
                                            <th class="py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada dokumen bukti.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-borderless table-hover align-middle" style="font-size: 0.85rem; margin-bottom: 0;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <th class="py-3" style="width: 5%;">No</th>
                                            <th class="py-3" style="width: 25%;">Nama Bukti</th>
                                            <th class="py-3">Level</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3">Link</th>
                                            <th class="py-3">PIC</th>
                                            <th class="py-3">Deadline</th>
                                            <th class="py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penilaian->buktis->where('kriteria_kode', $sub->kriteria_kode) as $index => $bukti)
                                        <tr style="border-bottom: 1px solid #f1f5f9;" class="bukti-row" data-id="{{ $bukti->id }}">
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td class="fw-medium text-dark">{{ $bukti->nama_bukti }}</td>
                                            <td>
                                                @if($bukti->level == 'PRODI')
                                                    <span class="badge rounded-pill" style="background-color: #fef08a; color: #854d0e; font-weight: 600; padding: 0.35rem 0.6rem;">{{ $bukti->level }}</span>
                                                @elseif($bukti->level == 'FIKES')
                                                    <span class="badge rounded-pill" style="background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 0.35rem 0.6rem;">{{ $bukti->level }}</span>
                                                @else
                                                    <span class="badge rounded-pill" style="background-color: #dbeafe; color: #1e40af; font-weight: 600; padding: 0.35rem 0.6rem;">{{ $bukti->level }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <select name="status_bukti" class="form-select form-select-sm shadow-none bukti-input" style="width: 120px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155; border-radius: 6px; cursor: pointer;">
                                                    <option value="Tersedia" {{ $bukti->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                    <option value="Tidak Ada" {{ $bukti->status == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                    <option value="Belum Memenuhi" {{ $bukti->status == 'Belum Memenuhi' ? 'selected' : '' }}>Belum Memenuhi</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="link" class="form-control form-control-sm shadow-none bukti-input" placeholder="tautan / lokasi file" value="{{ $bukti->link }}" style="font-size: 0.8rem; border-color: #cbd5e1; border-radius: 6px; min-width: 160px;">
                                            </td>
                                            <td>
                                                <input type="text" name="pic" class="form-control form-control-sm shadow-none bukti-input" placeholder="nama PIC" value="{{ $bukti->pic }}" style="font-size: 0.8rem; border-color: #cbd5e1; border-radius: 6px; min-width: 120px;">
                                            </td>
                                            <td>
                                                <input type="date" name="deadline" class="form-control form-control-sm shadow-none text-muted bukti-input" value="{{ $bukti->deadline }}" style="font-size: 0.8rem; border-color: #cbd5e1; border-radius: 6px; width: 130px;">
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <button type="button" class="btn btn-sm {{ $bukti->catatan ? 'text-primary' : 'text-secondary' }} p-1 border-0 shadow-none" title="Catatan" style="background:transparent;" data-bs-toggle="modal" data-bs-target="#modalCatatanBukti{{ $bukti->id }}">
                                                        <i class="bi bi-chat-left-text{{ $bukti->catatan ? '-fill' : '' }}" style="font-size:14px;"></i>
                                                    </button>
                                                    <form action="{{ route('penilaian.destroy', $bukti->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bukti ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="type" value="bukti">
                                                        <button type="submit" class="btn btn-sm btn-danger py-0 px-1" title="Hapus"><i class="bi bi-trash-fill" style="font-size:11px;"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Belum ada dokumen bukti.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        
                        <!-- Modals Catatan Bukti -->
                        @foreach($penilaian->buktis->where('kriteria_kode', $sub->kriteria_kode) as $bukti)
                            <div class="modal fade" id="modalCatatanBukti{{ $bukti->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('penilaian.update', $bukti->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="type" value="bukti">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-6 fw-bold">Catatan Bukti Pendukung</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2 text-muted" style="font-size: 0.85rem;">
                                                    Nama Bukti: <span class="fw-medium text-dark">{{ $bukti->nama_bukti }}</span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium" style="font-size: 0.85rem;">Catatan / Keterangan</label>
                                                    <textarea name="catatan" class="form-control" rows="4" placeholder="Tulis catatan atau keterangan tambahan di sini...">{{ $bukti->catatan }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-sm text-white" style="background:#5520B8;">Simpan Catatan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($plusCount > 0)
                        <div class="mt-3 text-muted" style="font-size: 0.8rem; font-style: italic;">
                            + {{ $plusCount }} dokumen bukti lain pada sub-kriteria ini — lihat Tracker Terpusat untuk rincian lengkap.
                        </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Modal Tambah Bukti per Sub -->
        <div class="modal fade" id="modalTambahBukti{{ $sub->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('penilaian.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="bukti">
                        <input type="hidden" name="penilaian_id" value="{{ $penilaian->id ?? 1 }}">
                        <input type="hidden" name="kriteria_kode" value="{{ $sub->kriteria_kode }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Dokumen Bukti ({{ $sub->kriteria_kode }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Dokumen Bukti</label>
                                <input type="text" name="nama_bukti" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level Tanggung Jawab</label>
                                <select name="level" class="form-select" required>
                                    <option value="PRODI">PRODI</option>
                                    <option value="FIKES">FIKES</option>
                                    <option value="UNIV">UNIV</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status_bukti" class="form-select" required>
                                    <option value="Tersedia">Tersedia</option>
                                    <option value="Tidak Ada">Tidak Ada</option>
                                    <option value="Belum Memenuhi">Belum Memenuhi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tautan / Link File</label>
                                <input type="url" name="link" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PIC (Penanggung Jawab)</label>
                                <input type="text" name="pic" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" name="deadline" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Bukti</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="d-flex flex-wrap align-items-center mt-4 gap-3 px-1" style="font-size: 0.85rem;">
        <div class="d-flex align-items-center gap-2">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:#198754;"></span>
            <span class="text-muted">Memenuhi / Lengkap</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:#ffc107;"></span>
            <span class="text-muted">Memenuhi Sebagian</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background-color:#dc3545;"></span>
            <span class="text-muted">Tidak Ada / Belum Memenuhi</span>
        </div>
        
        <span class="badge rounded-pill" style="background-color: #fef08a; color: #854d0e; font-weight: 600; padding: 0.35rem 0.6rem;">PRODI</span>
        <span class="badge rounded-pill" style="background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 0.35rem 0.6rem;">FIKES</span>
        <span class="badge rounded-pill" style="background-color: #dbeafe; color: #1e40af; font-weight: 600; padding: 0.35rem 0.6rem;">UNIV</span>
        
        <span class="badge rounded-pill" style="background-color: #fee2e2; color: #991b1b; font-weight: 600; padding: 0.35rem 0.6rem;">WAJIB</span>
        <span class="badge rounded-pill" style="background-color: #fef3c7; color: #92400e; font-weight: 600; padding: 0.35rem 0.6rem;">BOLEH SEBAGIAN</span>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.bukti-input').on('change', function() {
                var $row = $(this).closest('tr.bukti-row');
                var buktiId = $row.data('id');
                var fieldName = $(this).attr('name');
                var fieldValue = $(this).val();
                
                var data = {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    type: 'bukti'
                };
                data[fieldName] = fieldValue;
                
                $.ajax({
                    url: '/penilaian/' + buktiId,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Flash green background temporarily to show save success
                        $row.css('background-color', '#d1fae5');
                        setTimeout(function() {
                            $row.css('background-color', 'transparent');
                        }, 800);
                        
                        // Update percentages dynamically if returned
                        if(response.pctBukti !== undefined && response.kriteria_kode !== undefined) {
                            var heading = $row.closest('.accordion-item').find('.accordion-header');
                            heading.find('.bukti-pct-display').text('Bukti ' + response.pctBukti + '%');
                            
                            var progressBar = heading.find('.progress-bar-combined');
                            var narasiPct = parseFloat(progressBar.data('narasi-pct')) || 0;
                            var combined = (narasiPct + response.pctBukti) / 2;
                            progressBar.css('width', combined + '%');
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal menyimpan perubahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endpush
@endsection
