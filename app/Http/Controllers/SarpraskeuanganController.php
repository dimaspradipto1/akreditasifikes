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

        $sarpraskeuangan = \App\Models\Sarpraskeuangan::firstOrCreate(
            ['user_id' => $user->id],
            ['tahun' => date('Y')]
        );

        $kriterias = [
            '6.1' => [
                'nama' => 'Fasilitas Fisik untuk Pendidikan dan Pelatihan',
                'is_wajib' => true,
            ],
            '6.2' => [
                'nama' => 'Sumber Informasi',
                'is_wajib' => false,
            ],
            '6.3' => [
                'nama' => 'Sumber Daya Keuangan',
                'is_wajib' => false,
            ],
        ];

        foreach ($kriterias as $kode => $kriteria) {
            \App\Models\SarpraskeuanganNarasi::firstOrCreate(
                ['sarpraskeuangan_id' => $sarpraskeuangan->id, 'kriteria_kode' => $kode],
                ['status' => 'Belum Diisi']
            );
        }

        $narasis = $sarpraskeuangan->narasis()->get()->keyBy('kriteria_kode');
        $subKriterias = $narasis;

        // Hitung persentase global
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

    public function updateNarasi(SarpraskeuanganRequest $request, $id)
    {
        $narasi = \App\Models\SarpraskeuanganNarasi::findOrFail($id);
        $narasi->update($request->validated());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tersimpan']);
        }

        Alert::success('Berhasil!', 'Narasi berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    public function storeBukti(SarpraskeuanganRequest $request)
    {
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

    public function updateBukti(Request $request, $id)
    {
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

        $this->updateBuktiPersen($bukti->sarpraskeuangan_id, $bukti->kriteria_kode);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Berhasil diperbarui.']);
        }

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    public function destroyBukti($id)
    {
        $bukti = \App\Models\SarpraskeuanganBukti::findOrFail($id);
        $sarpraskeuangan_id = $bukti->sarpraskeuangan_id;
        $kriteria_kode = $bukti->kriteria_kode;
        $bukti->delete();
        $this->updateBuktiPersen($sarpraskeuangan_id, $kriteria_kode);

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

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
    }
}
