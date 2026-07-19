<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Exports\TrackerExport;
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

class ReportController extends Controller
{
    public function index()
    {
        $k1_narasi = \Illuminate\Support\Facades\DB::table('vmts_narasis')->avg('narasi_persen') ?? 0;
        $k1_bukti = \Illuminate\Support\Facades\DB::table('vmts_narasis')->avg('bukti_persen') ?? 0;
        $k2_narasi = \Illuminate\Support\Facades\DB::table('kurikulum_narasis')->avg('narasi_persen') ?? 0;
        $k2_bukti = \Illuminate\Support\Facades\DB::table('kurikulum_narasis')->avg('bukti_persen') ?? 0;
        $k3_narasi = \Illuminate\Support\Facades\DB::table('penilaian_narasis')->avg('narasi_persen') ?? 0;
        $k3_bukti = \Illuminate\Support\Facades\DB::table('penilaian_narasis')->avg('bukti_persen') ?? 0;
        $k4_narasi = \Illuminate\Support\Facades\DB::table('mahasiswa_narasis')->avg('narasi_persen') ?? 0;
        $k4_bukti = \Illuminate\Support\Facades\DB::table('mahasiswa_narasis')->avg('bukti_persen') ?? 0;
        $k5_narasi = \Illuminate\Support\Facades\DB::table('doenpkm_narasis')->avg('narasi_persen') ?? 0;
        $k5_bukti = \Illuminate\Support\Facades\DB::table('doenpkm_narasis')->avg('bukti_persen') ?? 0;
        $k6_narasi = \Illuminate\Support\Facades\DB::table('sarpraskeuangan_narasis')->avg('narasi_persen') ?? 0;
        $k6_bukti = \Illuminate\Support\Facades\DB::table('sarpraskeuangan_narasis')->avg('bukti_persen') ?? 0;
        $k7_narasi = \Illuminate\Support\Facades\DB::table('mutu_narasis')->avg('narasi_persen') ?? 0;
        $k7_bukti = \Illuminate\Support\Facades\DB::table('mutu_narasis')->avg('bukti_persen') ?? 0;
        $k8_narasi = \Illuminate\Support\Facades\DB::table('tatakelola_narasis')->avg('narasi_persen') ?? 0;
        $k8_bukti = \Illuminate\Support\Facades\DB::table('tatakelola_narasis')->avg('bukti_persen') ?? 0;

        $kriterias_data = [
            ['id' => 'K1', 'name' => 'Visi, Misi, Tujuan & Strategi', 'skor' => ($k1_narasi + $k1_bukti) / 2],
            ['id' => 'K2', 'name' => 'Kurikulum', 'skor' => ($k2_narasi + $k2_bukti) / 2],
            ['id' => 'K3', 'name' => 'Penilaian', 'skor' => ($k3_narasi + $k3_bukti) / 2],
            ['id' => 'K4', 'name' => 'Mahasiswa', 'skor' => ($k4_narasi + $k4_bukti) / 2],
            ['id' => 'K5', 'name' => 'Dosen, Tendik, Penelitian & PkM', 'skor' => ($k5_narasi + $k5_bukti) / 2],
            ['id' => 'K6', 'name' => 'Sarana, Prasarana & Keuangan', 'skor' => ($k6_narasi + $k6_bukti) / 2],
            ['id' => 'K7', 'name' => 'Penjaminan Mutu', 'skor' => ($k7_narasi + $k7_bukti) / 2],
            ['id' => 'K8', 'name' => 'Tata Kelola & Administrasi', 'skor' => ($k8_narasi + $k8_bukti) / 2],
        ];

        $kriterias = [];
        $total_narasi = 0;
        $total_bukti = 0;

        foreach ($kriterias_data as $k) {
            $status = $k['skor'] >= 50 ? 'Baik' : 'Perlu Perhatian';
            $kriterias[] = [
                'id' => $k['id'],
                'name' => $k['name'],
                'status' => $status,
            ];
        }

        // Bobot dari pengaturan
        $weights = [
            '1' => \App\Models\Setting::where('key', 'bobot_k1')->value('value') ?? 15,
            '2' => \App\Models\Setting::where('key', 'bobot_k2')->value('value') ?? 15,
            '3' => \App\Models\Setting::where('key', 'bobot_k3')->value('value') ?? 12,
            '4' => \App\Models\Setting::where('key', 'bobot_k4')->value('value') ?? 12,
            '5' => \App\Models\Setting::where('key', 'bobot_k5')->value('value') ?? 18,
            '6' => \App\Models\Setting::where('key', 'bobot_k6')->value('value') ?? 12,
            '7' => \App\Models\Setting::where('key', 'bobot_k7')->value('value') ?? 8,
            '8' => \App\Models\Setting::where('key', 'bobot_k8')->value('value') ?? 8,
        ];

        $totalWeight = array_sum($weights);
        if ($totalWeight == 0) $totalWeight = 100;

        $total_narasi = 0;
        $total_bukti = 0;

        // hitung narasi
        $total_narasi += $k1_narasi * ($weights['1'] / $totalWeight);
        $total_narasi += $k2_narasi * ($weights['2'] / $totalWeight);
        $total_narasi += $k3_narasi * ($weights['3'] / $totalWeight);
        $total_narasi += $k4_narasi * ($weights['4'] / $totalWeight);
        $total_narasi += $k5_narasi * ($weights['5'] / $totalWeight);
        $total_narasi += $k6_narasi * ($weights['6'] / $totalWeight);
        $total_narasi += $k7_narasi * ($weights['7'] / $totalWeight);
        $total_narasi += $k8_narasi * ($weights['8'] / $totalWeight);

        // hitung bukti
        $total_bukti += $k1_bukti * ($weights['1'] / $totalWeight);
        $total_bukti += $k2_bukti * ($weights['2'] / $totalWeight);
        $total_bukti += $k3_bukti * ($weights['3'] / $totalWeight);
        $total_bukti += $k4_bukti * ($weights['4'] / $totalWeight);
        $total_bukti += $k5_bukti * ($weights['5'] / $totalWeight);
        $total_bukti += $k6_bukti * ($weights['6'] / $totalWeight);
        $total_bukti += $k7_bukti * ($weights['7'] / $totalWeight);
        $total_bukti += $k8_bukti * ($weights['8'] / $totalWeight);

        $skor_capaian = ($total_narasi + $total_bukti) / 2;

        if ($skor_capaian >= 85) {
            $proyeksi_status = 'Unggul';
        } elseif ($skor_capaian >= 70) {
            $proyeksi_status = 'Baik Sekali';
        } elseif ($skor_capaian >= 50) {
            $proyeksi_status = 'Baik';
        } else {
            $proyeksi_status = 'Tidak Memenuhi';
        }

        return view('pages.reports.index', compact('kriterias', 'total_narasi', 'total_bukti', 'proyeksi_status'));
    }

