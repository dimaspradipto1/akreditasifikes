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
        border-radius: 20px;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .nav-pills-kriteria .nav-link.active {
        background-color: #185fa5; /* Modified from VMTS color */
        color: white;
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
                <li class="breadcrumb-item active">K2 — Kurikulum</li>
            </ol>
        </nav>
        <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Kriteria 2 — Kurikulum</h1>
        <small class="text-muted">S1 Kesehatan Lingkungan (Sarjana) - 4 sub-kriteria pada kriteria ini</small>
    </div>
    <div class="text-end">
        <small class="text-muted d-block mb-1">Status simulasi kriteria</small>
        <h4 class="text-success fw-bold m-0">Baik</h4>
    </div>
</div>

<section class="section">
    <!-- Top KPI Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="kriteria-card">
                <small class="text-muted d-block mb-2">% Narasi Lengkap (Kriteria)</small>
                <h3 class="fw-bold m-0">{{ $pctNarasi }}%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kriteria-card">
                <small class="text-muted d-block mb-2">% Bukti Tersedia (Kriteria)</small>
                <h3 class="fw-bold m-0">{{ $pctBukti }}%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kriteria-card">
                <small class="text-muted d-block mb-2">Sub-Kriteria Memenuhi</small>
                <h3 class="fw-bold m-0 text-success">4 dari 4</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kriteria-card">
                <small class="text-muted d-block mb-2">Sub-Kriteria WAJIB Belum Memenuhi</small>
                <h3 class="fw-bold m-0 text-success">0</h3>
            </div>
        </div>
    </div>

    <!-- Horizontal Navigation -->
    <ul class="nav nav-pills nav-pills-kriteria mb-4 border-bottom pb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('vmts.index') }}"><i class="bi bi-circle-fill text-primary"></i> K1 — VMTS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#"><i class="bi bi-circle-fill text-success"></i> K2 — Kurikulum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-secondary"></i> K3 — Penilaian</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-warning"></i> K4 — Mhs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-info"></i> K5 — Dosen&PkM</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-danger"></i> K6 — Sarpras&Keu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-success"></i> K7 — Mutu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-warning"></i> K8 — Tata Kelola</a>
        </li>
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Akreditasi S1</a></li>
                    <li class="breadcrumb-item active fw-medium text-dark" aria-current="page">K2 — Kurikulum</li>
                </ol>
            </nav>
            <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">Kriteria 2 — Kurikulum</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Kelola dokumen narasi dan bukti pendukung untuk standar Kurikulum LAM-PTKes.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light border shadow-sm btn-sm px-3 fw-medium">
                <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Export PDF
            </button>
            <button class="btn btn-primary shadow-sm btn-sm px-3 fw-medium" style="background-color: #185fa5; border: none;">
                <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Semua
            </button>
        </div>
    </div>

    <!-- Ringkasan Metrik -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(145deg, #ffffff, #f8fafc);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #e0e7ff; color: #4f46e5;">
                        <i class="bi bi-card-text fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Progress Narasi</div>
                        <div class="fs-4 fw-bold text-dark">{{ $pctNarasi }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: linear-gradient(145deg, #ffffff, #f8fafc);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #dcfce7; color: #16a34a;">
                        <i class="bi bi-folder-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Ketersediaan Bukti</div>
                        <div class="fs-4 fw-bold text-dark">{{ $pctBukti }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #fff8f1;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background-color: #ffedd5; color: #ea580c;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Status</div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Masih ada {{ $narasis->where('status', '!=', 'Memenuhi')->count() }} sub-kriteria belum lengkap</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $subKriterias = $narasis->filter(fn($n, $kode) => preg_match('/^2\.\d+$/', $kode));
    @endphp

    <!-- OUTER ACCORDION FOR 2.1 to 2.4 -->
    <div class="accordion mb-5" id="accordionKurikulum">
        @foreach($subKriterias as $kode => $sub)
        @php
            $euKriterias = $narasis->filter(fn($n, $k) => str_starts_with($k, $sub->kriteria_kode . '_EU'));
            $hasEU = $euKriterias->isNotEmpty();
        @endphp
        <div class="accordion-item mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; background-color: #fff;">
            
            <h2 class="accordion-header d-flex align-items-center" id="headingSub{{ $sub->id }}" style="border-bottom: 1px solid #e2e8f0;">
                <button class="accordion-button collapsed flex-grow-1 shadow-none bg-white py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub{{ $sub->id }}" aria-expanded="false" aria-controls="collapseSub{{ $sub->id }}">
                    <span class="text-primary fw-bold me-3" style="font-size: 1.1rem;">{{ $sub->kriteria_kode }}</span>
                    <span class="text-dark fw-bold flex-grow-1" style="font-size: 1.1rem;">{{ $sub->kriteria_nama }}</span>
                </button>
                <div class="me-4 d-flex align-items-center gap-3" style="z-index: 2;">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger" style="font-size: 0.7rem; padding: 0.3rem 0.5rem;">WAJIB</span>
                    <span class="badge {{ $sub->status == 'Memenuhi' ? 'bg-success bg-opacity-10 text-success border border-success' : 'bg-warning bg-opacity-10 text-warning border border-warning' }}" style="font-size: 0.7rem; padding: 0.3rem 0.5rem;"><i class="bi {{ $sub->status == 'Memenuhi' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} me-1"></i> {{ $sub->status }}</span>
                    
                    <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                        <span class="me-2">Narasi {{ $sub->narasi_persen ?? 0 }}%</span>
                        <span>Bukti {{ $sub->bukti_persen ?? 0 }}%</span>
                    </div>
                    
                    <div class="progress" style="width: 80px; height: 6px; border-radius: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ (($sub->narasi_persen ?? 0) + ($sub->bukti_persen ?? 0)) / 2 }}%"></div>
                    </div>
                </div>
            </h2>

            <div id="collapseSub{{ $sub->id }}" class="accordion-collapse collapse" aria-labelledby="headingSub{{ $sub->id }}" data-bs-parent="#accordionKurikulum">
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
                                        <select name="status" form="form-narasi-{{ $euNarasi->id }}" class="form-select form-select-sm" style="font-size: 0.85rem; font-weight: 500; border-radius: 6px; border-color: #cbd5e1; cursor: pointer; color: #334155; {{ $euNarasi->status == 'Lengkap' ? 'background-color: #f0fdf4; color: #166534; border-color: #bbf7d0;' : '' }}" onchange="document.getElementById('form-narasi-{{ $euNarasi->id }}').submit();">
                                            <option value="Lengkap" {{ $euNarasi->status == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                            <option value="Draft" {{ $euNarasi->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="Belum Diisi" {{ $euNarasi->status == 'Belum Diisi' ? 'selected' : '' }}>Belum Diisi</option>
                                        </select>
                                    </div>
                                </h2>
                                <div id="collapseEU{{ $euNarasi->id }}" class="accordion-collapse collapse" aria-labelledby="headingEU{{ $euNarasi->id }}" data-bs-parent="#accordionEU{{ $sub->id }}">
                                    <div class="accordion-body bg-white p-4">
                                        <form id="form-narasi-{{ $euNarasi->id }}" action="{{ route('kurikulum.narasi.update', $euNarasi->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $euNarasi->status }}">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">A. Deskripsi Kondisi Saat Ini</label>
                                                <textarea class="form-control" name="kondisi_saat_ini" rows="2" placeholder="Jelaskan kondisi saat ini...">{{ $euNarasi->kondisi_saat_ini }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">B. Data & Fakta Pendukung</label>
                                                <textarea class="form-control" name="data_fakta" rows="2" placeholder="Sebutkan data/fakta...">{{ $euNarasi->data_fakta }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">C. Analisis Capaian vs Standar</label>
                                                <textarea class="form-control" name="analisis" rows="2" placeholder="Analisis capaian terhadap standar LAM-PTKes...">{{ $euNarasi->analisis }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">D. Permasalahan/Kelemahan</label>
                                                <textarea class="form-control" name="permasalahan" rows="2" placeholder="Permasalahan yang ditemukan...">{{ $euNarasi->permasalahan }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">E. Rencana Perbaikan & Pengembangan</label>
                                                <textarea class="form-control" name="rencana_perbaikan" rows="2" placeholder="Rencana tindak lanjut...">{{ $euNarasi->rencana_perbaikan }}</textarea>
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

                        <!-- Bagian B untuk Daftar Bukti Pendukung -->
                        <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #1e3a8a;">Bagian B — Daftar Bukti Pendukung</h5>
                        @php
                            $docCount = 6;
                            if ($sub->kriteria_kode == '2.1') $docCount = 16;
                            if ($sub->kriteria_kode == '2.3') $docCount = 9;
                            if ($sub->kriteria_kode == '2.4') $docCount = 4;
                            
                            $plusCount = 1;
                            if ($sub->kriteria_kode == '2.1') $plusCount = 9;
                            if ($sub->kriteria_kode == '2.4') $plusCount = 0;
                        @endphp
                        <p class="text-muted mb-4" style="font-size: 0.85rem;">{{ $docCount }} dokumen bukti diperlukan · badge level menandai siapa yang mengisi (PRODI = tim prodi, FIKES/UNIV = otomatis dari Dokumen Bersama).</p>
                        
                        <div class="bg-white p-3 rounded shadow-sm" style="border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-end mb-3">
                                <button class="btn btn-sm text-white" style="background: #185fa5;" data-bs-toggle="modal" data-bs-target="#modalTambahBukti">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Bukti
                                </button>
                            </div>
                            <div class="table-responsive">
                                @if($sub->kriteria_kode == '2.1')
                                    {!! $dataTable->table(['class' => 'table table-borderless table-hover align-middle', 'style' => 'font-size: 0.85rem; margin-bottom: 0;']) !!}
                                @else
                                    <table class="table table-borderless table-hover align-middle" style="font-size: 0.85rem; margin-bottom: 0;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <th class="py-3">No</th>
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
                                            @forelse($kurikulum->buktis as $index => $bukti)
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td class="fw-medium text-dark">{{ $bukti->nama_bukti }}</td>
                                                <td>
                                                    @if($bukti->level == 'PRODI')
                                                        <span class="badge bg-warning text-dark">{{ $bukti->level }}</span>
                                                    @elseif($bukti->level == 'FIKES')
                                                        <span class="badge bg-success">{{ $bukti->level }}</span>
                                                    @else
                                                        <span class="badge bg-primary">{{ $bukti->level }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bukti->status == 'Tersedia')
                                                        <strong class="text-success">{{ $bukti->status }}</strong>
                                                    @elseif($bukti->status == 'Tidak Ada')
                                                        <strong class="text-danger">{{ $bukti->status }}</strong>
                                                    @else
                                                        <strong class="text-warning">{{ $bukti->status }}</strong>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($bukti->link)
                                                        <a href="{{ $bukti->link }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;"><i class="bi bi-link-45deg"></i> Link</a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $bukti->pic ?: '-' }}</td>
                                                <td class="text-muted">{{ $bukti->deadline ? \Carbon\Carbon::parse($bukti->deadline)->format('d M Y') : '-' }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        <button type="button" class="btn btn-sm btn-warning text-white py-0 px-1" title="Edit"><i class="bi bi-pencil-fill" style="font-size:11px;"></i></button>
                                                        <form action="{{ route('kurikulum.bukti.destroy', $bukti->id) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus bukti ini?');">
                                                            @csrf
                                                            @method('DELETE')
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
                            @if($plusCount > 0)
                            <div class="mt-3 text-muted" style="font-size: 0.8rem; font-style: italic;">
                                + {{ $plusCount }} dokumen bukti lain pada sub-kriteria ini — lihat Tracker Terpusat untuk rincian lengkap.
                            </div>
                            @endif
                        </div>
                    @else
                        <!-- Form Standar A-E untuk sub-kriteria tanpa EU -->
                        <div class="bg-white p-4 rounded shadow-sm" style="border: 1px solid #e2e8f0;">
                            <form id="form-narasi-{{ $sub->id }}" action="{{ route('kurikulum.narasi.update', $sub->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">Status Pemenuhan</label>
                                    <select name="status" class="form-select" style="max-width: 300px;">
                                        <option value="Memenuhi" {{ $sub->status == 'Memenuhi' ? 'selected' : '' }}>Memenuhi</option>
                                        <option value="Memenuhi Sebagian" {{ $sub->status == 'Memenuhi Sebagian' ? 'selected' : '' }}>Memenuhi Sebagian</option>
                                        <option value="Belum Memenuhi" {{ $sub->status == 'Belum Memenuhi' ? 'selected' : '' }}>Belum Memenuhi</option>
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
                    
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Bukti -->
<div class="modal fade" id="modalTambahBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kurikulum.bukti.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kurikulum_id" value="{{ $kurikulum->id ?? 1 }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokumen Bukti</h5>
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
                        <select name="status" class="form-select" required>
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

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
@endsection
