@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle mb-4">
    <nav>
        <ol class="breadcrumb" style="font-size: 0.85rem; margin-bottom: 0.5rem; background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</li>
            <li class="breadcrumb-item active">Tracker Bukti</li>
        </ol>
    </nav>
    <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Tracker Bukti Terpusat</h1>
    <small class="text-muted">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }}) - Rekap seluruh dokumen bukti dari 8 Kriteria — halaman ini hanya MEMBACA, bukan tempat input</small>
</div>

<section class="section dashboard">
    <!-- Top Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Total Dokumen Bukti</div>
                    <div class="fs-3 fw-bold">{{ $totalBukti }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Level PRODI</div>
                    <div class="fs-3 fw-bold text-darken" style="color: #b4850d;">{{ $prodiCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Level FIKES (otomatis)</div>
                    <div class="fs-3 fw-bold text-success">{{ $fikesCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Level UNIV (otomatis)</div>
                    <div class="fs-3 fw-bold text-primary">{{ $univCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 d-flex align-items-center mb-4" role="alert" style="border-radius: 10px; font-size: 0.85rem;">
        <i class="bi bi-info-circle-fill me-3 text-primary fs-5"></i>
        <div>
            Untuk mengubah status/link: level <strong class="text-dark">PRODI</strong> → edit di halaman Kriteria terkait. 
            Level <strong class="text-dark">UNIV</strong> → edit di Dokumen Bersama (bagian Universitas). 
            Level <strong class="text-dark">FIKES</strong> → edit di Dokumen Bersama (bagian FIKes).
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            
            <!-- Filters & Search -->
            <div class="d-flex flex-column gap-3 mb-4">
                <!-- Kriteria Filter -->
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary px-4 filter-kriteria btn-sm rounded-pill" data-kriteria="all">Semua Kriteria</button>
                    @for($i=1; $i<=8; $i++)
                        <button type="button" class="btn btn-outline-secondary px-3 filter-kriteria btn-sm rounded-pill" data-kriteria="K{{$i}}">K{{$i}}</button>
                    @endfor
                </div>
                
                <!-- Level Filter & Search -->
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary px-4 filter-level btn-sm rounded-pill" data-level="all">Semua Level</button>
                        <button type="button" class="btn btn-outline-secondary px-3 filter-level btn-sm rounded-pill" data-level="PRODI">
                            <i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.6rem;"></i> PRODI
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-3 filter-level btn-sm rounded-pill" data-level="FIKES">
                            <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.6rem;"></i> FIKES
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-3 filter-level btn-sm rounded-pill" data-level="UNIV">
                            <i class="bi bi-circle-fill text-primary me-1" style="font-size: 0.6rem;"></i> UNIV
                        </button>
                    </div>
                    <div class="flex-grow-1" style="max-width: 400px;">
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari nama dokumen bukti..." style="border-radius: 20px;">
                    </div>
                </div>
            </div>

            <div class="text-muted mb-2" style="font-size: 0.75rem;">Menampilkan <span id="visibleCount">{{ $totalBukti }}</span> dari {{ $totalBukti }} dokumen bukti</div>

            <!-- Table -->
            <div class="table-responsive table-container">
                <table class="table table-hover align-middle mb-0" id="trackerTable">
                    <thead>
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th scope="col" width="8%">KRITERIA</th>
                            <th scope="col" width="8%">SUB-K</th>
                            <th scope="col" width="10%">KODE EU</th>
                            <th scope="col" width="40%">NAMA DOKUMEN / BUKTI</th>
                            <th scope="col" width="8%" class="text-center">LEVEL</th>
                            <th scope="col" width="16%" class="text-center">STATUS</th>
                            <th scope="col" width="10%" class="text-center">PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allBukti as $b)
                        <tr class="tracker-row" data-kriteria="{{ $b->kriteria }}" data-level="{{ $b->level }}">
                            <td class="fw-bold text-primary">{{ $b->kriteria }}</td>
                            <td class="text-muted">{{ $b->sub_k }}</td>
                            <td class="text-muted">{{ $b->kode_eu }}</td>
                            <td style="font-size: 0.9rem;" class="bukti-nama text-dark">{{ $b->nama_dokumen }}</td>
                            <td class="text-center">
                                @if($b->level == 'PRODI')
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-darken border border-warning" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">PRODI</span>
                                @elseif($b->level == 'FIKES')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">FIKES</span>
                                @else
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">UNIV</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(in_array($b->status, ['Belum Ada', 'Tidak Ada', 'Draft', 'Belum Diisi', 'Revisi']))
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger w-100 py-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-x fs-6 me-1"></i> Belum Ada
                                    </span>
                                @elseif(in_array($b->status, ['Otomatis']))
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-darken w-100 py-2 border border-warning border-opacity-25" style="font-size: 0.75rem;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Otomatis
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success w-100 py-2 border border-success border-opacity-25" style="font-size: 0.75rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted" style="font-size: 0.85rem;">{{ $b->pic }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Legend Bottom -->
            <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center gap-3" style="font-size: 0.8rem;">
                <span class="badge rounded-pill bg-warning bg-opacity-10 text-darken px-3 py-2 border border-warning">PRODI</span>
                <span class="text-muted">diisi tim {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</span>
                
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 border border-success ms-md-3">FIKES</span>
                <span class="text-muted">otomatis dari Dokumen Bersama FIKes</span>

                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary ms-md-3">UNIV</span>
                <span class="text-muted">otomatis dari Dokumen Bersama Universitas</span>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterKriteriaBtns = document.querySelectorAll('.filter-kriteria');
        const filterLevelBtns = document.querySelectorAll('.filter-level');
        const rows = document.querySelectorAll('.tracker-row');
        const searchInput = document.getElementById('searchInput');
        const visibleCountSpan = document.getElementById('visibleCount');

        let currentKriteria = 'all';
        let currentLevel = 'all';

        // Kriteria Filter
        filterKriteriaBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterKriteriaBtns.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');

                currentKriteria = this.getAttribute('data-kriteria');
                applyFilters();
            });
        });

        // Level Filter
        filterLevelBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterLevelBtns.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');

                currentLevel = this.getAttribute('data-level');
                applyFilters();
            });
        });

        // Search Input
        searchInput.addEventListener('input', applyFilters);

        function applyFilters() {
            const term = searchInput.value.toLowerCase();
            let count = 0;

            rows.forEach(row => {
                const kriteria = row.getAttribute('data-kriteria');
                const level = row.getAttribute('data-level');
                const name = row.querySelector('.bukti-nama').textContent.toLowerCase();
                
                const matchesKriteria = currentKriteria === 'all' || kriteria === currentKriteria;
                const matchesLevel = currentLevel === 'all' || level === currentLevel;
                const matchesSearch = name.includes(term);

                if (matchesKriteria && matchesLevel && matchesSearch) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            visibleCountSpan.textContent = count;
        }
    });
</script>
<style>
    /* Custom Styling */
    .text-darken { color: #b4850d !important; }
    
    /* Scrollable table */
    .table-container {
        max-height: 500px;
        overflow-y: auto;
    }
    .table-container thead th {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    /* Custom Scrollbar for better UI */
    .table-container::-webkit-scrollbar {
        width: 8px;
    }
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1; 
        border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8; 
    }
</style>
@endpush
