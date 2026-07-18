<?php

namespace App\Http\Controllers;

use App\Http\Requests\TatakelolaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class TatakelolaController extends Controller
{
    public function __construct()
    {
        class_exists(\App\Models\Tatakelola::class);
    }

    public function index(Builder $builder, Request $request)
    {
        $user = Auth::user();

        $tatakelola = \App\Models\Tatakelola::firstOrCreate(
            ['user_id' => $user->id],
            ['tahun' => date('Y')]
        );

        $kriterias = [
            '8.1' => [
                'nama' => 'Tata Kelola',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '8.1_EU-1' => 'Struktur organisasi & tata pamong',
                    '8.1_EU-2' => 'Kepemimpinan program studi',
                    '8.1_EU-3' => 'SOP layanan',
                    '8.1_EU-4' => 'Kerja sama (MoU)',
                    '8.1_EU-5' => 'Audit & evaluasi kinerja tata kelola'
                ]
            ],
            '8.2' => [
                'nama' => 'Keterlibatan Mahasiswa dan Dosen dalam Tata Kelola',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '8.2_EU-1' => 'Partisipasi mahasiswa & dosen dalam pengambilan keputusan',
                    '8.2_EU-2' => 'Forum/organisasi kemahasiswaan',
                    '8.2_EU-3' => 'Keterlibatan dalam evaluasi program studi'
                ]
            ],
            '8.3' => [
                'nama' => 'Administrasi',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '8.3_EU-1' => 'SOP administrasi akademik (dengan SLA)',
                    '8.3_EU-2' => 'Sistem informasi administrasi',
                    '8.3_EU-3' => 'Layanan kepada mahasiswa/dosen'
                ]
            ],
        ];

        foreach ($kriterias as $kode => $kriteria) {
            \App\Models\TatakelolaNarasi::firstOrCreate(
                ['tatakelola_id' => $tatakelola->id, 'kriteria_kode' => $kode],
                ['status' => 'Belum Diisi']
            );

            // Create EU rows if they exist
            if (isset($kriteria['is_eu']) && $kriteria['is_eu'] && isset($kriteria['eus'])) {
                foreach ($kriteria['eus'] as $euKode => $euNama) {
                    \App\Models\TatakelolaNarasi::firstOrCreate(
                        ['tatakelola_id' => $tatakelola->id, 'kriteria_kode' => $euKode],
                        ['status' => 'Draft']
                    );
                }
            }
        }

        $narasis = $tatakelola->narasis()->get()->keyBy('kriteria_kode');
        
        // Dynamically add kriteria_nama so we don't need a DB migration
        foreach ($narasis as $kode => $narasi) {
            if (str_contains($kode, '_EU')) {
                $parentKode = explode('_EU', $kode)[0];
                $narasi->kriteria_nama = $kriterias[$parentKode]['eus'][$kode] ?? '';
            } else {
                $narasi->kriteria_nama = $kriterias[$kode]['nama'] ?? '';
            }
            
            // Decode narasi_text to support 5 blocks without DB columns
            $decoded = json_decode($narasi->narasi_text, true);
            if (is_array($decoded)) {
                $narasi->kondisi_saat_ini = $decoded['kondisi_saat_ini'] ?? '';
                $narasi->data_fakta = $decoded['data_fakta'] ?? '';
                $narasi->analisis = $decoded['analisis'] ?? '';
            } else {
                $narasi->kondisi_saat_ini = $narasi->narasi_text;
                $narasi->data_fakta = '';
                $narasi->analisis = '';
            }
        }
        
        $subKriterias = $narasis->filter(fn($n) => !str_contains($n->kriteria_kode, '_EU'));

        // Hitung persentase global
        $totalSub = $subKriterias->count();
        $pctNarasi = $totalSub > 0 ? (int) round($subKriterias->avg('narasi_persen')) : 0;
        $pctBukti = $totalSub > 0 ? (int) round($subKriterias->avg('bukti_persen')) : 0;

        return view('pages.tatakelola.index', compact(
            'tatakelola', 
            'kriterias', 
            'narasis', 
            'subKriterias',
            'pctNarasi',
            'pctBukti',
            'totalSub'
        ));
    }

    public function store(TatakelolaRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $tatakelola_id = $request->input('tatakelola_id');
            $kriteria_kode = $request->input('kriteria_kode');

            \App\Models\TatakelolaBukti::create([
                'tatakelola_id' => $tatakelola_id,
                'kriteria_kode' => $kriteria_kode,
                'nama_bukti' => $request->input('nama_bukti'),
                'level' => $request->input('level'),
                'status' => $request->input('status_bukti'),
                'link' => $request->input('link'),
                'pic' => $request->input('pic'),
                'deadline' => $request->input('deadline'),
                'catatan' => $request->input('catatan'),
            ]);

            $this->updateBuktiPersen($tatakelola_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\TatakelolaNarasi::findOrFail($id);
            $data = $request->validate([
                'status' => 'required|string',
                'kondisi_saat_ini' => 'nullable|string',
                'data_fakta' => 'nullable|string',
                'analisis' => 'nullable|string',
                'narasi_persen' => 'nullable|integer'
            ]);
            
            // Encode missing columns into narasi_text to avoid migration
            $narasi_text = [
                'kondisi_saat_ini' => $data['kondisi_saat_ini'] ?? '',
                'data_fakta' => $data['data_fakta'] ?? '',
                'analisis' => $data['analisis'] ?? '',
            ];
            $data['narasi_text'] = json_encode($narasi_text);
            unset($data['kondisi_saat_ini'], $data['data_fakta'], $data['analisis']);

            if (str_contains($narasi->kriteria_kode, '_EU') && isset($data['status'])) {
                $data['narasi_persen'] = $data['status'] === 'Lengkap' ? 100 : 0;
            }

            $narasi->update($data);

            // Jika ini adalah EU, update rata-rata narasi_persen ke parent
            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_EU', $narasi->kriteria_kode)[0];
                $parent = \App\Models\TatakelolaNarasi::where('tatakelola_id', $narasi->tatakelola_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();
                    
                if ($parent) {
                    $allEUs = \App\Models\TatakelolaNarasi::where('tatakelola_id', $narasi->tatakelola_id)
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
            $bukti = \App\Models\TatakelolaBukti::findOrFail($id);
            $bukti->update([
                'nama_bukti' => $request->input('nama_bukti', $bukti->nama_bukti),
                'level' => $request->input('level', $bukti->level),
                'status' => $request->input('status_bukti', $bukti->status),
                'link' => $request->input('link', $bukti->link),
                'pic' => $request->input('pic', $bukti->pic),
                'deadline' => $request->input('deadline', $bukti->deadline),
                'catatan' => $request->input('catatan', $bukti->catatan),
            ]);

            $newPct = $this->updateBuktiPersen($bukti->tatakelola_id, $bukti->kriteria_kode);

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
            $bukti = \App\Models\TatakelolaBukti::findOrFail($id);
            $tatakelola_id = $bukti->tatakelola_id;
            $kriteria_kode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($tatakelola_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    private function updateBuktiPersen($tatakelola_id, $kriteria_kode)
    {
        $totalBukti = \App\Models\TatakelolaBukti::where('tatakelola_id', $tatakelola_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->count();
        $tersedia = \App\Models\TatakelolaBukti::where('tatakelola_id', $tatakelola_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->where('status', 'Tersedia')
            ->count();
        
        $newPctBukti = $totalBukti > 0 ? (int) round(($tersedia / $totalBukti) * 100) : 0;

        \App\Models\TatakelolaNarasi::where('tatakelola_id', $tatakelola_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->update(['bukti_persen' => $newPctBukti]);
            
        return $newPctBukti;
    }
}
