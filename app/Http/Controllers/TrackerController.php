<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

class TrackerController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $allBukti = collect();

        // Helper to extract sub kriteria (e.g., "1.1.1" -> "1.1", "4.1_EU-1" -> "4.1")
        $extractSubK = function($kode) {
            if (preg_match('/^(\d+\.\d+)/', $kode, $matches)) {
                return $matches[1];
            }
            return '-';
        };

        // 1. K1 - VMTS
        $vmts = Vmts::where('user_id', '=', $userId)->first();
        if ($vmts) {
            $buktis = VmtsBukti::where('vmts_id', '=', $vmts->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K1',
                    'sub_k' => $extractSubK($b->elemen_kode ?? $b->kriteria_kode ?? ''),
                    'kode_eu' => $b->elemen_kode ?? $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 2. K2 - Kurikulum
        $kurikulum = Kurikulum::where('user_id', '=', $userId)->first();
        if ($kurikulum) {
            $buktis = KurikulumBukti::where('kurikulum_id', '=', $kurikulum->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K2',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? $b->elemen_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? $b->elemen_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 3. K3 - Penilaian
        $penilaian = Penilaian::where('user_id', '=', $userId)->first();
        if ($penilaian) {
            $buktis = PenilaianBukti::where('penilaian_id', '=', $penilaian->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K3',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 4. K4 - Mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', '=', $userId)->first();
        if ($mahasiswa) {
            $buktis = MahasiswaBukti::where('mahasiswa_id', '=', $mahasiswa->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K4',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 5. K5 - Doenpkm
        $doenpkm = Doenpkm::where('user_id', '=', $userId)->first();
        if ($doenpkm) {
            $buktis = DoenpkmBukti::where('doenpkm_id', '=', $doenpkm->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K5',
                    'sub_k' => $extractSubK($b->elemen_kode ?? $b->kriteria_kode ?? ''),
                    'kode_eu' => $b->elemen_kode ?? $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 6. K6 - Sarpraskeuangan
        $sarpras = Sarpraskeuangan::where('user_id', '=', $userId)->first();
        if ($sarpras) {
            $buktis = SarpraskeuanganBukti::where('sarpraskeuangan_id', '=', $sarpras->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K6',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 7. K7 - Mutu
        $mutu = Mutu::where('user_id', '=', $userId)->first();
        if ($mutu) {
            $buktis = MutuBukti::where('mutu_id', '=', $mutu->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K7',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // 8. K8 - Tatakelola
        $tatakelola = Tatakelola::where('user_id', '=', $userId)->first();
        if ($tatakelola) {
            $buktis = TatakelolaBukti::where('tatakelola_id', '=', $tatakelola->id)->get()->map(function($b) use ($extractSubK) {
                return (object)[
                    'kriteria' => 'K8',
                    'sub_k' => $extractSubK($b->kriteria_kode ?? ''),
                    'kode_eu' => $b->kriteria_kode ?? '',
                    'nama_dokumen' => $b->nama_bukti,
                    'level' => $b->level ?? 'PRODI',
                    'status' => $b->status ?? 'Belum Ada',
                    'pic' => $b->pic ?? '—',
                ];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // Calculate stats
        $totalBukti = $allBukti->count();
        $prodiCount = $allBukti->where('level', 'PRODI')->count();
        $fikesCount = $allBukti->where('level', 'FIKES')->count();
        $univCount = $allBukti->where('level', 'UNIV')->count();

        // Change status to 'Otomatis' for FIKES and UNIV so the view shows the badge
        $allBukti = $allBukti->map(function($b) {
            if ($b->level === 'FIKES' || $b->level === 'UNIV') {
                $b->status = 'Otomatis'; 
            }
            return $b;
        });

        // Sort by kriteria and kode_eu
        $allBukti = $allBukti->sortBy([
            ['kriteria', 'asc'],
            ['kode_eu', 'asc']
        ])->values();

        return view('pages.tracker.index', compact(
            'allBukti',
            'totalBukti',
            'prodiCount',
            'fikesCount',
            'univCount'
        ));
    }
}
