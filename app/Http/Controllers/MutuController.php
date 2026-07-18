<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class MutuController extends Controller
{
    public function __construct()
    {
        class_exists(\App\Models\Mutu::class);
    }

    public function index(Builder $builder, Request $request)
    {
        $user = Auth::user();

        $mutu = \App\Models\Mutu::firstOrCreate(
            ['user_id' => $user->id],
            ['tahun' => date('Y')]
        );

        $kriterias = [
            '7.1' => [
                'nama' => 'Sistem Penjaminan Mutu',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '7.1_EU-1' => 'Kebijakan & standar mutu (SPMI)',
                    '7.1_EU-2' => 'Siklus PPEPP',
                    '7.1_EU-3' => 'Pelaksanaan Audit Mutu Internal (AMI)',
                    '7.1_EU-4' => 'Rapat Tinjauan Manajemen (RTM)',
                    '7.1_EU-5' => 'Tindak lanjut hasil evaluasi mutu'
                ]
            ],
        ];

        foreach ($kriterias as $kode => $kriteria) {
            \App\Models\MutuNarasi::firstOrCreate(
                ['mutu_id' => $mutu->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $kriteria['nama'], 'status' => 'Belum Diisi']
            );

            if (isset($kriteria['is_eu']) && $kriteria['is_eu'] && isset($kriteria['eus'])) {
                foreach ($kriteria['eus'] as $euKode => $euNama) {
                    \App\Models\MutuNarasi::firstOrCreate(
                        ['mutu_id' => $mutu->id, 'kriteria_kode' => $euKode],
                        ['kriteria_nama' => $euNama, 'status' => 'Draft']
                    );
                }
            }
        }

        // Default Buktis removed per user request

        $narasis = $mutu->narasis()->get()->keyBy('kriteria_kode');
        $subKriterias = $narasis->filter(fn($n) => !str_contains($n->kriteria_kode, '_EU'));

        // Hitung persentase global
        $totalSub = $subKriterias->count();
        $pctNarasi = $totalSub > 0 ? (int) round($subKriterias->avg('narasi_persen')) : 0;
        $pctBukti = $totalSub > 0 ? (int) round($subKriterias->avg('bukti_persen')) : 0;

        return view('pages.mutu.index', compact(
            'mutu', 
            'kriterias', 
            'narasis', 
            'subKriterias',
            'pctNarasi',
            'pctBukti',
            'totalSub'
        ));
    }

    public function store(MutuRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $mutu_id = $request->input('mutu_id');
            $kriteria_kode = $request->input('kriteria_kode');

            \App\Models\MutuBukti::create([
                'mutu_id' => $mutu_id,
                'kriteria_kode' => $kriteria_kode,
                'nama_bukti' => $request->input('nama_bukti'),
                'level' => $request->input('level'),
                'status' => $request->input('status_bukti'),
                'link' => $request->input('link'),
                'pic' => $request->input('pic'),
                'deadline' => $request->input('deadline'),
                'catatan' => $request->input('catatan'),
            ]);

            $this->updateBuktiPersen($mutu_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\MutuNarasi::findOrFail($id);
            $data = $request->validate([
                'status' => 'required|string',
                'narasi' => 'nullable|string'
            ]);

            if (str_contains($narasi->kriteria_kode, '_EU') && isset($data['status'])) {
                $data['narasi_persen'] = $data['status'] === 'Lengkap' ? 100 : 0;
            }

            $narasi->update($data);

            // Jika ini adalah EU, update rata-rata narasi_persen ke parent
            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_EU', $narasi->kriteria_kode)[0];
                $parent = \App\Models\MutuNarasi::where('mutu_id', $narasi->mutu_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();
                    
                if ($parent) {
                    $allEUs = \App\Models\MutuNarasi::where('mutu_id', $narasi->mutu_id)
                        ->where('kriteria_kode', 'LIKE', $parentKode . '_EU%')
                        ->get();
                        
                    $totalEU = $allEUs->count();
                    $lengkapEU = $allEUs->where('status', 'Lengkap')->count();
                    $narasiPersen = $totalEU > 0 ? round(($lengkapEU / $totalEU) * 100) : 0;
                    
                    $status = ($narasiPersen == 100) ? 'Memenuhi' : (($narasiPersen > 0) ? 'Memenuhi Sebagian' : 'Belum Memenuhi');
                    
                    $parent->update([
                        'narasi_persen' => $narasiPersen,
                        'status' => $status
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Tersimpan']);
            }

            Alert::success('Berhasil!', 'Narasi berhasil diperbarui.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }

        if ($request->has('type') && $request->type === 'bukti') {
            $bukti = \App\Models\MutuBukti::findOrFail($id);
            
            $bukti->update([
                'nama_bukti' => $request->input('nama_bukti', $bukti->nama_bukti),
                'level' => $request->input('level', $bukti->level),
                'status' => $request->input('status_bukti', $bukti->status),
                'link' => $request->input('link', $bukti->link),
                'pic' => $request->input('pic', $bukti->pic),
                'deadline' => $request->input('deadline', $bukti->deadline),
                'catatan' => $request->input('catatan', $bukti->catatan),
            ]);

            $newPct = $this->updateBuktiPersen($bukti->mutu_id, $bukti->kriteria_kode);

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
            $bukti = \App\Models\MutuBukti::findOrFail($id);
            $mutu_id = $bukti->mutu_id;
            $kriteria_kode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($mutu_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    private function updateBuktiPersen($mutu_id, $kriteria_kode)
    {
        $totalBukti = \App\Models\MutuBukti::where('mutu_id', $mutu_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->count();
        $tersedia = \App\Models\MutuBukti::where('mutu_id', $mutu_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->where('status', 'Tersedia')
            ->count();
        
        $newPctBukti = $totalBukti > 0 ? (int) round(($tersedia / $totalBukti) * 100) : 0;

        \App\Models\MutuNarasi::where('mutu_id', $mutu_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->update(['bukti_persen' => $newPctBukti]);
            
        return $newPctBukti;
    }
}
