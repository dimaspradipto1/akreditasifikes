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
        background-color: #5520B8;
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
                <li class="breadcrumb-item active">K1 — Visi, Misi, Tujuan & Strategi</li>
            </ol>
        </nav>
        <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Kriteria 1 — Visi, Misi, Tujuan & Strategi</h1>
        <small class="text-muted">S1 Kesehatan Lingkungan (Sarjana) - 1 sub-kriteria pada kriteria ini</small>
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
                <h3 class="fw-bold m-0 text-success">1 dari 1</h3>
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
            <a class="nav-link active" href="#"><i class="bi bi-circle-fill text-primary"></i> K1 — VMTS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-circle-fill text-success"></i> K2 — Kurikulum</a>
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
    </ul>

    <!-- Main Content Card -->
    <div class="card shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #5520B8 !important;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #e2e8f0;">
            <h5 class="m-0 fw-bold" style="color: #1e293b; font-size: 1.1rem;">
                <span class="me-3">1.1</span> Pernyataan Visi, Misi, Tujuan, dan Strategi
            </h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">WAJIB</span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle-fill me-1"></i> Memenuhi</span>
                <small class="text-muted ms-2" style="font-size: 0.75rem;">Narasi {{ $pctNarasi }}% &nbsp; Bukti {{ $pctBukti }}%</small>
                <div class="progress ms-2" style="width: 60px; height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($pctNarasi + $pctBukti) / 2 }}%"></div>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            
            <!-- BAGIAN A -->
            <div class="mb-5">
                <div class="section-title">Bagian A — Panduan Elemen Utama (EU) & Draf Narasi</div>
                <div class="section-subtitle">6 Elemen Utama pada sub-kriteria ini. Setiap EU memuat form narasi 5 blok (A-E) beserta status & simulasi pemenuhan otomatis.</div>

                <div class="accordion" id="accordionEU">
                    @foreach($narasis as $kode => $narasi)
                    <div class="accordion-item mb-2" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header d-flex align-items-center" id="heading{{ $narasi->id }}" style="background-color: #fff;">
                            <button class="accordion-button collapsed flex-grow-1 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $narasi->id }}" aria-expanded="false" aria-controls="collapse{{ $narasi->id }}" style="background: transparent;">
                                <span class="badge bg-primary bg-opacity-10 text-primary me-3">{{ $narasi->elemen_kode }}</span>
                                <span class="text-dark" style="font-weight: 500;">{{ $narasi->elemen_nama }}</span>
                            </button>
                            <div class="me-3" style="min-width: 130px; z-index: 2;">
                                <select name="status" form="form-narasi-{{ $narasi->id }}" class="form-select form-select-sm" style="font-size: 0.85rem; font-weight: 500; border-radius: 6px; border-color: #cbd5e1; cursor: pointer; color: #334155; {{ $narasi->status == 'Lengkap' ? 'background-color: #f0fdf4; color: #166534; border-color: #bbf7d0;' : '' }}" onchange="document.getElementById('form-narasi-{{ $narasi->id }}').submit();">
                                    <option value="Lengkap" {{ $narasi->status == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                    <option value="Draft" {{ $narasi->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Belum Diisi" {{ $narasi->status == 'Belum Diisi' ? 'selected' : '' }}>Belum Diisi</option>
                                </select>
                            </div>
                        </h2>
                        <div id="collapse{{ $narasi->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $narasi->id }}" data-bs-parent="#accordionEU">
                            <div class="accordion-body bg-white p-4">
                                <form id="form-narasi-{{ $narasi->id }}" action="{{ route('vmts.narasi.update', $narasi->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">A. Deskripsi Kondisi Saat Ini</label>
                                        <textarea class="form-control" name="kondisi_saat_ini" rows="2" placeholder="Jelaskan kondisi saat ini...">{{ $narasi->kondisi_saat_ini }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">B. Data & Fakta Pendukung</label>
                                        <textarea class="form-control" name="data_fakta" rows="2" placeholder="Sebutkan data/fakta...">{{ $narasi->data_fakta }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">C. Analisis Capaian vs Standar</label>
                                        <textarea class="form-control" name="analisis" rows="2" placeholder="Analisis capaian terhadap standar LAM-PTKes...">{{ $narasi->analisis }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">D. Permasalahan/Kelemahan</label>
                                        <textarea class="form-control" name="permasalahan" rows="2" placeholder="Permasalahan yang ditemukan...">{{ $narasi->permasalahan }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: #334155;">E. Rencana Perbaikan & Pengembangan</label>
                                        <textarea class="form-control" name="rencana_perbaikan" rows="2" placeholder="Rencana tindak lanjut...">{{ $narasi->rencana_perbaikan }}</textarea>
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

            <!-- BAGIAN B -->
            <div>
                <div class="section-title">Bagian B — Daftar Bukti Pendukung</div>
                <div class="section-subtitle">Dokumen bukti diperlukan - badge level menandai siapa yang mengisi (PRODI = tim prodi, FIKES/UNIV = otomatis dari Dokumen Bersama).</div>
                
                <div class="mb-3 text-end">
                    <button class="btn btn-sm text-white" style="background: #5520B8;" data-bs-toggle="modal" data-bs-target="#modalTambahBukti">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Bukti
                    </button>
                </div>

                <div class="table-responsive">
                    {{ $dataTable->table(['class' => 'table table-hover table-bordered align-middle', 'style' => 'font-size: 0.85rem; width:100%;']) }}
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Modal Tambah Bukti -->
<div class="modal fade" id="modalTambahBukti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('vmts.bukti.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vmts_id" value="{{ $vmts->id }}">
                <div class="modal-header">
                    <h5 class="modal-title fs-6 fw-bold">Tambah Bukti Pendukung</h5>
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
                        <select name="status" class="form-select" required>
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

@endsection

@push('scripts')
{{ $dataTable->scripts() }}
@endpush
