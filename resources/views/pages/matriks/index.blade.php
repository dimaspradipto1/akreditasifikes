@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle mb-4">
    <nav>
        <ol class="breadcrumb" style="font-size: 0.85rem; margin-bottom: 0.5rem; background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</li>
            <li class="breadcrumb-item active">Matriks 26 Sub-Kriteria</li>
        </ol>
    </nav>
    <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Matriks 26 Sub-Kriteria</h1>
    <small class="text-muted">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }}) - Peta navigasi seluruh sub-kriteria — klik nama sub-kriteria untuk membuka halaman Kriteria terkait</small>
</div>

<section class="section dashboard">
    <!-- Top Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Total Sub-Kriteria</div>
                    <div class="fs-3 fw-bold">{{ $totalSubKriteria }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Total Elemen Utama (EU)</div>
                    <div class="fs-3 fw-bold">{{ $totalEU }}</div>
                </div>
            </div>
        </div>
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
                    <div class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Wajib / Boleh Sebagian</div>
                    <div class="fs-3 fw-bold"><span class="text-danger">{{ $wajibCount }}</span> / <span class="text-warning text-darken">{{ $bolehSebagianCount }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <!-- Filter & Search Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary px-4 filter-btn" data-filter="all" style="border-radius: 20px 0 0 20px;">Semua Kategori</button>
                    <button type="button" class="btn btn-outline-secondary px-4 filter-btn" data-filter="WAJIB">
                        <i class="bi bi-circle-fill text-danger me-2" style="font-size: 0.6rem;"></i> WAJIB
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4 filter-btn" data-filter="BOLEH SEBAGIAN" style="border-radius: 0 20px 20px 0;">
                        <i class="bi bi-circle-fill text-warning me-2" style="font-size: 0.6rem;"></i> BOLEH SEBAGIAN
                    </button>
                </div>
                <div class="flex-grow-1">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama sub-kriteria..." style="border-radius: 20px;">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive table-container">
                <table class="table table-hover align-middle mb-0" id="matriksTable">
                    <thead>
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th scope="col" width="5%">NO</th>
                            <th scope="col" width="5%">KODE</th>
                            <th scope="col" width="25%">NAMA SUB-KRITERIA</th>
                            <th scope="col" width="15%">KRITERIA</th>
                            <th scope="col" width="5%" class="text-center">EU</th>
                            <th scope="col" width="5%" class="text-center">BUKTI</th>
                            <th scope="col" width="10%" class="text-center">KATEGORI</th>
                            <th scope="col" width="15%" class="text-center">SIMULASI PEMENUHAN</th>
                            <th scope="col" width="8%" class="text-center">% NARASI</th>
                            <th scope="col" width="8%" class="text-center">% BUKTI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matrix as $item)
                        @php
                            $routeMap = [
                                1 => 'vmts.index',
                                2 => 'kurikulum.index',
                                3 => 'penilaian.index',
                                4 => 'mahasiswa.index',
                                5 => 'doenpkm.index',
                                6 => 'sarpraskeuangan.index',
                                7 => 'mutu.index',
                                8 => 'tatakelola.index',
                            ];
                            $routeUrl = route($routeMap[$item->no]);
                        @endphp
                        <tr class="matriks-row" data-kategori="{{ $item->kategori }}">
                            <td class="text-muted">{{ $item->no }}</td>
                            <td class="fw-bold text-primary">{{ $item->kode }}</td>
                            <td>
                                <a href="{{ $routeUrl }}" class="text-primary text-decoration-none fw-medium matriks-nama" style="font-size: 0.9rem;">
                                    {{ $item->nama }}
                                </a>
                            </td>
                            <td class="text-muted" style="font-size: 0.85rem;">{{ $item->kriteria }}</td>
                            <td class="text-center">{{ $item->eu }}</td>
                            <td class="text-center">{{ $item->bukti }}</td>
                            <td class="text-center">
                                @if($item->kategori == 'WAJIB')
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">WAJIB</span>
                                @else
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning" style="font-size: 0.7rem; padding: 0.35rem 0.6rem;">BOLEH SEBAGIAN</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $item->simulasi == 'Memenuhi' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}" style="font-size: 0.75rem; padding: 0.4rem 0.7rem;">
                                    <i class="bi {{ $item->simulasi == 'Memenuhi' ? 'bi-check-circle-fill' : 'bi-x' }} me-1"></i> {{ $item->simulasi }}
                                </span>
                            </td>
                            <td class="text-center">{{ $item->pct_narasi }}%</td>
                            <td class="text-center">{{ $item->pct_bukti }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 pt-3 border-top d-flex align-items-center gap-3" style="font-size: 0.8rem;">
                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">WAJIB</span>
                <span class="text-muted">tidak boleh berstatus "memenuhi sebagian" — satu saja gagal membuat proyeksi jadi Tidak Terakreditasi</span>
                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 ms-3">BOLEH SEBAGIAN</span>
                <span class="text-muted">jumlah yang "sebagian" menentukan level Unggul (5/4/3 tahun)</span>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.matriks-row');
        const searchInput = document.getElementById('searchInput');

        // Filter by Category
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                filterBtns.forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary');

                const filterValue = this.getAttribute('data-filter');
                filterRows(filterValue, searchInput.value);
            });
        });

        // Search by Name
        searchInput.addEventListener('input', function() {
            const activeFilter = document.querySelector('.filter-btn.btn-primary').getAttribute('data-filter');
            filterRows(activeFilter, this.value);
        });

        function filterRows(category, searchTerm) {
            const term = searchTerm.toLowerCase();
            rows.forEach(row => {
                const name = row.querySelector('.matriks-nama').textContent.toLowerCase();
                const cat = row.getAttribute('data-kategori');
                
                const matchesCategory = category === 'all' || cat === category;
                const matchesSearch = name.includes(term);

                if (matchesCategory && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
<style>
    /* Styling to match screenshot slightly closer */
    .text-darken { color: #b4850d !important; }
    .bg-opacity-10 { opacity: 0.9; }

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
        box-shadow: 0 1px 2px rgba(0,0,0,0.05); /* Slight shadow to separate from content */
    }
</style>
@endpush
