<?php

namespace App\Http\Controllers;

use App\Models\Doenpkm;
use App\Models\DoenpkmNarasi;
use App\Models\DoenpkmBukti;
use Illuminate\Http\Request;
use App\Http\Requests\DoenpkmRequest;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DoenpkmController extends Controller
{
    /**
     * Display the DoenPKM Dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        $doenpkm = Doenpkm::firstOrCreate(
            ['user_id' => $user->id, 'tahun_akreditasi' => date('Y')]
        );

        $elements = [
            '5.1' => [
                'title' => 'Kebijakan Penetapan Dosen',
                'type' => 'WAJIB',
                'eus' => [
                    '5.1-EU1' => 'Kebijakan rekrutmen & penetapan dosen',
                    '5.1-EU2' => 'Kesesuaian kualifikasi dosen',
                    '5.1-EU3' => 'Rasio dosen : mahasiswa',
                ]
            ],
            '5.2' => [
                'title' => 'Kinerja dan Perilaku Dosen',
                'type' => 'WAJIB',
                'eus' => [
                    '5.2-EU1' => 'Evaluasi kinerja dosen (BKD/SKP)',
                    '5.2-EU2' => 'Kode etik dosen',
                    '5.2-EU3' => 'Penghargaan dosen',
                    '5.2-EU4' => 'Tindak lanjut hasil evaluasi',
                ]
            ],
            '5.3' => [
                'title' => 'Pengembangan Profesional Berkelanjutan untuk Dosen',
                'type' => 'BOLEH SEBAGIAN',
                'eus' => [
                    '5.3-EU1' => 'Program pengembangan kompetensi dosen',
                    '5.3-EU2' => 'Sertifikasi profesi dosen',
                    '5.3-EU3' => 'Studi lanjut dosen',
                ]
            ],
            '5.4' => [
                'title' => 'Pengembangan Tenaga Kependidikan',
                'type' => 'BOLEH SEBAGIAN',
                'eus' => [
                    '5.4-EU1' => 'Kualifikasi tenaga kependidikan',
                    '5.4-EU2' => 'Pengembangan tenaga kependidikan',
                ]
            ],
            '5.5' => [
                'title' => 'Relevansi Penelitian sesuai Visi dan Unggulan PS',
                'type' => 'WAJIB',
                'eus' => [
                    '5.5-EU1' => 'Peta jalan penelitian',
                    '5.5-EU2' => 'Relevansi penelitian dengan visi PS',
                ]
            ],
            '5.6' => [
                'title' => 'Relevansi PkM sesuai Visi dan Unggulan PS',
                'type' => 'WAJIB',
                'eus' => [
                    '5.6-EU1' => 'Peta jalan PkM',
                    '5.6-EU2' => 'Relevansi PkM dengan visi PS',
                ]
            ],
        ];

        foreach ($elements as $subKode => $subData) {
            foreach ($subData['eus'] as $kode => $nama) {
                DoenpkmNarasi::firstOrCreate(
                    ['doenpkm_id' => $doenpkm->id, 'elemen_kode' => $kode],
                    ['elemen_nama' => $nama, 'status' => 'Draft']
                );
            }
        }

        $narasis = $doenpkm->narasis()->get()->keyBy('elemen_kode');
        $buktis = $doenpkm->buktis()->get()->groupBy(function($item) {
            // Group by sub-criteria like 5.1, 5.2
            return explode('-', $item->elemen_kode)[0];
        });

        // Calculate per sub-criteria
        $subKriteriasStats = [];
        $memenuhiCount = 0;
        $wajibBelumMemenuhiCount = 0;

        foreach ($elements as $subKode => $subData) {
            $subNarasis = $narasis->filter(function($n, $k) use ($subKode) {
                return str_starts_with($k, $subKode . '-');
            });
            $subTotal = $subNarasis->count();
            $subPctNarasi = $subTotal > 0 ? (int) round($subNarasis->avg('narasi_persen')) : 0;
            $subPctBukti = $subTotal > 0 ? (int) round($subNarasis->avg('bukti_persen')) : 0;
            
            $isMemenuhi = $subPctNarasi == 100 && $subPctBukti >= 50;
            if ($isMemenuhi) {
                $memenuhiCount++;
            } else {
                if ($subData['type'] == 'WAJIB') {
                    $wajibBelumMemenuhiCount++;
                }
            }
            $subKriteriasStats[] = [
                'narasi' => $subPctNarasi,
                'bukti' => $subPctBukti
            ];
        }

        $pctNarasi = count($subKriteriasStats) > 0 ? (int) round(collect($subKriteriasStats)->avg('narasi')) : 0;
        $pctBukti = count($subKriteriasStats) > 0 ? (int) round(collect($subKriteriasStats)->avg('bukti')) : 0;

        return view('pages.doenpkm.index', compact(
            'doenpkm', 'narasis', 'buktis', 'pctNarasi', 'pctBukti', 
            'elements', 'memenuhiCount', 'wajibBelumMemenuhiCount'
        ));
    }

    public function updateNarasi(DoenpkmRequest $request, DoenpkmNarasi $narasi)
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['narasi_persen'] = $data['status'] === 'Lengkap' ? 100 : 0;
        }
        $narasi->update($data);

        Alert::success('Berhasil!', 'Narasi ' . $narasi->elemen_kode . ' berhasil disimpan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    public function storeBukti(DoenpkmRequest $request)
    {
        $data = $request->validated();
        if(isset($data['status_bukti'])) {
            $data['status'] = $data['status_bukti'];
            unset($data['status_bukti']);
        }
        $bukti = DoenpkmBukti::create($data);
        $this->updateBuktiPersen($bukti->doenpkm_id, $bukti->elemen_kode);

        Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    public function updateBukti(DoenpkmRequest $request, $id)
    {
        $bukti = \App\Models\DoenpkmBukti::findOrFail($id);
        $updateData = $request->validated();
        if(isset($updateData['status_bukti'])) {
            $updateData['status'] = $updateData['status_bukti'];
            unset($updateData['status_bukti']);
        }
        $bukti->update($updateData);
        $newPctBukti = $this->updateBuktiPersen($bukti->doenpkm_id, $bukti->elemen_kode);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Berhasil diperbarui.', 'pctBukti' => $newPctBukti, 'elemen_kode' => $bukti->elemen_kode]);
        }

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    public function destroyBukti($id)
    {
        $bukti = \App\Models\DoenpkmBukti::findOrFail($id);
        $doenpkm_id = $bukti->doenpkm_id;
        $elemen_kode = $bukti->elemen_kode;
        $bukti->delete();
        $this->updateBuktiPersen($doenpkm_id, $elemen_kode);

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    private function updateBuktiPersen($doenpkm_id, $elemen_kode)
    {
        // For backwards compatibility or just safety, extract sub_kode (e.g. 5.1 from 5.1-EU1)
        $sub_kode = explode('-', $elemen_kode)[0];

        $totalBukti = DoenpkmBukti::where('doenpkm_id', $doenpkm_id)
            ->where('elemen_kode', 'like', $sub_kode . '%')
            ->count();
            
        $tersediaBukti = DoenpkmBukti::where('doenpkm_id', $doenpkm_id)
            ->where('elemen_kode', 'like', $sub_kode . '%')
            ->where('status', 'Tersedia')
            ->count();
            
        $pct = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;
        
        // Update ALL narasi inside this sub-kriteria
        DoenpkmNarasi::where('doenpkm_id', $doenpkm_id)
            ->where('elemen_kode', 'like', $sub_kode . '-%')
            ->update(['bukti_persen' => $pct]);
            
        return $pct;
    }
}
