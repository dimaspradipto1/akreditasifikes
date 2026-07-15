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

        // Setup HTML builder untuk DataTable (Bagian B)
        $dataTable = $builder
            ->columns([
                ['data' => 'nama_bukti', 'name' => 'nama_bukti', 'title' => 'NAMA BUKTI', 'width' => '30%'],
                ['data' => 'level', 'name' => 'level', 'title' => 'LEVEL'],
                ['data' => 'status', 'name' => 'status', 'title' => 'STATUS'],
                ['data' => 'link', 'name' => 'link', 'title' => 'LINK', 'orderable' => false],
                ['data' => 'pic', 'name' => 'pic', 'title' => 'PIC'],
                ['data' => 'deadline', 'name' => 'deadline', 'title' => 'DEADLINE'],
                ['data' => 'catatan', 'name' => 'catatan', 'title' => 'CATATAN'],
                ['data' => 'action', 'name' => 'action', 'title' => 'AKSI', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
            ])
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'url' => 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                ],
                'dom' => '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            ]);

        // Hitung persentase global
        $totalSub = $subKriterias->count();
        $memenuhiSub = $subKriterias->where('status', 'Memenuhi')->count();
        $pctNarasi = $totalSub > 0 ? round(($memenuhiSub / $totalSub) * 100) : 0;

        $totalBukti = $penilaian->buktis()->count();
        $tersediaBukti = $penilaian->buktis()->where('status', 'Tersedia')->count();
        $pctBukti = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;

        return view('pages.penilaian.index', compact(
            'penilaian',
            'subKriterias',
            'narasis',
            'pctNarasi',
            'pctBukti',
            'dataTable',
            'totalSub',
            'memenuhiSub'
        ));
    }

    /**
     * Update Narasi via AJAX or standard form submission.
     */
    public function updateNarasi(PenilaianRequest $request, $id)
    {
        class_exists(\App\Models\Penilaian::class);
        $narasi = \App\Models\PenilaianNarasi::findOrFail($id);
        $narasi->update($request->validated());

        // Recalculate parent progress if this is an EU
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

    /**
     * Store new Bukti.
     */
    public function storeBukti(PenilaianRequest $request)
    {
        class_exists(\App\Models\Penilaian::class);
        \App\Models\PenilaianBukti::create($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Update Bukti.
     */
    public function updateBukti(PenilaianRequest $request, $id)
    {
        class_exists(\App\Models\Penilaian::class);
        $bukti = \App\Models\PenilaianBukti::findOrFail($id);
        $bukti->update($request->validated());

        Alert::success('Berhasil!', 'Bukti pendukung berhasil diperbarui.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }

    /**
     * Remove Bukti.
     */
    public function destroyBukti($id)
    {
        class_exists(\App\Models\Penilaian::class);
        $bukti = \App\Models\PenilaianBukti::findOrFail($id);
        $bukti->delete();

        Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
            ->toToast()->autoclose(3000)->timerProgressBar();

        return redirect()->back();
    }
}
