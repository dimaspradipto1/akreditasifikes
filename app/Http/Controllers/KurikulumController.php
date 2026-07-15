<?php

namespace App\Http\Controllers;

use App\DataTables\KurikulumBuktiDataTable;
use App\Models\Kurikulum;
use App\Models\KurikulumNarasi;
use App\Models\KurikulumBukti;
use App\Http\Requests\KurikulumRequest;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KurikulumController extends Controller
{
    /**
     * Display the Kurikulum Dashboard (Bagian A & B).
     */
    public function index(KurikulumBuktiDataTable $dataTable)
    {
        $user = Auth::user();
        
        // 1. Get or Create Kurikulum for current user (Prodi)
        $kurikulum = Kurikulum::firstOrCreate(
            ['user_id' => $user->id, 'tahun_akreditasi' => date('Y')]
        );

        // 2. Ensure all Sub-kriteria exist for this Kurikulum
        $elements = [
            '2.1' => 'Capaian Pembelajaran dalam Kurikulum',
            '2.2' => 'Struktur Kurikulum',
            '2.3' => 'Isi Kurikulum',
            '2.4' => 'Metode dan Pengalaman Pembelajaran',
            
            '2.1_EU-1' => 'Penyusunan CPL 4 komponen (sikap, pengetahuan, keterampilan umum & khusus)',
            '2.1_EU-2' => 'Kesesuaian CPL dengan SN-DIKTI & KKNI',
            '2.1_EU-3' => 'Pendekatan Outcome-Based Education (OBE)',
            '2.1_EU-4' => 'Pelibatan stakeholder/industri dalam penyusunan CPL',
            '2.1_EU-5' => 'Pengalaman belajar lapangan (PKL/magang)',
            '2.1_EU-6' => 'Relevansi CPL dengan karir lulusan',
            '2.1_EU-7' => 'Kesesuaian dengan konteks lokal Batam/Kepri',

            '2.2_EU-1' => 'Prinsip desain kurikulum',
            '2.2_EU-2' => 'Keseimbangan komponen teori, praktik, dan lapangan',
            '2.2_EU-3' => 'Integrasi penelitian & PkM dosen ke dalam pembelajaran',

            '2.3_EU-1' => 'Cakupan kompetensi inti',
            '2.3_EU-2' => 'Kemutakhiran isi kurikulum',
            '2.3_EU-3' => 'Penguasaan regulasi/standar bidang kesehatan',
            '2.3_EU-4' => 'Integrasi konteks sektor lokal',

            '2.4_EU-1' => 'Variasi metode pembelajaran',
            '2.4_EU-2' => 'Pengelolaan PKL/magang terstruktur',
            '2.4_EU-3' => 'Suasana akademik (seminar/kuliah tamu ≥ 2x/semester)',
        ];

        foreach ($elements as $kode => $nama) {
            $status = str_contains($kode, 'EU') ? 'Draft' : 'Belum Memenuhi';
            KurikulumNarasi::firstOrCreate(
                ['kurikulum_id' => $kurikulum->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $nama, 'status' => $status]
            );
        }

        $narasis = $kurikulum->narasis()->get()->keyBy('kriteria_kode');

        // Kalkulasi persentase kelengkapan
        $totalNarasi = $narasis->count();
        $lengkapNarasi = $narasis->where('status', 'Memenuhi')->count();
        $pctNarasi = $totalNarasi > 0 ? round(($lengkapNarasi / $totalNarasi) * 100) : 0;

        $totalBukti = $kurikulum->buktis()->count();
        $tersediaBukti = $kurikulum->buktis()->where('status', 'Tersedia')->count();
        $pctBukti = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;

        // Render index page with DataTable for Bagian B
        return $dataTable->with('kurikulum_id', $kurikulum->id)
                         ->render('pages.kurikulum.index', compact('kurikulum', 'narasis', 'pctNarasi', 'pctBukti'));
    }

    /**
     * Update Narasi via AJAX or standard form submission.
     */
    public function updateNarasi(KurikulumRequest $request, KurikulumNarasi $narasi)
    {
        $narasi->update($request->validated());

        Alert::success('Berhasil!', 'Narasi ' . $narasi->kriteria_kode . ' berhasil disimpan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Store new Bukti.
     */
    public function storeBukti(KurikulumRequest $request)
    {
        KurikulumBukti::create($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Update existing Bukti.
     */
    public function updateBukti(KurikulumRequest $request, KurikulumBukti $bukti)
    {
        $bukti->update($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Remove Bukti.
     */
    public function destroyBukti(KurikulumBukti $bukti)
    {
        $bukti->delete();

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }
}
