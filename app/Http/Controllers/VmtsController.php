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

    public function store(VmtsRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $data = $request->validated();
            if(isset($data['status_bukti'])) {
                $data['status'] = $data['status_bukti'];
                unset($data['status_bukti']);
            }
            $bukti = \App\Models\VmtsBukti::create($data);
            $this->updateBuktiPersen($bukti->vmts_id, $bukti->kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    public function update(VmtsRequest $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\VmtsNarasi::findOrFail($id);
            $narasi->update($request->validated());

            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_', $narasi->kriteria_kode)[0];
                $parent = \App\Models\VmtsNarasi::where('vmts_id', $narasi->vmts_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();

                if ($parent) {
                    $allEUs = \App\Models\VmtsNarasi::where('vmts_id', $narasi->vmts_id)
                        ->where('kriteria_kode', 'LIKE', $parentKode . '_EU%')
                        ->get();
                    
                    $totalEU = $allEUs->count();
                    $lengkapEU = $allEUs->where('status', 'Lengkap')->count();
                    
                    $narasiPersen = $totalEU > 0 ? round(($lengkapEU / $totalEU) * 100) : 0;
                    $status = ($narasiPersen == 100) ? 'Memenuhi' : 'Belum Memenuhi';
                    
                    $parent->update([
                        'narasi_persen' => $narasiPersen,
                        'status' => $status
                    ]);
                }
            }

            Alert::success('Berhasil!', 'Narasi ' . $narasi->kriteria_kode . ' berhasil disimpan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }

        if ($request->has('type') && $request->type === 'bukti') {
            $bukti = \App\Models\VmtsBukti::findOrFail($id);
            
            $updateData = $request->validated();
            if(isset($updateData['status_bukti'])) {
                $updateData['status'] = $updateData['status_bukti'];
                unset($updateData['status_bukti']);
            }

            $bukti->update($updateData);
            $newPctBukti = $this->updateBuktiPersen($bukti->vmts_id, $bukti->kriteria_kode);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Berhasil diperbarui.', 'pctBukti' => $newPctBukti, 'kriteria_kode' => $bukti->kriteria_kode]);
            }

            Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $bukti = \App\Models\VmtsBukti::findOrFail($id);
            $vmtsId = $bukti->vmts_id;
            $kriteriaKode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($vmtsId, $kriteriaKode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * Update bukti persen per elemen_kode
     */
    private function updateBuktiPersen($vmts_id, $elemen_kode = null)
    {
        $totalBukti = VmtsBukti::where('vmts_id', $vmts_id)->count();
            
        $tersediaBukti = VmtsBukti::where('vmts_id', $vmts_id)
            ->where('status', 'Tersedia')
            ->count();
            
        $pct = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;
        
        VmtsNarasi::where('vmts_id', $vmts_id)->update(['bukti_persen' => $pct]);
        return $pct;
    }
}
