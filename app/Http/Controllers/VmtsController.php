<?php

namespace App\Http\Controllers;

use App\DataTables\VmtsBuktiDataTable;
use App\Models\Vmts;
use App\Models\VmtsNarasi;
use App\Models\VmtsBukti;
use Illuminate\Http\Request;
use App\Http\Requests\VmtsRequest;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class VmtsController extends Controller
{
    /**
     * Display the VMTS Dashboard (Bagian A & B).
     */
    public function index(VmtsBuktiDataTable $dataTable)
    {
        $user = Auth::user();
        
        // 1. Get or Create VMTS for current user (Prodi)
        $vmts = Vmts::firstOrCreate(
            ['user_id' => $user->id, 'tahun_akreditasi' => date('Y')]
        );

        // 2. Ensure all 6 Elemen Utama (EU) exist for this VMTS
        $elements = [
            'EU-1' => 'Mekanisme perumusan VMTS & unggulan PS',
            'EU-2' => 'Sosialisasi VMTS ke sivitas akademika',
            'EU-3' => 'Strategi & Indikator kinerja (KPI) pencapaian',
            'EU-4' => 'Keterkaitan VMTS — Kurikulum — Penjaminan Mutu',
            'EU-5' => 'Siklus peninjauan berkala VMTS',
            'EU-6' => 'Tata nilai program studi',
        ];

        foreach ($elements as $kode => $nama) {
            VmtsNarasi::firstOrCreate(
                ['vmts_id' => $vmts->id, 'elemen_kode' => $kode],
                ['elemen_nama' => $nama, 'status' => 'Draft']
            );
        }

        $narasis = $vmts->narasis()->get()->keyBy('elemen_kode');

        // Kalkulasi persentase kelengkapan
        $totalNarasi = $narasis->count();
        $lengkapNarasi = $narasis->where('status', 'Lengkap')->count();
        $pctNarasi = $totalNarasi > 0 ? round(($lengkapNarasi / $totalNarasi) * 100) : 0;

        $totalBukti = $vmts->buktis()->count();
        $tersediaBukti = $vmts->buktis()->where('status', 'Tersedia')->count();
        $pctBukti = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;

        // Render index page with DataTable for Bagian B
        return $dataTable->with('vmts_id', $vmts->id)
                         ->render('pages.vmts.index', compact('vmts', 'narasis', 'pctNarasi', 'pctBukti'));
    }

    /**
     * Update Narasi (Bagian A) via AJAX or standard form submission.
     */
    public function updateNarasi(VmtsRequest $request, VmtsNarasi $narasi)
    {
        $narasi->update($request->validated());

        Alert::success('Berhasil!', 'Narasi ' . $narasi->elemen_kode . ' berhasil disimpan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Store new Bukti (Bagian B).
     */
    public function storeBukti(VmtsRequest $request)
    {
        VmtsBukti::create($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Update existing Bukti (Bagian B).
     */
    public function updateBukti(VmtsRequest $request, VmtsBukti $bukti)
    {
        $bukti->update($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Remove Bukti (Bagian B).
     */
    public function destroyBukti(VmtsBukti $bukti)
    {
        $bukti->delete();

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }
}
