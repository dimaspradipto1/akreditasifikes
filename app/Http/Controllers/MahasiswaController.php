<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class MahasiswaController extends Controller
{
    /**
     * Ensure Mahasiswa class is loaded to avoid Implicit Binding errors
     * since multiple models share the same file.
     */
    public function __construct()
    {
        class_exists(\App\Models\Mahasiswa::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder, Request $request)
    {
        $user = Auth::user();

        $targetUser = \App\Models\User::where('role', 'koordinatorprodi')->first() ?: \App\Models\User::first();
        $userId = $targetUser ? $targetUser->id : $user->id;

        // Cari atau buat data Mahasiswa (shared globally by year)
        $mahasiswa = \App\Models\Mahasiswa::firstOrCreate(
            ['tahun_akreditasi' => date('Y')],
            ['user_id' => $userId]
        );

        // Always recalculate bukti percentage on page load to keep it dynamic and synchronized
        foreach (['4.1', '4.2', '4.3', '4.4'] as $subKode) {
            $this->updateBuktiPersen($mahasiswa->id, $subKode);
        }

        // Kriteria yang ada untuk Mahasiswa (K4)
        $kriterias = [
            '4.1' => [
                'nama' => 'Kebijakan Seleksi dan Penerimaan Mahasiswa Baru (Maba)',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '4.1_EU-1' => 'Kebijakan & pedoman sistem seleksi penerimaan mahasiswa baru',
                    '4.1_EU-2' => 'Kriteria dan instrumen seleksi (akademik, non-akademik, kesehatan)',
                    '4.1_EU-3' => 'Rasio pendaftar terhadap daya tampung (selektivitas)',
                    '4.1_EU-4' => 'Transparansi, akuntabilitas, dan sosialisasi sistem seleksi',
                    '4.1_EU-5' => 'Kebijakan penerimaan mahasiswa afirmasi/berkebutuhan khusus/3T',
                    '4.1_EU-6' => 'Evaluasi & tindak lanjut efektivitas mekanisme seleksi',
                    '4.1_EU-7' => 'Layanan informasi, registrasi & orientasi mahasiswa baru (PKKMB)'
                ]
            ],
            '4.2' => [
                'nama' => 'Konseling dan Dukungan Mahasiswa',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '4.2_EU-1' => 'Layanan konseling mahasiswa',
                    '4.2_EU-2' => 'Bimbingan akademik',
                    '4.2_EU-3' => 'Beasiswa',
                    '4.2_EU-4' => 'Dukungan non-akademik mahasiswa',
                    '4.2_EU-5' => 'Layanan kesehatan',
                    '4.2_EU-6' => 'Pembinaan soft skills',
                    '4.2_EU-7' => 'Layanan asrama/tempat tinggal'
                ]
            ],
            '4.3' => [
                'nama' => 'Lingkungan Kerja dan Belajar Mahasiswa',
                'is_wajib' => false,
                'is_eu' => true,
                'eus' => [
                    '4.3_EU-1' => 'Kualitas lingkungan belajar',
                    '4.3_EU-2' => 'Fasilitas pendukung non-akademik',
                    '4.3_EU-3' => 'Keterlibatan mahasiswa dalam kegiatan ilmiah/organisasi',
                    '4.3_EU-4' => 'Kepuasan mahasiswa terhadap fasilitas',
                    '4.3_EU-5' => 'Dukungan untuk kegiatan UKM',
                    '4.3_EU-6' => 'Aksesibilitas fasilitas kampus'
                ]
            ],
            '4.4' => [
                'nama' => 'Keselamatan Mahasiswa',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '4.4_EU-1' => 'Kebijakan keselamatan mahasiswa',
                    '4.4_EU-2' => 'Identifikasi & pengelolaan risiko',
                    '4.4_EU-3' => 'Edukasi keselamatan mahasiswa',
                    '4.4_EU-4' => 'Pelaporan insiden',
                    '4.4_EU-5' => 'Keselamatan luar kampus'
                ]
            ],
        ];

        // Ensure narasis exist for each sub-criteria and EU
        $allValidCodes = [];
        foreach ($kriterias as $kode => $kriteria) {
            $allValidCodes[] = $kode;
            \App\Models\MahasiswaNarasi::firstOrCreate(
                ['mahasiswa_id' => $mahasiswa->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $kriteria['nama'], 'status' => 'Belum Diisi']
            );

            if ($kriteria['is_eu']) {
                foreach ($kriteria['eus'] as $euKode => $euNama) {
                    $allValidCodes[] = $euKode;
                    \App\Models\MahasiswaNarasi::firstOrCreate(
                        ['mahasiswa_id' => $mahasiswa->id, 'kriteria_kode' => $euKode],
                        ['kriteria_nama' => $euNama, 'status' => 'Draft']
                    );
                }
            }
        }

        // Cleanup obsolete/duplicate EU codes not in $kriterias (e.g. legacy 4.1_EU1, 4.1_EU2, 4.1_EU3)
        $mahasiswa->narasis()->whereNotIn('kriteria_kode', $allValidCodes)->delete();


        // Recalculate parent narasi persen and status for all sub-kriterias from EUs
        foreach (array_keys($kriterias) as $parentKode) {
            $allEUs = $mahasiswa->narasis()
                ->where('kriteria_kode', 'LIKE', $parentKode . '_EU%')
                ->get();
            
            $totalEU = $allEUs->count();
            if ($totalEU > 0) {
                $lengkapEU = $allEUs->where('status', 'Lengkap')->count();
                $narasiPersen = round(($lengkapEU / $totalEU) * 100);
                $status = ($narasiPersen == 100) ? 'Memenuhi' : ($narasiPersen > 0 ? 'Memenuhi Sebagian' : 'Belum Memenuhi');

                $mahasiswa->narasis()
                    ->where('kriteria_kode', $parentKode)
                    ->update([
                        'narasi_persen' => $narasiPersen,
                        'status' => $status
                    ]);
            }
        }

        $narasis = $mahasiswa->narasis()->get()->keyBy('kriteria_kode');
        
        // Data sub-kriterias (4.1, 4.2, 4.3, 4.4)
        $subKriterias = $narasis->filter(fn($n, $kode) => !str_contains($kode, '_EU'));
        
        if ($request->ajax()) {
            $data = $mahasiswa->buktis()->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $badge = match ($row->status) {
                        'Tersedia' => 'bg-success',
                        'Tidak Ada' => 'bg-danger',
                        'Belum Memenuhi' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge rounded-pill ' . $badge . ' px-3 py-2">' . $row->status . '</span>';
                })
                ->editColumn('level', function ($row) {
                    $badge = match ($row->level) {
                        'PRODI' => 'bg-primary',
                        'FIKES' => 'bg-info text-dark',
                        'UNIV' => 'bg-dark',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge rounded-pill ' . $badge . ' px-3 py-2">' . $row->level . '</span>';
                })
                ->editColumn('link', function ($row) {
                    return $row->link 
                        ? '<a href="' . $row->link . '" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm"><i class="bi bi-link-45deg"></i> Lihat</a>'
                        : '<span class="text-muted fst-italic"><i class="bi bi-dash"></i></span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light border shadow-sm edit-btn" 
                                data-id="' . $row->id . '" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editBuktiModal"
                                title="Edit Bukti">
                                <i class="bi bi-pencil-square text-primary"></i>
                            </button>
                            <form action="' . route('mahasiswa.bukti.destroy', $row->id) . '" method="POST" class="d-inline delete-form">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-light border shadow-sm delete-btn" title="Hapus Bukti">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'level', 'link', 'action'])
                ->make(true);
        }

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
        $pctNarasi = $totalSub > 0 ? (int) round($subKriterias->avg('narasi_persen')) : 0;

        $pctBukti = $totalSub > 0 ? (int) round($subKriterias->avg('bukti_persen')) : 0;

        return view('pages.mahasiswa.index', compact(
            'mahasiswa', 
            'kriterias', 
            'narasis', 
            'subKriterias',
            'pctNarasi',
            'pctBukti',
            'totalSub',
            'dataTable'
        ));
    }

    public function store(MahasiswaRequest $request)
    {
        if ($request->has('type') && $request->type === 'bukti') {
            $data = $request->validated();
            if(isset($data['status_bukti'])) {
                $data['status'] = $data['status_bukti'];
                unset($data['status_bukti']);
            }
            $bukti = \App\Models\MahasiswaBukti::create($data);
            $this->updateBuktiPersen($bukti->mahasiswa_id, $bukti->kriteria_kode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil ditambahkan.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    public function update(MahasiswaRequest $request, $id)
    {
        if ($request->has('type') && $request->type === 'narasi') {
            $narasi = \App\Models\MahasiswaNarasi::findOrFail($id);
            $data = $request->validated();
            if (str_contains($narasi->kriteria_kode, '_EU') && isset($data['status'])) {
                $data['narasi_persen'] = $data['status'] === 'Lengkap' ? 100 : 0;
            }
            $narasi->update($data);

            if (str_contains($narasi->kriteria_kode, '_EU')) {
                $parentKode = explode('_', $narasi->kriteria_kode)[0];
                $parent = \App\Models\MahasiswaNarasi::where('mahasiswa_id', $narasi->mahasiswa_id)
                    ->where('kriteria_kode', $parentKode)
                    ->first();

                if ($parent) {
                    $allEUs = \App\Models\MahasiswaNarasi::where('mahasiswa_id', $narasi->mahasiswa_id)
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
            $bukti = \App\Models\MahasiswaBukti::findOrFail($id);
            
            $updateData = $request->validated();
            if(isset($updateData['status_bukti'])) {
                $updateData['status'] = $updateData['status_bukti'];
                unset($updateData['status_bukti']);
            }

            $bukti->update($updateData);
            $newPctBukti = $this->updateBuktiPersen($bukti->mahasiswa_id, $bukti->kriteria_kode);

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
            $bukti = \App\Models\MahasiswaBukti::findOrFail($id);
            $mahasiswaId = $bukti->mahasiswa_id;
            $kriteriaKode = $bukti->kriteria_kode;
            $bukti->delete();
            $this->updateBuktiPersen($mahasiswaId, $kriteriaKode);

            Alert::success('Berhasil!', 'Bukti pendukung berhasil dihapus.')
                ->toToast()->autoclose(3000)->timerProgressBar();

            return redirect()->back();
        }
        return redirect()->back();
    }

    private function updateBuktiPersen($mahasiswaId, $kriteriaKode)
    {
        if (!$kriteriaKode) return 0;
        
        $totalBukti = \App\Models\MahasiswaBukti::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_kode', $kriteriaKode)
            ->count();
            
        $tersediaBukti = \App\Models\MahasiswaBukti::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_kode', $kriteriaKode)
            ->where('status', 'Tersedia')
            ->count();
            
        $pctBukti = $totalBukti > 0 ? round(($tersediaBukti / $totalBukti) * 100) : 0;
        
        \App\Models\MahasiswaNarasi::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_kode', $kriteriaKode)
            ->update(['bukti_persen' => $pctBukti]);
            
        return $pctBukti;
    }
}
