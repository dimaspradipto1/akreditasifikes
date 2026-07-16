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
    .nav-pills-kriteria .nav-link:focus,
    .nav-pills-kriteria .nav-link:active {
        outline: none !important;
        box-shadow: none !important;
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
                <li class="breadcrumb-item active">K5 — Dosen & PkM</li>
            </ol>
        </nav>
        <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Kriteria 5 — Dosen & PkM</h1>
        <small class="text-muted">S1 Kesehatan Lingkungan (Sarjana) - {{ count($elements) }} sub-kriteria pada kriteria ini</small>
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
                        <div class="fs-4 fw-bold text-success" style="line-height: 1;">{{ $narasis->where('status', 'Memenuhi')->count() }} <span class="fs-6 fw-normal text-muted">dari {{ count($elements) }}</span></div>
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

    <div class="accordion mb-5" id="accordionDoenpkm">
        @foreach($elements as $subKode => $subData)
            @php
                // Hitung persen per sub-kriteria
                $subNarasis = $narasis->filter(function($n, $k) use ($subKode) {
                    return str_starts_with($k, $subKode . '-');
                });
                $subTotal = $subNarasis->count();
                $subPctNarasi = $subTotal > 0 ? (int) round($subNarasis->avg('narasi_persen')) : 0;
                $subPctBukti = $subTotal > 0 ? (int) round($subNarasis->avg('bukti_persen')) : 0;
                $isMemenuhi = $subPctNarasi == 100 && $subPctBukti >= 50;
                $isTidakMemenuhi = !$isMemenuhi;
            @endphp
            <div class="accordion-item mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; background-color: #fff; border: 1px solid #e2e8f0;">
                <h2 class="accordion-header" id="headingSub_{{ str_replace('.', '_', $subKode) }}">
                    <button class="accordion-button collapsed flex-grow-1 shadow-none bg-white py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub_{{ str_replace('.', '_', $subKode) }}" aria-expanded="false" aria-controls="collapseSub_{{ str_replace('.', '_', $subKode) }}">
                        <span class="text-primary fw-bold me-3" style="font-size: 1.1rem;">{{ $subKode }}</span>
                        <span class="text-dark fw-bold flex-grow-1" style="font-size: 1.1rem;">{{ $subData['title'] }}</span>
                        
                        <div class="ms-3 d-flex align-items-center gap-3" style="z-index: 2;">
                            @if($subData['type'] == 'WAJIB')
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">WAJIB</span>
                            @else
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">BOLEH SEBAGIAN</span>
                            @endif

                            @if($isMemenuhi)
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;"><i class="bi bi-check-circle-fill me-1"></i> Memenuhi</span>
                            @else
                                @if($subData['type'] == 'WAJIB')
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;"><i class="bi bi-x-circle-fill me-1"></i> Tidak Memenuhi</span>
                                @else
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;"><i class="bi bi-circle-fill text-warning me-1" style="font-size:8px;"></i> Memenuhi Sebagian</span>
                                @endif
                            @endif
                            
                            <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                <span class="me-2">Narasi {{ $subPctNarasi }}%</span>
                                <span class="bukti-pct-display">Bukti {{ $subPctBukti }}%</span>
                            </div>
                            
                            <div class="progress" style="width: 80px; height: 6px; border-radius: 4px;">
                                <div class="progress-bar {{ $isMemenuhi ? 'bg-success' : ($subData['type'] == 'WAJIB' ? 'bg-danger' : 'bg-warning') }} progress-bar-combined" data-narasi-pct="{{ $subPctNarasi }}" role="progressbar" style="width: {{ ($subPctNarasi + $subPctBukti) / 2 }}%"></div>
                            </div>
                        </div>
                    </button>
                </h2>

                <div id="collapseSub_{{ str_replace('.', '_', $subKode) }}" class="accordion-collapse collapse" aria-labelledby="headingSub_{{ str_replace('.', '_', $subKode) }}" data-bs-parent="#accordionDoenpkm">
                    <div class="accordion-body p-4" style="background-color: #fff; border-left: 3px solid #5520B8;">
                        
                        <!-- BAGIAN A -->
                        <div class="mb-5">
                            <div class="section-title">Bagian A — Panduan Elemen Utama (EU) & Draf Narasi</div>
                            <div class="section-subtitle">{{ count($subData['eus']) }} Elemen Utama pada sub-kriteria ini. Setiap EU memuat form narasi 5 blok (A-E) beserta status & simulasi pemenuhan otomatis.</div>

                            <div class="accordion" id="accordionEU_{{ str_replace('.', '_', $subKode) }}">
                                @foreach($subData['eus'] as $kode => $nama)
                                    @php
                                        $narasi = $narasis[$kode] ?? null;
                                        if(!$narasi) continue;
                                        $euLabel = explode('-', $kode)[1]; // Get EU1, EU2 etc.
                                    @endphp
                                <div class="accordion-item mb-2" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                    <h2 class="accordion-header d-flex align-items-center" id="heading{{ $narasi->id }}" style="background-color: #fff;">
                                        <button class="accordion-button collapsed flex-grow-1 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $narasi->id }}" aria-expanded="false" aria-controls="collapse{{ $narasi->id }}" style="background: transparent;">
                                            <span class="badge bg-primary bg-opacity-10 text-primary me-3">{{ str_replace('EU', 'EU-', $euLabel) }}</span>
                                            <span class="text-dark" style="font-weight: 500;">{{ $nama }}</span>
                                        </button>
                                        <div class="me-3" style="min-width: 130px; z-index: 2;">
                                            <select name="status" form="form-narasi-{{ $narasi->id }}" class="form-select form-select-sm" style="font-size: 0.85rem; font-weight: 500; border-radius: 6px; border-color: #cbd5e1; cursor: pointer; color: #334155; {{ $narasi->status == 'Lengkap' ? 'background-color: #f0fdf4; color: #166534; border-color: #bbf7d0;' : '' }}">
                                                <option value="Lengkap" {{ $narasi->status == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                                <option value="Draft" {{ $narasi->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="Belum Diisi" {{ $narasi->status == 'Belum Diisi' ? 'selected' : '' }}>Belum Diisi</option>
                                            </select>
                                        </div>
                                    </h2>
                                    <div id="collapse{{ $narasi->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $narasi->id }}" data-bs-parent="#accordionEU_{{ str_replace('.', '_', $subKode) }}">
                                        <div class="accordion-body bg-white p-4">
                                            <form id="form-narasi-{{ $narasi->id }}" action="{{ route('doenpkm.narasi.update', $narasi->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">A. Deskripsi Kondisi Saat Ini</label>
                                                    <textarea class="form-control" name="kondisi_saat_ini" rows="2" placeholder="Jelaskan kondisi saat ini...">{{ $narasi->kondisi_saat_ini ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">B. Data & Fakta Pendukung</label>
                                                    <textarea class="form-control" name="data_fakta" rows="2" placeholder="Sebutkan data/fakta...">{{ $narasi->data_fakta ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">C. Analisis Capaian vs Standar</label>
                                                    <textarea class="form-control" name="analisis" rows="2" placeholder="Analisis capaian terhadap standar LAM-PTKes...">{{ $narasi->analisis ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">D. Permasalahan/Kelemahan</label>
                                                    <textarea class="form-control" name="permasalahan" rows="2" placeholder="Permasalahan yang ditemukan...">{{ $narasi->permasalahan ?? '' }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">E. Rencana Perbaikan & Pengembangan</label>
                                                    <textarea class="form-control" name="rencana_perbaikan" rows="2" placeholder="Rencana tindak lanjut...">{{ $narasi->rencana_perbaikan ?? '' }}</textarea>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center pt-3 mt-4 border-top">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="simulasi{{ $narasi->id }}" checked disabled>
                                                        <label class="form-check-label text-success fw-bold" for="simulasi{{ $narasi->id }}" style="font-size: 0.85rem;">
                                                            Simulasi Pemenuhan EU: Memenuhi
                                                        </label>
                                                    </div>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <button type="submit" class="btn btn-sm text-white" style="background:#5520B8;">Simpan Narasi</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bagian B -->
                        <div class="mb-5">
                            <div class="section-title mb-1">Bagian B — Daftar Bukti Pendukung</div>
                            <div class="section-subtitle mb-3">Dokumen bukti diperlukan · badge level menandai siapa yang mengisi (PRODI = tim prodi, FIKES/UNIV = otomatis dari Dokumen Bersama).</div>
                            
                            <div class="mb-3 text-end">
                                <button class="btn btn-sm text-white" style="background: #5520B8;" data-bs-toggle="modal" data-bs-target="#modalTambahBukti{{ str_replace('.', '_', $subKode) }}">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Bukti
                                </button>
                            </div>
                            
                            <div class="table-responsive bg-white rounded shadow-sm" style="border: 1px solid #e2e8f0; padding: 1rem;">
                                @php
                                    $subBuktis = collect($buktis[$subKode] ?? []);
                                @endphp
                                @if($subBuktis->isEmpty())
                                    <table class="table table-borderless table-hover align-middle" style="font-size: 0.85rem; margin-bottom: 0;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                                                <th class="py-3">Nama Bukti</th>
                                                <th class="py-3">Level</th>
                                                <th class="py-3">Status</th>
                                                <th class="py-3">Link</th>
                                                <th class="py-3">PIC</th>
                                                <th class="py-3">Deadline</th>
                                                <th class="py-3 text-center">Catatan</th>
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
                                            @foreach($subBuktis as $bukti)
                                            <tr style="border-bottom: 1px solid #f1f5f9;" class="bukti-row" data-id="{{ $bukti->id }}">
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
                                                        <form action="{{ route('doenpkm.bukti.destroy', $bukti->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bukti ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger py-0 px-1" title="Hapus"><i class="bi bi-trash-fill" style="font-size:11px;"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    <!-- Modals Catatan Bukti -->
                                    @foreach($subBuktis as $bukti)
                                        <div class="modal fade" id="modalCatatanBukti{{ $bukti->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('doenpkm.bukti.update', $bukti->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
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
                                @endif
                                
                                <div class="mt-3 text-muted" style="font-size: 0.75rem; font-style: italic;">
                                    + dokumen bukti lain pada sub-kriteria ini — lihat Tracker Terpusat untuk rincian lengkap.
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Bukti -->
                        <div class="modal fade" id="modalTambahBukti{{ str_replace('.', '_', $subKode) }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('doenpkm.bukti.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="doenpkm_id" value="{{ $doenpkm->id }}">
                                        <!-- Bukti applies to the whole sub-kriteria, e.g. 5.1 -->
                                        <input type="hidden" name="elemen_kode" value="{{ $subKode }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title fs-6 fw-bold">Tambah Bukti Pendukung ({{ $subKode }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Bukti <span class="text-danger">*</span></label>
                                                <input type="text" name="nama_bukti" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Level <span class="text-danger">*</span></label>
                                                <select name="level" class="form-select" required>
                                                    <option value="PRODI">PRODI</option>
                                                    <option value="FIKES">FIKES</option>
                                                    <option value="UNIV">UNIV</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                                <select name="status_bukti" class="form-select" required>
                                                    <option value="Tersedia">Tersedia</option>
                                                    <option value="Tidak Ada">Tidak Ada</option>
                                                    <option value="Belum Memenuhi">Belum Memenuhi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Link Dokumen</label>
                                                <input type="url" name="link" class="form-control" placeholder="https://...">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">PIC</label>
                                                <input type="text" name="pic" class="form-control" placeholder="Nama Penanggung Jawab">
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
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white btn-sm" style="background:#5520B8;">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
</section>

@endsection

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
                    _method: 'PUT'
                };
                data[fieldName] = fieldValue;
                
                $.ajax({
                    url: '/doenpkm/bukti/' + buktiId,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Flash green background temporarily to show save success
                        $row.css('background-color', '#d1fae5');
                        setTimeout(function() {
                            $row.css('background-color', 'transparent');
                        }, 800);

                        // If response contains new percentage, we could update UI dynamically.
                        // However, since DoenPKM aggregates per sub-kriteria (e.g. 5.1), 
                        // a full page refresh is more reliable to update the top KPI cards and progress bars.
                        // For now, we will reload the page to ensure all aggregated stats are accurate.
                        window.location.reload();
                    },
                    error: function(xhr) {
                        var msg = 'Gagal menyimpan perubahan. Silakan coba lagi.';
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            msg = 'Validasi gagal: \n';
                            for (var key in errors) {
                                msg += '- ' + errors[key][0] + '\n';
                            }
                        } else if (xhr.status === 500) {
                            msg = 'Terjadi kesalahan server (500).';
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
