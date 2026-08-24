<?php

namespace App\Http\Controllers;

use App\Http\Requests\SarpraskeuanganRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class SarpraskeuanganController extends Controller
{
    public function __construct()
    {
        class_exists(\App\Models\Sarpraskeuangan::class);
    }

    public function index(Builder $builder, Request $request)
    {
        $user = Auth::user();

        $targetUser = \App\Models\User::where('role', 'koordinatorprodi')->first() ?: \App\Models\User::first();
        $userId = $targetUser ? $targetUser->id : $user->id;

        $sarpraskeuangan = \App\Models\Sarpraskeuangan::firstOrCreate(
            ['tahun' => date('Y')],
            ['user_id' => $userId]
        );

        foreach (['6.1', '6.2', '6.3'] as $subKode) {
            $this->updateBuktiPersen($sarpraskeuangan->id, $subKode);
        }

        $kriterias = [
            '6.1' => [
                'nama' => 'Fasilitas Fisik untuk Pendidikan dan Pelatihan',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '6.1_EU-1' => 'Ketersediaan, kecukupan, dan aksesibilitas sarana dan prasarana pembelajaran/laboratorium',
                    '6.1_EU-2' => 'Pemeliharaan, keselamatan, dan keberlanjutan sarana prasarana',
                ]
            ],
            '6.2' => [
                'nama' => 'Sumber Informasi',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '6.2_EU-1' => 'Ketersediaan dan aksesibilitas sumber pustaka (buku teks, jurnal nasional/internasional, e-library)',
                    '6.2_EU-2' => 'Fasilitas dan infrastruktur teknologi informasi dan komunikasi (TIK) untuk pembelajaran',
                    '6.2_EU-3' => 'Sistem informasi manajemen terintegrasi untuk layanan akademik & administrasi (SIAKAD/SIM)',
                    '6.2_EU-4' => 'Pengelolaan, pemeliharaan, dan sistem keamanan infrastruktur TIK / data',
                ]
            ],
            '6.3' => [
                'nama' => 'Sumber Daya Keuangan',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '6.3_EU-1' => 'Kebijakan, sistem, dan keberlanjutan alokasi anggaran operasional dan investasi',
                    '6.3_EU-2' => 'Kecukupan dana untuk operasional pendidikan, penelitian, dan pengabdian masyarakat (Tridharma)',
                    '6.3_EU-3' => 'Realisasi penggunaan dana dan efisiensi pengelolaan anggaran keuangan',
                    '6.3_EU-4' => 'Akuntabilitas, pelaporan, audit keuangan internal dan eksternal',
                ]
            ],
        ];

        foreach ($kriterias as $kode => $kriteria) {
            \App\Models\SarpraskeuanganNarasi::firstOrCreate(
                ['sarpraskeuangan_id' => $sarpraskeuangan->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $kriteria['nama'], 'status' => 'Belum Diisi']
            );

            if (!empty($kriteria['is_eu']) && !empty($kriteria['eus'])) {
                foreach ($kriteria['eus'] as $euKode => $euNama) {
                    \App\Models\SarpraskeuanganNarasi::firstOrCreate(
                        ['sarpraskeuangan_id' => $sarpraskeuangan->id, 'kriteria_kode' => $euKode],
                        ['kriteria_nama' => $euNama, 'status' => 'Draft']
                    );
                }
            }
        }

        // Recalculate parent narasi persen and status for all sub-kriterias
        foreach (array_keys($kriterias) as $parentKode) {
            $allEUs = \App\Models\SarpraskeuanganNarasi::where('sarpraskeuangan_id', $sarpraskeuangan->id)
                ->where('kriteria_kode', 'LIKE', $parentKode . '_EU%')
                ->get();
            
            $totalEU = $allEUs->count();
            if ($totalEU > 0) {
                $lengkapEU = $allEUs->where('status', 'Lengkap')->count();
                $narasiPersen = round(($lengkapEU / $totalEU) * 100);
                $status = ($narasiPersen == 100) ? 'Memenuhi' : ($narasiPersen > 0 ? 'Memenuhi Sebagian' : 'Belum Memenuhi');

                \App\Models\SarpraskeuanganNarasi::where('sarpraskeuangan_id', $sarpraskeuangan->id)
                    ->where('kriteria_kode', $parentKode)
                    ->update([
                        'narasi_persen' => $narasiPersen,
                        'status' => $status
                    ]);
            }
        }

        $narasis = $sarpraskeuangan->narasis()->get()->keyBy('kriteria_kode');
        $subKriterias = $narasis->filter(fn($n, $kode) => !str_contains($kode, '_EU'));

        $totalSub = $subKriterias->count();
        $pctNarasi = $totalSub > 0 ? (int) round($subKriterias->avg('narasi_persen')) : 0;
        $pctBukti = $totalSub > 0 ? (int) round($subKriterias->avg('bukti_persen')) : 0;

        return view('pages.sarpraskeuangan.index', compact(
            'sarpraskeuangan', 
            'kriterias', 
            'narasis', 
            'subKriterias',
            'pctNarasi',
            'pctBukti',
            'totalSub'
        ));
    }

    public function store(SarpraskeuanganRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $sarpraskeuangan_id = $request->input('sarpraskeuangan_id');
            $kriteria_kode = $request->input('kriteria_kode');

            \App\Models\SarpraskeuanganBukti::create([
                'sarpraskeuangan_id' => $sarpraskeuangan_id,
                'kriteria_kode' => $kriteria_kode,
                'nama_bukti' => $request->input('nama_bukti'),
                'level' => $request->input('level'),
                'status' => $request->input('status_bukti'),
                'link' => $request->input('link'),
                'pic' => $request->input('pic'),
                'deadline' => $request->input('deadline'),
                'catatan' => $request->input('catatan'),
            ]);

            $this->updateBuktiPersen($sarpraskeuangan_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function update(SarpraskeuanganRequest $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\SarpraskeuanganNarasi::findOrFail($id);
            $data = $request->validated();

            if (str_contains($narasi->kriteria_kode, '_EU') && isset($data['status'])) {
                $data['narasi_persen'] = $data['status'] === 'Lengkap' ? 100 : 0;
            }

            $narasi->update($data);

            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_', $narasi->kriteria_kode)[0];
                $allEUs = \App\Models\SarpraskeuanganNarasi::where('sarpraskeuangan_id', $narasi->sarpraskeuangan_id)
                    ->where('kriteria_kode', 'LIKE', $parentKode . '_EU%')
                    ->get();
                
                $totalEU = $allEUs->count();
                $lengkapEU = $allEUs->where('status', 'Lengkap')->count();
                $narasiPersen = $totalEU > 0 ? round(($lengkapEU / $totalEU) * 100) : 0;
                $status = ($narasiPersen == 100) ? 'Memenuhi' : ($narasiPersen > 0 ? 'Memenuhi Sebagian' : 'Belum Memenuhi');

                \App\Models\SarpraskeuanganNarasi::where('sarpraskeuangan_id', $narasi->sarpraskeuangan_id)
                    ->where('kriteria_kode', $parentKode)
                    ->update([
                        'narasi_persen' => $narasiPersen,
                        'status' => $status
                    ]);
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Tersimpan']);
            }

            Alert::success('Berhasil!', 'Narasi berhasil diperbarui.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }

        if ($request->has('type') && $request->type === 'bukti') {
            $bukti = \App\Models\SarpraskeuanganBukti::findOrFail($id);
            $bukti->update([
                'nama_bukti' => $request->input('nama_bukti', $bukti->nama_bukti),
                'level' => $request->input('level', $bukti->level),
                'status' => $request->input('status_bukti', $bukti->status),
                'link' => $request->input('link', $bukti->link),
                'pic' => $request->input('pic', $bukti->pic),
                'deadline' => $request->input('deadline', $bukti->deadline),
                'catatan' => $request->input('catatan', $bukti->catatan),
            ]);

            $newPct = $this->updateBuktiPersen($bukti->sarpraskeuangan_id, $bukti->kriteria_kode);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Berhasil diperbarui.',
                    'pctBukti' => $newPct,
                    'kriteria_kode' => $bukti->kriteria_kode
                ]);
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
            $bukti = \App\Models\SarpraskeuanganBukti::findOrFail($id);
            $sarpraskeuangan_id = $bukti->sarpraskeuangan_id;
            $kriteria_kode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($sarpraskeuangan_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    private function updateBuktiPersen($sarpraskeuangan_id, $kriteria_kode)
    {
        $totalBukti = \App\Models\SarpraskeuanganBukti::where('sarpraskeuangan_id', $sarpraskeuangan_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->count();
        $tersedia = \App\Models\SarpraskeuanganBukti::where('sarpraskeuangan_id', $sarpraskeuangan_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->where('status', 'Tersedia')
            ->count();
        
        $newPctBukti = $totalBukti > 0 ? (int) round(($tersedia / $totalBukti) * 100) : 0;

        \App\Models\SarpraskeuanganNarasi::where('sarpraskeuangan_id', $sarpraskeuangan_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->update(['bukti_persen' => $newPctBukti]);

        return $newPctBukti;
    }
}