<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama
     */
    public function index()
    {
        // 1. Pengaturan Jadwal Akreditasi
        $endDateSetting = Setting::where('key', 'akreditasi_end_date')->first();
        $startDateSetting = Setting::where('key', 'akreditasi_start_date')->first();
        
        $akreditasi_end_date = $endDateSetting ? $endDateSetting->value : Carbon::now()->addDays(42)->format('Y-m-d');
        $akreditasi_start_date = $startDateSetting ? $startDateSetting->value : Carbon::now()->format('Y-m-d');
        
        $sisaHari = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($akreditasi_end_date)->startOfDay(), false);

        // 2. Ambil Parent Model untuk tiap Kriteria (shared globally per tahun)
        $targetUser = \App\Models\User::where('role', 'koordinatorprodi')->first() ?: \App\Models\User::first();
        $userId = $targetUser ? $targetUser->id : \Illuminate\Support\Facades\Auth::id();

        $vmts = \App\Models\Vmts::where('tahun_akreditasi', date('Y'))->first() ?: \App\Models\Vmts::where('user_id', $userId)->first();
        $kurikulum = \App\Models\Kurikulum::where('tahun_akreditasi', date('Y'))->first() ?: \App\Models\Kurikulum::where('user_id', $userId)->first();
        $penilaian = \App\Models\Penilaian::where('tahun_akreditasi', date('Y'))->first() ?: \App\Models\Penilaian::where('user_id', $userId)->first();
        $mahasiswa = \App\Models\Mahasiswa::where('tahun_akreditasi', date('Y'))->first() ?: \App\Models\Mahasiswa::where('user_id', $userId)->first();
        $doenpkm = \App\Models\Doenpkm::where('tahun_akreditasi', date('Y'))->first() ?: \App\Models\Doenpkm::where('user_id', $userId)->first();
        $sarpras = \App\Models\Sarpraskeuangan::where('tahun', date('Y'))->first() ?: \App\Models\Sarpraskeuangan::where('user_id', $userId)->first();
        $mutu = \App\Models\Mutu::where('tahun', date('Y'))->first() ?: \App\Models\Mutu::where('user_id', $userId)->first();
        $tatakelola = \App\Models\Tatakelola::where('tahun', date('Y'))->first() ?: \App\Models\Tatakelola::where('user_id', $userId)->first();

        // K1: VMTS (EU-1 s/d EU-6)
        $k1_narasi = $vmts ? ($vmts->narasis()->avg('narasi_persen') ?? 0) : 0;
        $k1_bukti = $vmts ? ($vmts->narasis()->avg('bukti_persen') ?? 0) : 0;
        
        // K2: Kurikulum (sub-kriteria 2.1 s/d 2.4)
        $k2_sub = $kurikulum ? $kurikulum->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k2_narasi = $k2_sub->count() > 0 ? ($k2_sub->avg('narasi_persen') ?? 0) : 0;
        $k2_bukti = $k2_sub->count() > 0 ? ($k2_sub->avg('bukti_persen') ?? 0) : 0;
        
        // K3: Penilaian (sub-kriteria 3.1 s/d 3.4)
        $k3_sub = $penilaian ? $penilaian->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k3_narasi = $k3_sub->count() > 0 ? ($k3_sub->avg('narasi_persen') ?? 0) : 0;
        $k3_bukti = $k3_sub->count() > 0 ? ($k3_sub->avg('bukti_persen') ?? 0) : 0;
        
        // K4: Mahasiswa (sub-kriteria 4.1 s/d 4.4)
        $k4_sub = $mahasiswa ? $mahasiswa->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k4_narasi = $k4_sub->count() > 0 ? ($k4_sub->avg('narasi_persen') ?? 0) : 0;
        $k4_bukti = $k4_sub->count() > 0 ? ($k4_sub->avg('bukti_persen') ?? 0) : 0;
        
        // K5: Dosen, Tendik, Penelitian & PkM
        $k5_narasi = $doenpkm ? ($doenpkm->narasis()->avg('narasi_persen') ?? 0) : 0;
        $k5_bukti = $doenpkm ? ($doenpkm->narasis()->avg('bukti_persen') ?? 0) : 0;
        
        // K6: Sarana, Prasarana & Keuangan (sub-kriteria 6.1 s/d 6.3)
        $k6_sub = $sarpras ? $sarpras->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k6_narasi = $k6_sub->count() > 0 ? ($k6_sub->avg('narasi_persen') ?? 0) : 0;
        $k6_bukti = $k6_sub->count() > 0 ? ($k6_sub->avg('bukti_persen') ?? 0) : 0;
        
        // K7: Penjaminan Mutu (sub-kriteria 7.1 s/d 7.3)
        $k7_sub = $mutu ? $mutu->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k7_narasi = $k7_sub->count() > 0 ? ($k7_sub->avg('narasi_persen') ?? 0) : 0;
        $k7_bukti = $k7_sub->count() > 0 ? ($k7_sub->avg('bukti_persen') ?? 0) : 0;
        
        // K8: Tata Kelola & Administrasi (sub-kriteria 8.1 s/d 8.3)
        $k8_sub = $tatakelola ? $tatakelola->narasis()->where('kriteria_kode', 'NOT LIKE', '%_EU%')->get() : collect();
        $k8_narasi = $k8_sub->count() > 0 ? ($k8_sub->avg('narasi_persen') ?? 0) : 0;
        $k8_bukti = $k8_sub->count() > 0 ? ($k8_sub->avg('bukti_persen') ?? 0) : 0;

        $kriteria_stats = [
            'K1' => ['nama' => 'Visi, Misi, Tujuan & Strategi', 'narasi' => round($k1_narasi), 'bukti' => round($k1_bukti)],
            'K2' => ['nama' => 'Kurikulum', 'narasi' => round($k2_narasi), 'bukti' => round($k2_bukti)],
            'K3' => ['nama' => 'Penilaian', 'narasi' => round($k3_narasi), 'bukti' => round($k3_bukti)],
            'K4' => ['nama' => 'Mahasiswa', 'narasi' => round($k4_narasi), 'bukti' => round($k4_bukti)],
            'K5' => ['nama' => 'Dosen, Tendik, Penelitian & PkM', 'narasi' => round($k5_narasi), 'bukti' => round($k5_bukti)],
            'K6' => ['nama' => 'Sarana, Prasarana & Keuangan', 'narasi' => round($k6_narasi), 'bukti' => round($k6_bukti)],
            'K7' => ['nama' => 'Penjaminan Mutu', 'narasi' => round($k7_narasi), 'bukti' => round($k7_bukti)],
            'K8' => ['nama' => 'Tata Kelola & Administrasi', 'narasi' => round($k8_narasi), 'bukti' => round($k8_bukti)],
        ];

        // Bobot dari pengaturan (atau default jika belum diatur)
        $weights = [
            '1' => \App\Models\Setting::where('key', 'bobot_k1')->value('value') ?? 15,
            '2' => \App\Models\Setting::where('key', 'bobot_k2')->value('value') ?? 15,
            '3' => \App\Models\Setting::where('key', 'bobot_k3')->value('value') ?? 12,
            '4' => \App\Models\Setting::where('key', 'bobot_k4')->value('value') ?? 12,
            '5' => \App\Models\Setting::where('key', 'bobot_k5')->value('value') ?? 18,
            '6' => \App\Models\Setting::where('key', 'bobot_k6')->value('value') ?? 12,
            '7' => \App\Models\Setting::where('key', 'bobot_k7')->value('value') ?? 8,
            '8' => \App\Models\Setting::where('key', 'bobot_k8')->value('value') ?? 8,
        ];

        // Normalisasi total bobot menjadi pengali desimal
        $totalWeight = array_sum($weights);
        if ($totalWeight == 0) $totalWeight = 100;
        
        $avg_narasi_total = 0;
        $avg_bukti_total = 0;
        
        foreach($kriteria_stats as $key => $stat) {
            $num = str_replace('K', '', $key);
            $weightMult = $weights[$num] / $totalWeight;
            
            $avg_narasi_total += $stat['narasi'] * $weightMult;
            $avg_bukti_total += $stat['bukti'] * $weightMult;
        }

        $skor_capaian = ($avg_narasi_total + $avg_bukti_total) / 2;

        // Proyeksi Status
        if($skor_capaian >= 85) {
            $proyeksi_status = 'Unggul';
            $proyeksi_warna = 'text-success';
        } elseif($skor_capaian >= 70) {
            $proyeksi_status = 'Baik Sekali';
            $proyeksi_warna = 'text-primary';
        } elseif($skor_capaian >= 50) {
            $proyeksi_status = 'Baik';
            $proyeksi_warna = 'text-warning';
        } else {
            $proyeksi_status = 'Tidak Memenuhi';
            $proyeksi_warna = 'text-danger';
        }

        // 4. Hitung Dokumen Bersama (UNIV dan FIKES)
        $total_univ = DB::table('dokumen_bersamas')->where('level', 'UNIV')->count();
        $tersedia_univ = DB::table('dokumen_bersamas')->where('level', 'UNIV')->where('status', 'Tersedia')->count();
        $pct_univ = $total_univ > 0 ? round(($tersedia_univ / $total_univ) * 100) : 0;

        $total_fikes = DB::table('dokumen_bersamas')->where('level', 'FIKES')->count();
        $tersedia_fikes = DB::table('dokumen_bersamas')->where('level', 'FIKES')->where('status', 'Tersedia')->count();
        $pct_fikes = $total_fikes > 0 ? round(($tersedia_fikes / $total_fikes) * 100) : 0;

        $dokumen = [
            'univ' => ['total' => $total_univ, 'tersedia' => $tersedia_univ, 'pct' => $pct_univ],
            'fikes' => ['total' => $total_fikes, 'tersedia' => $tersedia_fikes, 'pct' => $pct_fikes],
        ];

        // 5. Data Sub-Kriteria Wajib Belum Memenuhi (Simulasi)
        $wajib_belum_memenuhi = 0;
        $wajib_total = 17;

        // 6. Data Aktivitas Terbaru (Dummy Data)
        $aktivitas = [
            ['teks' => '<b>Koordinator Prodi S1 Kesling</b> mengisi narasi Blok A-C, Sub-K 1.1 (K1)', 'waktu' => '1 jam lalu'],
            ['teks' => '<b>Tim Penyusun Borang Kesling</b> mengunggah bukti RPS Kriteria 2 (Kurikulum)', 'waktu' => '3 jam lalu'],
            ['teks' => '<b>Ms. L (GPM FIKes)</b> memperbarui Renstra FIKes pada Dokumen Bersama', 'waktu' => 'kemarin'],
        ];

        return view('layouts.dashboard.index', compact(
            'kriteria_stats',
            'avg_narasi_total',
            'avg_bukti_total',
            'proyeksi_status',
            'proyeksi_warna',
            'skor_capaian',
            'dokumen',
            'wajib_belum_memenuhi',
            'wajib_total',
            'aktivitas',
            'akreditasi_start_date',
            'akreditasi_end_date',
            'sisaHari'
        ));
    }
    
    /**
     * Update Jadwal Akreditasi
     */
    public function updateJadwal(Request $request)
    {
        $request->validate([
            'akreditasi_start_date' => 'required|date',
            'akreditasi_end_date' => 'required|date',
        ]);
        
        Setting::updateOrCreate(
            ['key' => 'akreditasi_start_date'],
            ['value' => $request->akreditasi_start_date]
        );
        
        Setting::updateOrCreate(
            ['key' => 'akreditasi_end_date'],
            ['value' => $request->akreditasi_end_date]
        );
        
        return redirect()->back()->with('success', 'Jadwal Akreditasi berhasil diperbarui.');
    }
}
