<?php

namespace App\Http\Controllers;

use App\DataTables\KurikulumBuktiDataTable;
use App\Models\Kurikulum;
use App\Models\KurikulumNarasi;
use App\Models\KurikulumBukti;
use App\Http\Requests\KurikulumRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        $subKriterias = $narasis->filter(fn($n) => !str_contains($n->kriteria_kode, '_EU'));

        // Kalkulasi persentase kelengkapan
        $totalNarasi = $narasis->count();
        $pctNarasi = $totalNarasi > 0 ? (int) round($narasis->avg('narasi_persen')) : 0;

        $pctBukti = $totalNarasi > 0 ? (int) round($narasis->avg('bukti_persen')) : 0;

        // Render index page without DataTable
        return view('pages.kurikulum.index', compact('kurikulum', 'narasis', 'subKriterias', 'pctNarasi', 'pctBukti'));
    }

    public function store(KurikulumRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $data = $request->validated();
            if(isset($data['status_bukti'])) {
                $data['status'] = $data['status_bukti'];
                unset($data['status_bukti']);
            }
            $bukti = KurikulumBukti::create($data);
            $this->updateBuktiPersen($bukti->kurikulum_id, $bukti->kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    public function update(KurikulumRequest $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\KurikulumNarasi::findOrFail($id);
            $narasi->update($request->validated());

            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_', $narasi->kriteria_kode)[0];
                $parent = KurikulumNarasi::where('kurikulum_id', $narasi->kurikulum_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();

                if ($parent) {
                    $allEUs = KurikulumNarasi::where('kurikulum_id', $narasi->kurikulum_id)
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
            $bukti = \App\Models\KurikulumBukti::findOrFail($id);
            $updateData = $request->validated();
            if(isset($updateData['status_bukti'])) {
                $updateData['status'] = $updateData['status_bukti'];
                unset($updateData['status_bukti']);
            }
            $bukti->update($updateData);
            $newPctBukti = $this->updateBuktiPersen($bukti->kurikulum_id, $bukti->kriteria_kode);

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
            $bukti = \App\Models\KurikulumBukti::findOrFail($id);
            $kurikulum_id = $bukti->kurikulum_id;
            $kriteria_kode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($kurikulum_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        
        return redirect()->back();
    }

    /**
     * Update bukti persen per kriteria_kode
     */
    private function updateBuktiPersen($kurikulum_id, $kriteria_kode)
    {
        $narasi = KurikulumNarasi::where('kurikulum_id', $kurikulum_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->first();

        if ($narasi) {
            $totalBukti = KurikulumBukti::where('kurikulum_id', $kurikulum_id)
                ->where('kriteria_kode', $kriteria_kode)
                ->count();
                
            $tersediaBukti = KurikulumBukti::where('kurikulum_id', $kurikulum_id)
                ->where('kriteria_kode', $kriteria_kode)
                ->where('status', 'Tersedia')
                ->count();
                
            $pct = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;
            $narasi->update(['bukti_persen' => $pct]);
            return $pct;
        }
        return 0;
    }
}