    public function exportPdf(Request $request)
    {
        $kriteriaId = $request->query('kriteria', 'Keseluruhan');

        if ($kriteriaId === 'Dokumen Bersama') {
            $allDocs = \App\Models\DokumenBersama::all();
            $univDocs = $allDocs->where('level', 'UNIV')->values();
            $fikesDocs = $allDocs->where('level', 'FIKES')->values();

            $pdf = Pdf::loadView('pages.reports.export_dokumen_bersama_pdf', [
                'title' => 'Laporan Akreditasi Dokumen Bersama',
                'univDocs' => $univDocs,
                'fikesDocs' => $fikesDocs
            ]);
            
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('laporan-dokumen-bersama.pdf');
        }

        $data = $this->getKriteriaData($kriteriaId);
        
        $pdf = Pdf::loadView('pages.reports.export_pdf', [
            'title' => 'Laporan Akreditasi ' . $kriteriaId,
            'kriteriaId' => $kriteriaId,
            'data' => $data
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-akreditasi-' . strtolower(str_replace(' ', '-', $kriteriaId)) . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $kriteriaId = $request->query('kriteria', 'Keseluruhan');
        
        if ($kriteriaId === 'Tracker') {
            $data = $this->getTrackerData();
            return Excel::download(new TrackerExport($data), 'tracker-bukti.xlsx');
        }

        $data = $this->getKriteriaData($kriteriaId);
        return Excel::download(new LaporanExport($kriteriaId, $data), 'laporan-akreditasi-' . strtolower(str_replace(' ', '-', $kriteriaId)) . '.xlsx');
    }

    private function getKriteriaData($kriteriaId)
    {
        $tables = [
            'K1' => ['table' => 'vmts_narasis', 'kode' => 'elemen_kode', 'nama' => 'elemen_nama'],
            'K2' => ['table' => 'kurikulum_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
            'K3' => ['table' => 'penilaian_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
            'K4' => ['table' => 'mahasiswa_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
            'K5' => ['table' => 'doenpkm_narasis', 'kode' => 'elemen_kode', 'nama' => 'elemen_nama'],
            'K6' => ['table' => 'sarpraskeuangan_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
            'K7' => ['table' => 'mutu_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
            'K8' => ['table' => 'tatakelola_narasis', 'kode' => 'kriteria_kode', 'nama' => 'kriteria_nama'],
        ];

        $results = [];

        if ($kriteriaId === 'Keseluruhan' || $kriteriaId === 'Tracker' || $kriteriaId === 'Dokumen Bersama') {
            // For keseluruhan, gather all
            foreach ($tables as $kId => $t) {
                $rows = \Illuminate\Support\Facades\DB::table($t['table'])->get();
                foreach ($rows as $row) {
                    $results[] = [
                        'kriteria' => $kId,
                        'kode' => $row->{$t['kode']} ?? '-',
                        'nama' => $row->{$t['nama']} ?? '-',
                        'kondisi_saat_ini' => $row->kondisi_saat_ini ?? '',
                        'status' => $row->status ?? 'Belum Diisi',
                        'narasi_persen' => $row->narasi_persen ?? 0,
                        'bukti_persen' => $row->bukti_persen ?? 0,
                    ];
                }
            }
        } elseif (array_key_exists($kriteriaId, $tables)) {
            $t = $tables[$kriteriaId];
            $rows = \Illuminate\Support\Facades\DB::table($t['table'])->get();
            foreach ($rows as $row) {
                $results[] = [
                    'kriteria' => $kriteriaId,
                    'kode' => $row->{$t['kode']} ?? '-',
                    'nama' => $row->{$t['nama']} ?? '-',
                    'kondisi_saat_ini' => $row->kondisi_saat_ini ?? '',
                    'status' => $row->status ?? 'Belum Diisi',
                    'narasi_persen' => $row->narasi_persen ?? 0,
                    'bukti_persen' => $row->bukti_persen ?? 0,
                ];
            }
        }

        return $results;
    }

    private function getTrackerData()
    {
        $userId = Auth::id() ?? 1; // Default to 1 if not logged in (e.g. CLI or missing auth)
        $allBukti = collect();

        $extractSubK = function($kode) {
            if (preg_match('/^(\d+\.\d+)/', $kode, $matches)) {
                return $matches[1];
            }
            return '-';
        };

        // K1 - VMTS
        $vmts = Vmts::where('user_id', '=', $userId)->first();
        if ($vmts) {
            $buktis = VmtsBukti::where('vmts_id', '=', $vmts->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K1', 'sub_k' => $extractSubK($b->elemen_kode ?? $b->kriteria_kode ?? ''), 'kode_eu' => $b->elemen_kode ?? $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K2 - Kurikulum
        $kurikulum = Kurikulum::where('user_id', '=', $userId)->first();
        if ($kurikulum) {
            $buktis = KurikulumBukti::where('kurikulum_id', '=', $kurikulum->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K2', 'sub_k' => $extractSubK($b->kriteria_kode ?? $b->elemen_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? $b->elemen_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K3 - Penilaian
        $penilaian = Penilaian::where('user_id', '=', $userId)->first();
        if ($penilaian) {
            $buktis = PenilaianBukti::where('penilaian_id', '=', $penilaian->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K3', 'sub_k' => $extractSubK($b->kriteria_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K4 - Mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', '=', $userId)->first();
        if ($mahasiswa) {
            $buktis = MahasiswaBukti::where('mahasiswa_id', '=', $mahasiswa->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K4', 'sub_k' => $extractSubK($b->kriteria_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K5 - Doenpkm
        $doenpkm = Doenpkm::where('user_id', '=', $userId)->first();
        if ($doenpkm) {
            $buktis = DoenpkmBukti::where('doenpkm_id', '=', $doenpkm->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K5', 'sub_k' => $extractSubK($b->elemen_kode ?? $b->kriteria_kode ?? ''), 'kode_eu' => $b->elemen_kode ?? $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K6 - Sarpraskeuangan
        $sarpras = Sarpraskeuangan::where('user_id', '=', $userId)->first();
        if ($sarpras) {
            $buktis = SarpraskeuanganBukti::where('sarpraskeuangan_id', '=', $sarpras->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K6', 'sub_k' => $extractSubK($b->kriteria_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K7 - Mutu
        $mutu = Mutu::where('user_id', '=', $userId)->first();
        if ($mutu) {
            $buktis = MutuBukti::where('mutu_id', '=', $mutu->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K7', 'sub_k' => $extractSubK($b->kriteria_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        // K8 - Tatakelola
        $tatakelola = Tatakelola::where('user_id', '=', $userId)->first();
        if ($tatakelola) {
            $buktis = TatakelolaBukti::where('tatakelola_id', '=', $tatakelola->id)->get()->map(function($b) use ($extractSubK) {
                return (object)['kriteria' => 'K8', 'sub_k' => $extractSubK($b->kriteria_kode ?? ''), 'kode_eu' => $b->kriteria_kode ?? '', 'nama_dokumen' => $b->nama_bukti, 'level' => $b->level ?? 'PRODI', 'status' => $b->status ?? 'Belum Ada', 'pic' => $b->pic ?? '—'];
            });
            $allBukti = $allBukti->merge($buktis);
        }

        $allBukti = $allBukti->map(function($b) {
            if ($b->level === 'FIKES' || $b->level === 'UNIV') {
                $b->status = 'Otomatis'; 
            }
            return $b;
        });

        return $allBukti->sortBy([
            ['kriteria', 'asc'],
            ['kode_eu', 'asc']
        ])->values();
    }
}
