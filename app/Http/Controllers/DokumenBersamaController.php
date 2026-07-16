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
        $userId = Auth::id();
        $allDocs = collect();

        // Helper to fetch and merge buktis
        $fetchBuktis = function($modelClass, $buktiClass, $relationId) use ($userId, &$allDocs) {
            $parent = $modelClass::where('user_id', '=', $userId)->first();
            if ($parent) {
                $buktis = $buktiClass::where($relationId, '=', $parent->id)
                    ->whereIn('level', ['UNIV', 'FIKES'])
                    ->get();
                $allDocs = $allDocs->merge($buktis);
            }
        };

        $fetchBuktis(Vmts::class, VmtsBukti::class, 'vmts_id');
        $fetchBuktis(Kurikulum::class, KurikulumBukti::class, 'kurikulum_id');
        $fetchBuktis(Penilaian::class, PenilaianBukti::class, 'penilaian_id');
        $fetchBuktis(Mahasiswa::class, MahasiswaBukti::class, 'mahasiswa_id');
        $fetchBuktis(Doenpkm::class, DoenpkmBukti::class, 'doenpkm_id');
        $fetchBuktis(Sarpraskeuangan::class, SarpraskeuanganBukti::class, 'sarpraskeuangan_id');
        $fetchBuktis(Mutu::class, MutuBukti::class, 'mutu_id');
        $fetchBuktis(Tatakelola::class, TatakelolaBukti::class, 'tatakelola_id');

        // Group by nama_dokumen to get unique list
        $uniqueDocs = $allDocs->unique(function ($item) {
            return strtolower(trim($item->nama_bukti));
        });

        // Split by level
        $univDocs = $uniqueDocs->where('level', 'UNIV')->values();
        $fikesDocs = $uniqueDocs->where('level', 'FIKES')->values();

        $univTersedia = $univDocs->whereIn('status', ['Ada', 'Tersedia'])->count();
        $fikesTersedia = $fikesDocs->whereIn('status', ['Ada', 'Tersedia'])->count();

        $totalDocs = $univDocs->count() + $fikesDocs->count();

        return view('pages.dokumen-bersama.index', compact(
            'univDocs',
            'fikesDocs',
            'univTersedia',
            'fikesTersedia',
            'totalDocs'
        ));
    }

    public function update(Request $request)
    {
        $namaDokumen = $request->input('nama_dokumen');
        if (!$namaDokumen) {
            return response()->json(['success' => false, 'message' => 'Nama dokumen tidak valid'], 400);
        }

        $userId = Auth::id();

        // Helper to update buktis
        $updateBuktis = function($modelClass, $buktiClass, $relationId) use ($userId, $namaDokumen, $request) {
            $parent = $modelClass::where('user_id', '=', $userId)->first();
            if ($parent) {
                $buktiClass::where($relationId, '=', $parent->id)
                    ->where('nama_bukti', '=', $namaDokumen)
                    ->update([
                        'status' => $request->status,
                        'link' => $request->link,
                        'pic' => $request->pic,
                        'deadline' => $request->deadline,
                        'catatan' => $request->catatan,
                    ]);
            }
        };

        $updateBuktis(Vmts::class, VmtsBukti::class, 'vmts_id');
        $updateBuktis(Kurikulum::class, KurikulumBukti::class, 'kurikulum_id');
        $updateBuktis(Penilaian::class, PenilaianBukti::class, 'penilaian_id');
        $updateBuktis(Mahasiswa::class, MahasiswaBukti::class, 'mahasiswa_id');
        $updateBuktis(Doenpkm::class, DoenpkmBukti::class, 'doenpkm_id');
        $updateBuktis(Sarpraskeuangan::class, SarpraskeuanganBukti::class, 'sarpraskeuangan_id');
        $updateBuktis(Mutu::class, MutuBukti::class, 'mutu_id');
        $updateBuktis(Tatakelola::class, TatakelolaBukti::class, 'tatakelola_id');

        return response()->json([
            'success' => true,
            'message' => 'Semua dokumen terkait berhasil diperbarui'
        ]);
    }
}
