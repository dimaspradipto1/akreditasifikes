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
    public function index()
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
        $pctNarasi = $totalNarasi > 0 ? (int) round($narasis->avg('narasi_persen')) : 0;

        $pctBukti = $totalNarasi > 0 ? (int) round($narasis->avg('bukti_persen')) : 0;

        // Render index page without DataTable
        return view('pages.vmts.index', compact('vmts', 'narasis', 'pctNarasi', 'pctBukti'));
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
        $data = $request->validated();
        if(isset($data['status_bukti'])) {
            $data['status'] = $data['status_bukti'];
            unset($data['status_bukti']);
        }
        $bukti = VmtsBukti::create($data);
        $this->updateBuktiPersen($bukti->vmts_id, $bukti->elemen_kode);

        Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Update existing Bukti (Bagian B).
     */
    public function updateBukti(VmtsRequest $request, $id)
    {
        $bukti = \App\Models\VmtsBukti::findOrFail($id);
        $updateData = $request->validated();
        if(isset($updateData['status_bukti'])) {
            $updateData['status'] = $updateData['status_bukti'];
            unset($updateData['status_bukti']);
        }
        $bukti->update($updateData);
        $newPctBukti = $this->updateBuktiPersen($bukti->vmts_id, $bukti->elemen_kode);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Berhasil diperbarui.', 'pctBukti' => $newPctBukti, 'elemen_kode' => $bukti->elemen_kode]);
        }

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Remove Bukti (Bagian B).
     */
    public function destroyBukti($id)
    {
        $bukti = \App\Models\VmtsBukti::findOrFail($id);
        $vmts_id = $bukti->vmts_id;
        $elemen_kode = $bukti->elemen_kode;
        $bukti->delete();
        $this->updateBuktiPersen($vmts_id, $elemen_kode);

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Update bukti persen per elemen_kode
     */
    private function updateBuktiPersen($vmts_id, $elemen_kode)
    {
        $narasi = VmtsNarasi::where('vmts_id', $vmts_id)
            ->where('elemen_kode', $elemen_kode)
            ->first();

        if ($narasi) {
            $totalBukti = VmtsBukti::where('vmts_id', $vmts_id)
                ->where('elemen_kode', $elemen_kode)
                ->count();
                
            $tersediaBukti = VmtsBukti::where('vmts_id', $vmts_id)
                ->where('elemen_kode', $elemen_kode)
                ->where('status', 'Tersedia')
                ->count();
                
            $pct = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;
            $narasi->update(['bukti_persen' => $pct]);
            return $pct;
        }
        return 0;
    }
}
