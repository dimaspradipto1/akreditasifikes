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

        // 2. Ambil Rata-rata Persentase dari 8 Kriteria menggunakan DB Facade
        $k1_narasi = DB::table('vmts_narasis')->avg('narasi_persen') ?? 0;
        $k1_bukti = DB::table('vmts_narasis')->avg('bukti_persen') ?? 0;
        
        $k2_narasi = DB::table('kurikulum_narasis')->avg('narasi_persen') ?? 0;
        $k2_bukti = DB::table('kurikulum_narasis')->avg('bukti_persen') ?? 0;
        
        $k3_narasi = DB::table('penilaian_narasis')->avg('narasi_persen') ?? 0;
        $k3_bukti = DB::table('penilaian_narasis')->avg('bukti_persen') ?? 0;
        
        $k4_narasi = DB::table('mahasiswa_narasis')->avg('narasi_persen') ?? 0;
        $k4_bukti = DB::table('mahasiswa_narasis')->avg('bukti_persen') ?? 0;
        
        $k5_narasi = DB::table('doenpkm_narasis')->avg('narasi_persen') ?? 0;
        $k5_bukti = DB::table('doenpkm_narasis')->avg('bukti_persen') ?? 0;
        
        $k6_narasi = DB::table('sarpraskeuangan_narasis')->avg('narasi_persen') ?? 0;
        $k6_bukti = DB::table('sarpraskeuangan_narasis')->avg('bukti_persen') ?? 0;
        
        $k7_narasi = DB::table('mutu_narasis')->avg('narasi_persen') ?? 0;
        $k7_bukti = DB::table('mutu_narasis')->avg('bukti_persen') ?? 0;
        
        $k8_narasi = DB::table('tatakelola_narasis')->avg('narasi_persen') ?? 0;
        $k8_bukti = DB::table('tatakelola_narasis')->avg('bukti_persen') ?? 0;

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

        // 3. Hitung Rata-rata Keseluruhan
        $avg_narasi_total = array_sum(array_column($kriteria_stats, 'narasi')) / 8;
        $avg_bukti_total = array_sum(array_column($kriteria_stats, 'bukti')) / 8;
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
