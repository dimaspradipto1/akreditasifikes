@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle mb-4">
    <nav>
        <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</li>
            <li class="breadcrumb-item active">Laporan & Ekspor</li>
        </ol>
    </nav>
    <h1 class="mb-1" style="font-size: 1.5rem; font-weight: 700;">Laporan & Ekspor</h1>
    <small class="text-muted">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }}) Cetak/ekspor laporan kesiapan akreditasi per kriteria atau keseluruhan</small>
</div>

<section class="section">
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.8rem; font-weight: 600;">Rata-rata % Narasi</div>
                    <div class="fs-3 fw-bold text-dark">{{ round($total_narasi) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.8rem; font-weight: 600;">Rata-rata % Bukti</div>
                    <div class="fs-3 fw-bold text-dark">{{ round($total_bukti) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.8rem; font-weight: 600;">Proyeksi Status</div>
                    <div class="fs-4 fw-bold {{ $proyeksi_status == 'Tidak Memenuhi' ? 'text-danger' : 'text-success' }}">{{ $proyeksi_status }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body p-3">
                    <div class="text-muted mb-2" style="font-size: 0.8rem; font-weight: 600;">Terakhir Diperbarui</div>
                    <div class="fs-5 fw-bold text-dark mt-2">Baru saja</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Keseluruhan -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4">
            <h5 class="card-title p-0 mb-1 fw-bold text-dark">Laporan Keseluruhan</h5>
            <p class="text-muted small mb-4">Rekap 8 kriteria, 26 sub-kriteria, dan skor capaian {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} dalam satu dokumen</p>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('laporan.export.pdf', ['kriteria' => 'Keseluruhan']) }}" class="btn btn-primary px-4 py-2 fw-semibold" style="border-radius: 8px;">Unduh Laporan Lengkap (PDF)</a>
                <a href="{{ route('laporan.export.excel', ['kriteria' => 'Keseluruhan']) }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold" style="border-radius: 8px;">Unduh Rekap Skor (Excel)</a>
            </div>
        </div>
    </div>

    <!-- Laporan per Kriteria -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4">
            <h5 class="card-title p-0 mb-1 fw-bold text-dark">Laporan per Kriteria</h5>
            <p class="text-muted small mb-4">Unduh narasi Bagian A & daftar bukti Bagian B per kriteria untuk diserahkan ke asesor</p>
            
            <div class="table-responsive">
                <table class="table table-borderless align-middle" style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead>
                        <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th class="text-uppercase fw-bold pb-2" style="border-bottom: 1px solid #e2e8f0;">Kriteria</th>
                            <th class="text-uppercase fw-bold pb-2" style="border-bottom: 1px solid #e2e8f0;">Status Simulasi</th>
                            <th class="text-uppercase fw-bold pb-2 text-end" style="border-bottom: 1px solid #e2e8f0;">Ekspor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $k)
                        <tr>
                            <td class="fw-semibold text-primary" style="font-size: 0.95rem;">
                                {{ $k['id'] }} — {{ $k['name'] }}
                            </td>
                            <td>
                                @if($k['status'] == 'Baik')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">{{ $k['status'] }}</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill">{{ $k['status'] }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('laporan.export.pdf', ['kriteria' => $k['id']]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1">PDF</a>
                                <a href="{{ route('laporan.export.excel', ['kriteria' => $k['id']]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Excel</a>
                            </td>
                        </tr>
                        <tr><td colspan="3"><hr class="m-0 text-muted" style="opacity: 0.1"></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Laporan Dokumen Bersama & Tracker -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4">
            <h5 class="card-title p-0 mb-1 fw-bold text-dark">Laporan Dokumen Bersama & Tracker</h5>
            <p class="text-muted small mb-4">Termasuk status dokumen level FIKES/UNIV yang otomatis terbaca oleh prodi ini</p>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('laporan.export.excel', ['kriteria' => 'Tracker']) }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold" style="border-radius: 8px;">Ekspor Tracker Bukti (214 baris) — Excel</a>
                <a href="{{ route('laporan.export.pdf', ['kriteria' => 'Dokumen Bersama']) }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold" style="border-radius: 8px;">Ekspor Status Dokumen Bersama — PDF</a>
            </div>
        </div>
    </div>
    
    <div class="text-center text-muted small mt-2 mb-5">
        Semua tombol ekspor pada mockup ini bersifat ilustratif (namun sudah terhubung ke generator file PDF/Excel per kriteria).
    </div>

</section>
@endsection
