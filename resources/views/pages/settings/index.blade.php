@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center">
    <div>
        <h1>Pengaturan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}</li>
                <li class="breadcrumb-item active">Pengaturan</li>
            </ol>
        </nav>
        <p class="text-muted small mt-1 mb-0">{{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }}) - Konfigurasi bobot skor, periode pengisian, dan data master prodi</p>
    </div>
    <div>
        <button type="submit" form="settings-form" class="btn btn-primary fw-semibold" style="border-radius: 8px;">Simpan Perubahan</button>
    </div>
</div>

<section class="section">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form id="settings-form" action="{{ route('settings.update') }}" method="POST">
        @csrf
        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <h5 class="card-title p-0 mb-1 fw-bold text-dark">Data Master Program Studi</h5>
                <p class="text-muted small mb-4">Identitas prodi yang tampil di seluruh halaman (Dashboard, Kriteria, Laporan)</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Nama Program Studi</label>
                        <input type="text" name="prodi_nama" class="form-control" value="{{ $settings['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Jenjang</label>
                        <select name="prodi_jenjang" class="form-select">
                            <option value="Sarjana (S1)" {{ ($settings['prodi_jenjang'] ?? '') == 'Sarjana (S1)' ? 'selected' : '' }}>Sarjana (S1)</option>
                            <option value="Magister (S2)" {{ ($settings['prodi_jenjang'] ?? '') == 'Magister (S2)' ? 'selected' : '' }}>Magister (S2)</option>
                            <option value="Diploma" {{ ($settings['prodi_jenjang'] ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Koordinator Prodi</label>
                        <input type="text" name="prodi_koordinator" class="form-control" value="{{ $settings['prodi_koordinator'] ?? 'Koordinator Prodi S1 Kesling' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Dekan FIKes</label>
                        <input type="text" name="dekan_fikes" class="form-control" value="{{ $settings['dekan_fikes'] ?? 'Prof. Dr. Ameena Ahmad, SKM., M.Kes' }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <h5 class="card-title p-0 mb-1 fw-bold text-dark">Periode Pengisian</h5>
                <p class="text-muted small mb-4">Menentukan hitungan mundur "sisa waktu pengajuan" pada Dashboard</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Tanggal Mulai Pengisian</label>
                        <input type="date" name="akreditasi_start_date" class="form-control" value="{{ $settings['akreditasi_start_date'] ?? '2026-06-01' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Deadline Pengajuan ke LAM-PTKes</label>
                        <input type="date" name="akreditasi_end_date" class="form-control" value="{{ $settings['akreditasi_end_date'] ?? '2026-08-17' }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <h5 class="card-title p-0 mb-1 fw-bold text-dark">Bobot Skor per Kriteria</h5>
                <p class="text-muted small mb-4">Bobot ilustratif — total harus 100%. Dipakai untuk menghitung skor gabungan pada Dashboard & Laporan.</p>
                
                <div class="table-responsive">
                    <table class="table table-borderless align-middle" style="border-collapse: separate; border-spacing: 0 8px;">
                        <thead>
                            <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <th class="text-uppercase fw-bold pb-2" style="border-bottom: 1px solid #e2e8f0; width: 40%;">Kriteria</th>
                                <th class="text-uppercase fw-bold pb-2" style="border-bottom: 1px solid #e2e8f0;">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kriterias = [
                                    '1' => 'K1 — Visi, Misi, Tujuan & Strategi',
                                    '2' => 'K2 — Kurikulum',
                                    '3' => 'K3 — Penilaian',
                                    '4' => 'K4 — Mahasiswa',
                                    '5' => 'K5 — Dosen, Tendik, Penelitian & PkM',
                                    '6' => 'K6 — Sarana, Prasarana & Keuangan',
                                    '7' => 'K7 — Penjaminan Mutu',
                                    '8' => 'K8 — Tata Kelola & Administrasi'
                                ];
                                $defaultWeights = [
                                    '1' => 15, '2' => 15, '3' => 12, '4' => 12, 
                                    '5' => 18, '6' => 12, '7' => 8, '8' => 8
                                ];
                            @endphp

                            @foreach($kriterias as $k => $name)
                            <tr style="background: #fff;">
                                <td class="ps-3 py-3" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; border-left: 1px solid #e2e8f0; font-size: 0.9rem; color: #334155;">
                                    {{ $name }}
                                </td>
                                <td class="py-3 pe-3" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="range" class="form-range flex-grow-1" name="bobot_k{{ $k }}" id="bobot_k{{ $k }}" min="0" max="100" value="{{ $settings['bobot_k'.$k] ?? $defaultWeights[$k] }}" oninput="updateBobot({{ $k }})">
                                        <span class="fw-bold" style="min-width: 40px; text-align: right;" id="val_bobot_k{{ $k }}">{{ $settings['bobot_k'.$k] ?? $defaultWeights[$k] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4">
                <h5 class="card-title p-0 mb-1 fw-bold text-dark">Notifikasi</h5>
                <p class="text-muted small mb-4">Peringatan otomatis yang dikirim ke Koordinator Prodi & Tim Penyusun</p>
                
                <div class="form-check mb-2">
                    <input type="hidden" name="notif_eu" value="0">
                    <input class="form-check-input" type="checkbox" name="notif_eu" value="1" id="notif_eu" {{ ($settings['notif_eu'] ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="notif_eu" style="font-size: 0.9rem;">
                        EU/bukti belum diisi mendekati deadline
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="notif_wajib" value="0">
                    <input class="form-check-input" type="checkbox" name="notif_wajib" value="1" id="notif_wajib" {{ ($settings['notif_wajib'] ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="notif_wajib" style="font-size: 0.9rem;">
                        Sub-kriteria WAJIB berubah status menjadi Tidak Memenuhi
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="notif_dokumen" value="0">
                    <input class="form-check-input" type="checkbox" name="notif_dokumen" value="1" id="notif_dokumen" {{ ($settings['notif_dokumen'] ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="notif_dokumen" style="font-size: 0.9rem;">
                        Dokumen Bersama (FIKes/Universitas) diperbarui oleh tim lain
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="notif_email" value="0">
                    <input class="form-check-input" type="checkbox" name="notif_email" value="1" id="notif_email" {{ ($settings['notif_email'] ?? '0') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="notif_email" style="font-size: 0.9rem;">
                        Ringkasan mingguan progres pengisian (email)
                    </label>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    function updateBobot(k) {
        document.getElementById('val_bobot_k' + k).innerText = document.getElementById('bobot_k' + k).value + '%';
    }
</script>
@endsection
