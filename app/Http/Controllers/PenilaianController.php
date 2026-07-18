<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\PenilaianNarasi;
use App\Models\PenilaianBukti;
use App\Http\Requests\PenilaianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use RealRashid\SweetAlert\Facades\Alert;

class PenilaianController extends Controller
{
    /**
     * Tampilkan halaman utama Penilaian.
     */
    public function index(Builder $builder, Request $request)
    {
        // Ambil data kurikulum untuk tahun akreditasi terbaru atau milik user
        $penilaian = Penilaian::where('user_id', Auth::id())
            ->orderBy('tahun_akreditasi', 'desc')
            ->first();

        if (!$penilaian) {
            $penilaian = Penilaian::create([
                'user_id' => Auth::id(),
                'tahun_akreditasi' => date('Y')
            ]);
        }

        // Jika request dari DataTables (untuk Bagian B - Daftar Bukti)
        if ($request->ajax()) {
            $buktis = $penilaian->buktis()->orderBy('created_at', 'desc');

            return DataTables::of($buktis)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'Tersedia') {
                        return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>';
                    } elseif ($row->status == 'Tidak Ada') {
                        return '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Tidak Ada</span>';
                    } else {
                        return '<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Belum Memenuhi</span>';
                    }
                })
                ->editColumn('level', function ($row) {
                    if ($row->level == 'PRODI') return '<span class="badge bg-warning text-dark border border-warning bg-opacity-25">PRODI</span>';
                    if ($row->level == 'FIKES') return '<span class="badge bg-success text-success border border-success bg-opacity-25">FIKES</span>';
                    return '<span class="badge bg-info text-info border border-info bg-opacity-25">UNIV</span>';
                })
                ->editColumn('link', function ($row) {
                    if ($row->link) {
                        return '<a href="' . $row->link . '" target="_blank" class="text-primary text-decoration-none"><i class="bi bi-link-45deg"></i> Lihat Dokumen</a>';
                    }
                    return '<span class="text-muted fst-italic">Belum ada tautan</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-outline-primary mb-1 me-1" onclick="editBukti(' . $row->id . ', \'' . htmlspecialchars($row->nama_bukti, ENT_QUOTES) . '\', \'' . $row->level . '\', \'' . $row->status . '\', \'' . ($row->link ?? '') . '\', \'' . ($row->pic ?? '') . '\', \'' . ($row->deadline ?? '') . '\', \'' . htmlspecialchars($row->catatan ?? '', ENT_QUOTES) . '\')" title="Edit"><i class="bi bi-pencil-square"></i></button>';
                    
                    $delBtn = '<form action="' . route('penilaian.bukti.destroy', $row->id) . '" method="POST" class="d-inline delete-form">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="button" class="btn btn-sm btn-outline-danger mb-1 btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                               </form>';
                    
                    return '<div class="text-nowrap">' . $editBtn . $delBtn . '</div>';
                })
                ->rawColumns(['status', 'level', 'link', 'action'])
                ->make(true);
        }

        // Ambil semua narasi
        $narasis = $penilaian->narasis()->get()->keyBy('kriteria_kode');

        // Ambil parent criteria
        $subKriterias = $narasis->filter(function ($item) {
            return !str_contains($item->kriteria_kode, '_EU');
        })->sortBy('kriteria_kode');

        // Hitung persentase global
        $totalSub = $subKriterias->count();
        $pctNarasi = $totalSub > 0 ? (int) round($subKriterias->avg('narasi_persen')) : 0;

        $pctBukti = $totalSub > 0 ? (int) round($narasis->avg('bukti_persen')) : 0;

        return view('pages.penilaian.index', compact(
            'penilaian',
            'subKriterias',
            'narasis',
            'pctNarasi',
            'pctBukti',
            'totalSub'
        ));
    }

    public function store(PenilaianRequest $request)
    {
        class_exists(\App\Models\Penilaian::class);
        if ($request->has('type') && $request->type === 'bukti') {
            $data = $request->validated();
            if(isset($data['status_bukti'])) {
                $data['status'] = $data['status_bukti'];
                unset($data['status_bukti']);
            }
            $bukti = \App\Models\PenilaianBukti::create($data);
            $this->updateBuktiPersen($bukti->penilaian_id, $bukti->kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function update(PenilaianRequest $request, $id)
    {
        class_exists(\App\Models\Penilaian::class);
        
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\PenilaianNarasi::findOrFail($id);
            $narasi->update($request->validated());

            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_', $narasi->kriteria_kode)[0];
                $parent = \App\Models\PenilaianNarasi::where('penilaian_id', $narasi->penilaian_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();

                if ($parent) {
                    $allEUs = \App\Models\PenilaianNarasi::where('penilaian_id', $narasi->penilaian_id)
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
            $bukti = \App\Models\PenilaianBukti::findOrFail($id);
            $updateData = $request->validated();
            if(isset($updateData['status_bukti'])) {
                $updateData['status'] = $updateData['status_bukti'];
                unset($updateData['status_bukti']);
            }
            $bukti->update($updateData);
            $newPctBukti = $this->updateBuktiPersen($bukti->penilaian_id, $bukti->kriteria_kode);

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
        class_exists(\App\Models\Penilaian::class);
        if ($request->has('type') && $request->type === 'bukti') {
            $bukti = \App\Models\PenilaianBukti::findOrFail($id);
            $penilaian_id = $bukti->penilaian_id;
            $kriteria_kode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($penilaian_id, $kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    /**
     * Update bukti persen per kriteria_kode
     */
    private function updateBuktiPersen($penilaian_id, $kriteria_kode)
    {
        $narasi = \App\Models\PenilaianNarasi::where('penilaian_id', $penilaian_id)
            ->where('kriteria_kode', $kriteria_kode)
            ->first();

        if ($narasi) {
            $totalBukti = \App\Models\PenilaianBukti::where('penilaian_id', $penilaian_id)
                ->where('kriteria_kode', $kriteria_kode)
                ->count();
                
            $tersediaBukti = \App\Models\PenilaianBukti::where('penilaian_id', $penilaian_id)
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
