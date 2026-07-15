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
            'EU-1' => 'Penyusunan CPL 4 komponen (sikap, pengetahuan, keterampilan umum & khusus)',
            'EU-2' => 'Kesesuaian CPL dengan SN-DIKTI & KKNI',
            'EU-3' => 'Pendekatan Outcome-Based Education (OBE)',
            'EU-4' => 'Pelibatan stakeholder/industri dalam penyusunan CPL',
            'EU-5' => 'Pengalaman belajar lapangan (PKL/magang)',
            'EU-6' => 'Relevansi CPL dengan karir lulusan',
            'EU-7' => 'Kesesuaian dengan konteks lokal Batam/Kepri',
        ];

        foreach ($elements as $kode => $nama) {
            $status = str_starts_with($kode, 'EU') ? 'Draft' : 'Belum Memenuhi';
            KurikulumNarasi::firstOrCreate(
                ['kurikulum_id' => $kurikulum->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $nama, 'syarat' => 'WAJIB', 'status' => $status]
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
