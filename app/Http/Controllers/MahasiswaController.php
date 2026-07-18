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

        // Cari atau buat data Mahasiswa untuk user ini (tahun akreditasi default ke tahun saat ini)
        $mahasiswa = \App\Models\Mahasiswa::firstOrCreate(
            ['user_id' => $user->id],
            ['tahun_akreditasi' => date('Y')]
        );

        // Kriteria yang ada untuk Mahasiswa (K4)
        $kriterias = [
            '4.1' => [
                'nama' => 'Kebijakan Seleksi dan Penerimaan Mahasiswa Baru (Maba)',
                'is_wajib' => true,
                'is_eu' => true,
                'eus' => [
                    '4.1_EU1' => 'Kebijakan seleksi mahasiswa baru',
                    '4.1_EU2' => 'Rasio pendaftar terhadap daya tampung',
                    '4.1_EU3' => 'Transparansi prosedur penerimaan'
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
                    '4.4_EU-1' => 'Kebijakan keselamatan & K3 kampus',
                    '4.4_EU-2' => 'Prosedur tanggap darurat',
                    '4.4_EU-3' => 'Penanganan kekerasan/PPKS',
                    '4.4_EU-4' => 'Layanan medis darurat',
                    '4.4_EU-5' => 'Keamanan lingkungan kampus'
                ]
            ],
        ];

        // Ensure narasis exist for each sub-criteria and EU
        foreach ($kriterias as $kode => $kriteria) {
            \App\Models\MahasiswaNarasi::firstOrCreate(
                ['mahasiswa_id' => $mahasiswa->id, 'kriteria_kode' => $kode],
                ['kriteria_nama' => $kriteria['nama'], 'status' => 'Belum Diisi']
            );

            if ($kriteria['is_eu']) {
                foreach ($kriteria['eus'] as $euKode => $euNama) {
                    $narasiData = ['kriteria_nama' => $euNama, 'status' => 'Draft'];
                    
                    // Pre-fill 4.1 EU-1 to match screenshot
                    if ($euKode == '4.1_EU1') {
                        $narasiData['status'] = 'Lengkap';
                        $narasiData['kondisi_saat_ini'] = 'Kondisi saat ini pada aspek ini sudah berjalan sesuai standar minimum, dengan dokumentasi yang tersedia di tingkat program studi.';
                        $narasiData['data_fakta'] = 'Data pendukung terkumpul dari dokumen internal prodi dan Dokumen Bersama FIKes/Universitas yang relevan.';
                        $narasiData['analisis'] = 'Dibandingkan dengan standar LAM-PTKes, capaian saat ini berada pada kategori memenuhi dengan beberapa catatan minor.';
                        $narasiData['permasalahan'] = 'Masih ditemukan keterlambatan pembaruan dokumen pada beberapa siklus terakhir.';
                        $narasiData['rencana_perbaikan'] = 'Disusun rencana pembaruan berkala setiap semester beserta penanggung jawab pelaksana.';
                    }
                    
                    // Pre-fill 4.2 EU-1 to match screenshot
                    if ($euKode == '4.2_EU-1') {
                        $narasiData['status'] = 'Lengkap';
                        $narasiData['kondisi_saat_ini'] = 'Kondisi saat ini pada aspek ini sudah berjalan sesuai standar minimum, dengan dokumentasi yang tersedia di tingkat program studi.';
                        $narasiData['data_fakta'] = 'Data pendukung terkumpul dari dokumen internal prodi dan Dokumen Bersama FIKes/Universitas yang relevan.';
                        $narasiData['analisis'] = 'Dibandingkan dengan standar LAM-PTKes, capaian saat ini berada pada kategori memenuhi dengan beberapa catatan minor.';
                        $narasiData['permasalahan'] = 'Masih ditemukan keterlambatan pembaruan dokumen pada beberapa siklus terakhir.';
                        $narasiData['rencana_perbaikan'] = 'Disusun rencana pembaruan berkala setiap semester beserta penanggung jawab pelaksana.';
                    }

                    // Pre-fill 4.3 EU-1 to match screenshot
                    if ($euKode == '4.3_EU-1') {
                        $narasiData['status'] = 'Lengkap';
                        $narasiData['kondisi_saat_ini'] = 'Kondisi saat ini pada aspek ini sudah berjalan sesuai standar minimum, dengan dokumentasi yang tersedia di tingkat program studi.';
                        $narasiData['data_fakta'] = 'Data pendukung terkumpul dari dokumen internal prodi dan Dokumen Bersama FIKes/Universitas yang relevan.';
                        $narasiData['analisis'] = 'Dibandingkan dengan standar LAM-PTKes, capaian saat ini berada pada kategori memenuhi dengan beberapa catatan minor.';
                        $narasiData['permasalahan'] = 'Masih ditemukan keterlambatan pembaruan dokumen pada beberapa siklus terakhir.';
                        $narasiData['rencana_perbaikan'] = 'Disusun rencana pembaruan berkala setiap semester beserta penanggung jawab pelaksana.';
                    }

                    // Pre-fill 4.4 EU-1 to match screenshot
                    if ($euKode == '4.4_EU-1') {
                        $narasiData['status'] = 'Lengkap';
                        $narasiData['kondisi_saat_ini'] = 'Kondisi saat ini pada aspek ini sudah berjalan sesuai standar minimum, dengan dokumentasi yang tersedia di tingkat program studi.';
                        $narasiData['data_fakta'] = 'Data pendukung terkumpul dari dokumen internal prodi dan Dokumen Bersama FIKes/Universitas yang relevan.';
                        $narasiData['analisis'] = 'Dibandingkan dengan standar LAM-PTKes, capaian saat ini berada pada kategori memenuhi dengan beberapa catatan minor.';
                        $narasiData['permasalahan'] = 'Masih ditemukan keterlambatan pembaruan dokumen pada beberapa siklus terakhir.';
                        $narasiData['rencana_perbaikan'] = 'Disusun rencana pembaruan berkala setiap semester beserta penanggung jawab pelaksana.';
                    }

                    \App\Models\MahasiswaNarasi::firstOrCreate(
                        ['mahasiswa_id' => $mahasiswa->id, 'kriteria_kode' => $euKode],
                        $narasiData
                    );
                }
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
            $narasi->update($request->validated());

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
