@extends('layouts.dashboard.template')

@section('title', 'Dashboard')

@section('content')
<style>
    .metric-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 100%;
    }
    .metric-title {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .progress-wrapper {
        margin-bottom: 1.5rem;
    }
    .progress-wrapper .kriteria-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.2rem;
    }
    .progress-wrapper .kriteria-labels {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.4rem;
        display: flex;
        gap: 1rem;
    }
    .custom-progress {
        height: 6px;
        background-color: #f1f5f9;
        border-radius: 4px;
        margin-bottom: 4px;
        overflow: hidden;
    }
    .custom-progress-bar {
        height: 100%;
        border-radius: 4px;
    }
    .bg-success-custom { background-color: #16a34a; }
    .bg-warning-custom { background-color: #d97706; }
    .bg-danger-custom { background-color: #dc2626; }
    
    .card-custom {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .card-title-custom {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 0.25rem;
    }
    .card-subtitle-custom {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }
    
    .doc-row {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .doc-label {
        width: 250px;
        font-size: 0.9rem;
        color: #334155;
        font-weight: 500;
    }
    .doc-progress-container {
        flex-grow: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .doc-progress {
        flex-grow: 1;
        height: 8px;
        background-color: #f1f5f9;
        border-radius: 4px;
        overflow: hidden;
    }
    .doc-value {
        width: 80px;
        text-align: right;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e3a8a;
    }
    
    .activity-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #334155;
    }
    .activity-row:last-child {
        border-bottom: none;
    }
    .activity-time {
        color: #94a3b8;
        white-space: nowrap;
        margin-left: 1rem;
    }
    
    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
    }
</style>

@php
    function getColorClass($value) {
        if ($value >= 80) return 'bg-success-custom';
        if ($value >= 50) return 'bg-warning-custom';
        return 'bg-danger-custom';
    }
@endphp

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item text-muted" style="font-size: 0.85rem;">Dashboard / {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard Kesiapan Akreditasi</h1>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }}) - FIKes Universitas XYZ - Instrumen Baru LAM-PTKes 2025</p>
        </div>
        <div class="text-end" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editJadwalModal" title="Klik untuk edit jadwal">
            <div class="text-muted" style="font-size: 0.8rem;">Sisa waktu pengajuan <i class="bi bi-pencil-square ms-1"></i></div>
            <div class="fw-bold {{ $sisaHari < 0 ? 'text-danger' : 'text-warning' }}" style="font-size: 1.25rem;">
                {{ $sisaHari < 0 ? 'Waktu Habis' : $sisaHari . ' hari' }}
            </div>
        </div>
    </div>

    <!-- 4 Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="metric-card bg-light">
                <div class="metric-title">Proyeksi Status Akreditasi</div>
                <div class="metric-value {{ $proyeksi_warna ?? 'text-success' }}">{{ $proyeksi_status ?? 'Unggul' }} 4 Tahun</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-light">
                <div class="metric-title">Rata-rata % Narasi Lengkap</div>
                <div class="metric-value">{{ round($avg_narasi_total ?? 81) }}%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-light">
                <div class="metric-title">Rata-rata % Bukti Tersedia</div>
                <div class="metric-value">{{ round($avg_bukti_total ?? 70) }}%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-light">
                <div class="metric-title">Sub Kriteria WAJIB Belum Memenuhi</div>
                <div class="metric-value text-success">{{ $wajib_belum_memenuhi ?? 0 }} dari {{ $wajib_total ?? 17 }}</div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4 mb-4">
        <!-- Progress Kriteria -->
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="card-title-custom">Progres Narasi & Bukti per Kriteria</div>
                <div class="card-subtitle-custom">Klik nama kriteria di sidebar untuk membuka detail & mengisi</div>
                
                <div class="mt-4">
                    @foreach($kriteria_stats as $key => $stat)
                    <div class="progress-wrapper">
                        <div class="kriteria-title">{{ $key }}. {{ $stat['nama'] }}</div>
                        <div class="kriteria-labels">
                            <span class="{{ $stat['narasi'] >= 80 ? 'text-success' : ($stat['narasi'] >= 50 ? 'text-warning' : 'text-danger') }}">Narasi {{ $stat['narasi'] }}%</span>
                            <span class="{{ $stat['bukti'] >= 80 ? 'text-success' : ($stat['bukti'] >= 50 ? 'text-warning' : 'text-danger') }}">Bukti {{ $stat['bukti'] }}%</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar {{ getColorClass($stat['narasi']) }}" style="width: {{ $stat['narasi'] }}%"></div>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar {{ getColorClass($stat['bukti']) }}" style="width: {{ $stat['bukti'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Radar Chart -->
        <div class="col-lg-5">
            <div class="card-custom h-100">
                <div class="card-title-custom">Skor Capaian (Radar 8 Kriteria)</div>
                <div class="card-subtitle-custom">Rata-rata % narasi + % bukti per kriteria</div>
                
                <div id="radarChart" style="min-height: 400px; width: 100%;" class="echart mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Dokumen Bersama -->
    <div class="card-custom mb-4">
        <div class="card-title-custom">Dokumen Bersama (Universitas & FIKes)</div>
        <div class="card-subtitle-custom">Diisi satu kali oleh Tim LPM/Rektorat & Dekanat/GPM FIKes — otomatis terbaca oleh halaman Kriteria</div>
        
        <div class="mt-4">
            <!-- UNIV -->
            <div class="doc-row">
                <div class="doc-label">
                    <div>Dokumen Universitas</div>
                    <div class="text-muted" style="font-size: 0.75rem;">(DOK UNIV)<br>Diisi Tim LPM/Rektorat</div>
                </div>
                <div class="doc-progress-container">
                    <div class="doc-progress">
                        <div class="custom-progress-bar bg-success-custom" style="width: {{ $dokumen['univ']['pct'] ?? 0 }}%"></div>
                    </div>
                    <div class="doc-value">{{ $dokumen['univ']['tersedia'] ?? 0 }}/{{ $dokumen['univ']['total'] ?? 15 }} ({{ $dokumen['univ']['pct'] ?? 0 }}%)</div>
                </div>
            </div>
            
            <!-- FIKES -->
            <div class="doc-row">
                <div class="doc-label">
                    <div>Dokumen FIKes</div>
                    <div class="text-muted" style="font-size: 0.75rem;">(DOK FIKES)<br>Diisi Tim Dekanat/GPM FIKes</div>
                </div>
                <div class="doc-progress-container">
                    <div class="doc-progress">
                        <div class="custom-progress-bar bg-success-custom" style="width: {{ $dokumen['fikes']['pct'] ?? 0 }}%"></div>
                    </div>
                    <div class="doc-value">{{ $dokumen['fikes']['tersedia'] ?? 0 }}/{{ $dokumen['fikes']['total'] ?? 15 }} ({{ $dokumen['fikes']['pct'] ?? 0 }}%)</div>
                </div>
            </div>
            
            <a href="{{ route('dokumen-bersama.index') }}" class="text-decoration-underline mt-2 d-inline-block" style="font-size: 0.85rem; color: #5520B8; font-weight: 500;">Lihat detail Dokumen Bersama →</a>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="card-custom mb-4">
        <div class="card-title-custom" style="margin-bottom: 1.5rem;">Aktivitas Terbaru</div>
        
        <div>
            @foreach($aktivitas as $act)
            <div class="activity-row">
                <div>{!! $act['teks'] !!}</div>
                <div class="activity-time">{{ $act['waktu'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div class="d-flex gap-4 text-muted" style="font-size: 0.75rem; font-weight: 500; margin-top: 1rem;">
        <div><span class="legend-dot bg-success-custom"></span> Memenuhi / Lengkap</div>
        <div><span class="legend-dot bg-warning-custom"></span> Boleh sebagian - belum lengkap</div>
        <div><span class="legend-dot bg-danger-custom"></span> Syarat perlu (WAJIB) belum memenuhi</div>
    </div>

</div>

<!-- Load ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Data dari Controller
        const kriteriaData = @json($kriteria_stats);
        
        // Menyiapkan data untuk chart
        const indicator = [
            { name: 'Visi', max: 100 },
            { name: 'Kurikulum', max: 100 },
            { name: 'Penilaian', max: 100 },
            { name: 'Mahasiswa', max: 100 },
            { name: 'Dosen', max: 100 },
            { name: 'Sarana', max: 100 },
            { name: 'Penjaminan Mutu', max: 100 },
            { name: 'Tata Kelola', max: 100 }
        ];
        
        // Rata-rata per kriteria
        const dataValues = [
            (kriteriaData['K1'].narasi + kriteriaData['K1'].bukti) / 2,
            (kriteriaData['K2'].narasi + kriteriaData['K2'].bukti) / 2,
            (kriteriaData['K3'].narasi + kriteriaData['K3'].bukti) / 2,
            (kriteriaData['K4'].narasi + kriteriaData['K4'].bukti) / 2,
            (kriteriaData['K5'].narasi + kriteriaData['K5'].bukti) / 2,
            (kriteriaData['K6'].narasi + kriteriaData['K6'].bukti) / 2,
            (kriteriaData['K7'].narasi + kriteriaData['K7'].bukti) / 2,
            (kriteriaData['K8'].narasi + kriteriaData['K8'].bukti) / 2,
        ];
        
        const myChart = echarts.init(document.querySelector("#radarChart"));
        
        myChart.setOption({
            radar: {
                indicator: indicator,
                splitArea: {
                    show: false
                },
                axisName: {
                    color: '#64748b',
                    fontSize: 10
                },
                splitLine: {
                    lineStyle: {
                        color: ['#e2e8f0']
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#e2e8f0'
                    }
                }
            },
            series: [{
                name: 'Skor Capaian',
                type: 'radar',
                data: [
                    {
                        value: dataValues,
                        name: 'Skor',
                        areaStyle: {
                            color: 'rgba(34, 197, 94, 0.2)'
                        },
                        lineStyle: {
                            color: '#22c55e',
                            width: 2
                        },
                        itemStyle: {
                            color: '#22c55e',
                            borderColor: '#eab308',
                            borderWidth: 2
                        }
                    }
                ]
            }],
            tooltip: {
                trigger: 'item'
            }
        });
        
        // Resize chart on window resize
        window.addEventListener('resize', function() {
            myChart.resize();
        });
    });
</script>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.jadwal.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editJadwalModalLabel">Pengaturan Jadwal Akreditasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="akreditasi_start_date" class="form-label">Tanggal Mulai Pengajuan</label>
                        <input type="date" class="form-control" id="akreditasi_start_date" name="akreditasi_start_date" value="{{ $akreditasi_start_date ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="akreditasi_end_date" class="form-label">Tanggal Target / Akhir</label>
                        <input type="date" class="form-control" id="akreditasi_end_date" name="akreditasi_end_date" value="{{ $akreditasi_end_date ?? '' }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
