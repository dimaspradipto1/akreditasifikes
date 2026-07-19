<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Vmts;
use App\Models\VmtsBukti;
use App\Models\Kurikulum;
use App\Models\KurikulumBukti;
use App\Models\Penilaian;
use App\Models\PenilaianBukti;
use App\Models\Mahasiswa;
use App\Models\MahasiswaBukti;
use App\Models\Doenpkm;
use App\Models\DoenpkmBukti;
use App\Models\Sarpraskeuangan;
use App\Models\SarpraskeuanganBukti;
use App\Models\Mutu;
use App\Models\MutuBukti;
use App\Models\Tatakelola;
use App\Models\TatakelolaBukti;

class DokumenBersamaController extends Controller
{
    public function index()
    {
        $allDocs = \App\Models\DokumenBersama::all();

        $univDocs = $allDocs->where('level', 'UNIV')->values();
        $fikesDocs = $allDocs->where('level', 'FIKES')->values();

        $univTersedia = $univDocs->whereIn('status', ['Ada', 'Tersedia'])->count();
        $fikesTersedia = $fikesDocs->whereIn('status', ['Ada', 'Tersedia'])->count();

        $totalDocs = $allDocs->count();

        return view('pages.dokumen-bersama.index', compact(
            'univDocs',
            'fikesDocs',
            'univTersedia',
            'fikesTersedia',
            'totalDocs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama_dokumen' => 'required',
            'level' => 'required|in:UNIV,FIKES',
        ]);

        \App\Models\DokumenBersama::create([
            'kode' => $request->kode,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis ?? '-',
            'level' => $request->level,
            'status' => 'Belum Ada',
            'link' => '',
            'pic' => '',
            'deadline' => null,
            'catatan' => '',
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $dokumenBersama = \App\Models\DokumenBersama::findOrFail($id);

        if ($request->has('inline')) {
            $dokumenBersama->update([
                'status' => $request->status,
                'link' => $request->link,
                'pic' => $request->pic,
                'deadline' => $request->deadline,
                'catatan' => $request->catatan,
            ]);

            $namaDokumen = $dokumenBersama->nama_dokumen;

            $updateBuktis = function($buktiClass) use ($namaDokumen, $request) {
                $buktiClass::where('nama_bukti', '=', $namaDokumen)
                    ->update([
                        'status' => $request->status,
                        'link' => $request->link,
                        'pic' => $request->pic,
                        'deadline' => $request->deadline,
                        'catatan' => $request->catatan,
                    ]);
            };

            // Force autoloader to load files containing multiple classes
            class_exists(\App\Models\Vmts::class);
            class_exists(\App\Models\Kurikulum::class);
            class_exists(\App\Models\Penilaian::class);
            class_exists(\App\Models\Mahasiswa::class);
            class_exists(\App\Models\Doenpkm::class);
            class_exists(\App\Models\Sarpraskeuangan::class);
            class_exists(\App\Models\Mutu::class);
            class_exists(\App\Models\Tatakelola::class);

            $updateBuktis(VmtsBukti::class);
            $updateBuktis(KurikulumBukti::class);
            $updateBuktis(PenilaianBukti::class);
            $updateBuktis(MahasiswaBukti::class);
            $updateBuktis(DoenpkmBukti::class);
            $updateBuktis(SarpraskeuanganBukti::class);
            $updateBuktis(MutuBukti::class);
            $updateBuktis(TatakelolaBukti::class);

            return response()->json([
                'success' => true,
                'message' => 'Semua dokumen terkait berhasil diperbarui'
            ]);
        }

        $request->validate([
            'kode' => 'required',
            'nama_dokumen' => 'required',
            'level' => 'required|in:UNIV,FIKES',
        ]);

        $oldNama = $dokumenBersama->nama_dokumen;
        
        $dokumenBersama->update([
            'kode' => $request->kode,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis ?? '-',
            'level' => $request->level,
        ]);
        
        if ($oldNama !== $request->nama_dokumen) {
            $updateBuktis = function($buktiClass) use ($oldNama, $request) {
                $buktiClass::where('nama_bukti', '=', $oldNama)
                    ->update([
                        'nama_bukti' => $request->nama_dokumen
                    ]);
            };

            // Force autoloader to load files containing multiple classes
            class_exists(\App\Models\Vmts::class);
            class_exists(\App\Models\Kurikulum::class);
            class_exists(\App\Models\Penilaian::class);
            class_exists(\App\Models\Mahasiswa::class);
            class_exists(\App\Models\Doenpkm::class);
            class_exists(\App\Models\Sarpraskeuangan::class);
            class_exists(\App\Models\Mutu::class);
            class_exists(\App\Models\Tatakelola::class);

            $updateBuktis(VmtsBukti::class);
            $updateBuktis(KurikulumBukti::class);
            $updateBuktis(PenilaianBukti::class);
            $updateBuktis(MahasiswaBukti::class);
            $updateBuktis(DoenpkmBukti::class);
            $updateBuktis(SarpraskeuanganBukti::class);
            $updateBuktis(MutuBukti::class);
            $updateBuktis(TatakelolaBukti::class);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dokumenBersama = \App\Models\DokumenBersama::findOrFail($id);
        $dokumenBersama->delete();
        
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }
}
