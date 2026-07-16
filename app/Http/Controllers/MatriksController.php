<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vmts;
use App\Models\Kurikulum;
use App\Models\Penilaian;
use App\Models\Mahasiswa;
use App\Models\Doenpkm;
use App\Models\Sarpraskeuangan;
use App\Models\Mutu;
use App\Models\Tatakelola;

class MatriksController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $tahun = date('Y');

        // 1. Fetch parent models
        $vmts = Vmts::where('user_id', '=', $userId)->first();
        $kurikulum = Kurikulum::where('user_id', '=', $userId)->first();
        $penilaian = Penilaian::where('user_id', '=', $userId)->first();
        $mahasiswa = Mahasiswa::where('user_id', '=', $userId)->first();
        $doenpkm = Doenpkm::where('user_id', '=', $userId)->first();
        $sarpras = Sarpraskeuangan::where('user_id', '=', $userId)->first();
        $mutu = Mutu::where('user_id', '=', $userId)->first();
        $tatakelola = Tatakelola::where('user_id', '=', $userId)->first();

        // 2. Fetch Narasis (Collections)
        $vmtsN = $vmts ? $vmts->narasis()->get() : collect();
        $kurikulumN = $kurikulum ? $kurikulum->narasis()->get()->keyBy('kriteria_kode') : collect();
        $penilaianN = $penilaian ? $penilaian->narasis()->get()->keyBy('kriteria_kode') : collect();
        $mahasiswaN = $mahasiswa ? $mahasiswa->narasis()->get()->keyBy('kriteria_kode') : collect();
        $doenpkmN = $doenpkm ? $doenpkm->narasis()->get() : collect();
        $sarprasN = $sarpras ? $sarpras->narasis()->get()->keyBy('kriteria_kode') : collect();
        $mutuN = $mutu ? $mutu->narasis()->get()->keyBy('kriteria_kode') : collect();
        $tatakelolaN = $tatakelola ? $tatakelola->narasis()->get()->keyBy('kriteria_kode') : collect();

        // Helper function for K5 (Doenpkm) which only has EUs in DB
        $getDoenpkmStats = function($prefix) use ($doenpkmN) {
            $eus = $doenpkmN->filter(function($n) use ($prefix) {
                return str_starts_with($n->elemen_kode, $prefix . '-');
            });
            $count = $eus->count();
            if ($count == 0) return ['narasi' => 0, 'bukti' => 0, 'status' => 'Tidak Memenuhi'];
            $narasi = (int) round($eus->avg('narasi_persen'));
            $bukti = (int) round($eus->avg('bukti_persen'));
            $memenuhi = $eus->where('status', 'Memenuhi')->count();
            $status = ($memenuhi == $count && $count > 0) ? 'Memenuhi' : 'Tidak Memenuhi';
            return ['narasi' => $narasi, 'bukti' => $bukti, 'status' => $status];
        };

        // Static definition of 26 sub-criteria based on user screenshot and controllers
        $subKriteria = [
            // K1 (1)
            [
                'no' => 1, 'kode' => '1.1', 'nama' => 'Pernyataan Visi, Misi, Tujuan, dan Strategi', 
                'kriteria' => 'Visi, Misi, Tujuan, dan Strategi', 'eu' => 6, 'bukti' => 16, 'kategori' => 'WAJIB',
                'simulasi' => $vmtsN->count() > 0 && $vmtsN->where('status', '!=', 'Memenuhi')->count() == 0 ? 'Memenuhi' : 'Tidak Memenuhi',
                'pct_narasi' => $vmtsN->count() > 0 ? (int)round($vmtsN->avg('narasi_persen')) : 0,
                'pct_bukti' => $vmtsN->count() > 0 ? (int)round($vmtsN->avg('bukti_persen')) : 0,
            ],
            // K2 (4)
            [
                'no' => 2, 'kode' => '2.1', 'nama' => 'Capaian Pembelajaran dalam Kurikulum', 'kriteria' => 'Kurikulum', 'eu' => 7, 'bukti' => 16, 'kategori' => 'WAJIB',
                'simulasi' => $kurikulumN->has('2.1') ? $kurikulumN->get('2.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $kurikulumN->has('2.1') ? $kurikulumN->get('2.1')->narasi_persen : 0,
                'pct_bukti' => $kurikulumN->has('2.1') ? $kurikulumN->get('2.1')->bukti_persen : 0,
            ],
            [
                'no' => 2, 'kode' => '2.2', 'nama' => 'Struktur Kurikulum', 'kriteria' => 'Kurikulum', 'eu' => 3, 'bukti' => 6, 'kategori' => 'WAJIB',
                'simulasi' => $kurikulumN->has('2.2') ? $kurikulumN->get('2.2')->status : 'Tidak Memenuhi',
                'pct_narasi' => $kurikulumN->has('2.2') ? $kurikulumN->get('2.2')->narasi_persen : 0,
                'pct_bukti' => $kurikulumN->has('2.2') ? $kurikulumN->get('2.2')->bukti_persen : 0,
            ],
            [
                'no' => 2, 'kode' => '2.3', 'nama' => 'Isi Kurikulum', 'kriteria' => 'Kurikulum', 'eu' => 4, 'bukti' => 9, 'kategori' => 'WAJIB',
                'simulasi' => $kurikulumN->has('2.3') ? $kurikulumN->get('2.3')->status : 'Tidak Memenuhi',
                'pct_narasi' => $kurikulumN->has('2.3') ? $kurikulumN->get('2.3')->narasi_persen : 0,
                'pct_bukti' => $kurikulumN->has('2.3') ? $kurikulumN->get('2.3')->bukti_persen : 0,
            ],
            [
                'no' => 2, 'kode' => '2.4', 'nama' => 'Metode dan Pengalaman Pembelajaran', 'kriteria' => 'Kurikulum', 'eu' => 3, 'bukti' => 4, 'kategori' => 'WAJIB',
                'simulasi' => $kurikulumN->has('2.4') ? $kurikulumN->get('2.4')->status : 'Tidak Memenuhi',
                'pct_narasi' => $kurikulumN->has('2.4') ? $kurikulumN->get('2.4')->narasi_persen : 0,
                'pct_bukti' => $kurikulumN->has('2.4') ? $kurikulumN->get('2.4')->bukti_persen : 0,
            ],
            // K3 (4)
            [
                'no' => 3, 'kode' => '3.1', 'nama' => 'Kebijakan dan Sistem Penilaian', 'kriteria' => 'Penilaian', 'eu' => 3, 'bukti' => 7, 'kategori' => 'WAJIB',
                'simulasi' => $penilaianN->has('3.1') ? $penilaianN->get('3.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $penilaianN->has('3.1') ? $penilaianN->get('3.1')->narasi_persen : 0,
                'pct_bukti' => $penilaianN->has('3.1') ? $penilaianN->get('3.1')->bukti_persen : 0,
            ],
            [
                'no' => 3, 'kode' => '3.2', 'nama' => 'Penilaian dalam Mendukung Pembelajaran', 'kriteria' => 'Penilaian', 'eu' => 3, 'bukti' => 6, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $penilaianN->has('3.2') ? $penilaianN->get('3.2')->status : 'Tidak Memenuhi',
                'pct_narasi' => $penilaianN->has('3.2') ? $penilaianN->get('3.2')->narasi_persen : 0,
                'pct_bukti' => $penilaianN->has('3.2') ? $penilaianN->get('3.2')->bukti_persen : 0,
            ],
            [
                'no' => 3, 'kode' => '3.3', 'nama' => 'Penilaian untuk Mendukung Pengambilan Keputusan', 'kriteria' => 'Penilaian', 'eu' => 5, 'bukti' => 9, 'kategori' => 'WAJIB',
                'simulasi' => $penilaianN->has('3.3') ? $penilaianN->get('3.3')->status : 'Tidak Memenuhi',
                'pct_narasi' => $penilaianN->has('3.3') ? $penilaianN->get('3.3')->narasi_persen : 0,
                'pct_bukti' => $penilaianN->has('3.3') ? $penilaianN->get('3.3')->bukti_persen : 0,
            ],
            [
                'no' => 3, 'kode' => '3.4', 'nama' => 'Pengendalian Mutu Penilaian', 'kriteria' => 'Penilaian', 'eu' => 6, 'bukti' => 8, 'kategori' => 'WAJIB',
                'simulasi' => $penilaianN->has('3.4') ? $penilaianN->get('3.4')->status : 'Tidak Memenuhi',
                'pct_narasi' => $penilaianN->has('3.4') ? $penilaianN->get('3.4')->narasi_persen : 0,
                'pct_bukti' => $penilaianN->has('3.4') ? $penilaianN->get('3.4')->bukti_persen : 0,
            ],
            // K4 (4)
            [
                'no' => 4, 'kode' => '4.1', 'nama' => 'Kebijakan Seleksi dan Penerimaan Mahasiswa Baru (Maba)', 'kriteria' => 'Mahasiswa', 'eu' => 3, 'bukti' => 12, 'kategori' => 'WAJIB',
                'simulasi' => $mahasiswaN->has('4.1') ? $mahasiswaN->get('4.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $mahasiswaN->has('4.1') ? $mahasiswaN->get('4.1')->narasi_persen : 0,
                'pct_bukti' => $mahasiswaN->has('4.1') ? $mahasiswaN->get('4.1')->bukti_persen : 0,
            ],
            [
                'no' => 4, 'kode' => '4.2', 'nama' => 'Konseling dan Dukungan Mahasiswa', 'kriteria' => 'Mahasiswa', 'eu' => 7, 'bukti' => 11, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $mahasiswaN->has('4.2') ? $mahasiswaN->get('4.2')->status : 'Tidak Memenuhi',
                'pct_narasi' => $mahasiswaN->has('4.2') ? $mahasiswaN->get('4.2')->narasi_persen : 0,
                'pct_bukti' => $mahasiswaN->has('4.2') ? $mahasiswaN->get('4.2')->bukti_persen : 0,
            ],
            [
                'no' => 4, 'kode' => '4.3', 'nama' => 'Lingkungan Kerja dan Belajar Mahasiswa', 'kriteria' => 'Mahasiswa', 'eu' => 6, 'bukti' => 11, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $mahasiswaN->has('4.3') ? $mahasiswaN->get('4.3')->status : 'Tidak Memenuhi',
                'pct_narasi' => $mahasiswaN->has('4.3') ? $mahasiswaN->get('4.3')->narasi_persen : 0,
                'pct_bukti' => $mahasiswaN->has('4.3') ? $mahasiswaN->get('4.3')->bukti_persen : 0,
            ],
            [
                'no' => 4, 'kode' => '4.4', 'nama' => 'Keselamatan Mahasiswa', 'kriteria' => 'Mahasiswa', 'eu' => 5, 'bukti' => 10, 'kategori' => 'WAJIB',
                'simulasi' => $mahasiswaN->has('4.4') ? $mahasiswaN->get('4.4')->status : 'Tidak Memenuhi',
                'pct_narasi' => $mahasiswaN->has('4.4') ? $mahasiswaN->get('4.4')->narasi_persen : 0,
                'pct_bukti' => $mahasiswaN->has('4.4') ? $mahasiswaN->get('4.4')->bukti_persen : 0,
            ],
            // K5 (6)
            [
                'no' => 5, 'kode' => '5.1', 'nama' => 'Kebijakan Penetapan Dosen', 'kriteria' => 'Dosen & PkM', 'eu' => 3, 'bukti' => 5, 'kategori' => 'WAJIB',
                'simulasi' => $getDoenpkmStats('5.1')['status'], 'pct_narasi' => $getDoenpkmStats('5.1')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.1')['bukti'],
            ],
            [
                'no' => 5, 'kode' => '5.2', 'nama' => 'Kinerja dan Perilaku Dosen', 'kriteria' => 'Dosen & PkM', 'eu' => 4, 'bukti' => 6, 'kategori' => 'WAJIB',
                'simulasi' => $getDoenpkmStats('5.2')['status'], 'pct_narasi' => $getDoenpkmStats('5.2')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.2')['bukti'],
            ],
            [
                'no' => 5, 'kode' => '5.3', 'nama' => 'Pengembangan Profesional Berkelanjutan untuk Dosen', 'kriteria' => 'Dosen & PkM', 'eu' => 3, 'bukti' => 5, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $getDoenpkmStats('5.3')['status'], 'pct_narasi' => $getDoenpkmStats('5.3')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.3')['bukti'],
            ],
            [
                'no' => 5, 'kode' => '5.4', 'nama' => 'Pengembangan Tenaga Kependidikan', 'kriteria' => 'Dosen & PkM', 'eu' => 2, 'bukti' => 3, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $getDoenpkmStats('5.4')['status'], 'pct_narasi' => $getDoenpkmStats('5.4')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.4')['bukti'],
            ],
            [
                'no' => 5, 'kode' => '5.5', 'nama' => 'Relevansi Penelitian sesuai Visi dan Unggulan PS', 'kriteria' => 'Dosen & PkM', 'eu' => 2, 'bukti' => 3, 'kategori' => 'WAJIB',
                'simulasi' => $getDoenpkmStats('5.5')['status'], 'pct_narasi' => $getDoenpkmStats('5.5')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.5')['bukti'],
            ],
            [
                'no' => 5, 'kode' => '5.6', 'nama' => 'Relevansi PkM sesuai Visi dan Unggulan PS', 'kriteria' => 'Dosen & PkM', 'eu' => 2, 'bukti' => 3, 'kategori' => 'WAJIB',
                'simulasi' => $getDoenpkmStats('5.6')['status'], 'pct_narasi' => $getDoenpkmStats('5.6')['narasi'], 'pct_bukti' => $getDoenpkmStats('5.6')['bukti'],
            ],
            // K6 (3)
            [
                'no' => 6, 'kode' => '6.1', 'nama' => 'Fasilitas Fisik untuk Pendidikan dan Pelatihan', 'kriteria' => 'Sarana, Prasarana & Keuangan', 'eu' => 5, 'bukti' => 8, 'kategori' => 'WAJIB',
                'simulasi' => $sarprasN->has('6.1') ? $sarprasN->get('6.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $sarprasN->has('6.1') ? $sarprasN->get('6.1')->narasi_persen : 0,
                'pct_bukti' => $sarprasN->has('6.1') ? $sarprasN->get('6.1')->bukti_persen : 0,
            ],
            [
                'no' => 6, 'kode' => '6.2', 'nama' => 'Sumber Informasi', 'kriteria' => 'Sarana, Prasarana & Keuangan', 'eu' => 3, 'bukti' => 5, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $sarprasN->has('6.2') ? $sarprasN->get('6.2')->status : 'Tidak Memenuhi',
                'pct_narasi' => $sarprasN->has('6.2') ? $sarprasN->get('6.2')->narasi_persen : 0,
                'pct_bukti' => $sarprasN->has('6.2') ? $sarprasN->get('6.2')->bukti_persen : 0,
            ],
            [
                'no' => 6, 'kode' => '6.3', 'nama' => 'Sumber Daya Keuangan', 'kriteria' => 'Sarana, Prasarana & Keuangan', 'eu' => 4, 'bukti' => 6, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $sarprasN->has('6.3') ? $sarprasN->get('6.3')->status : 'Tidak Memenuhi',
                'pct_narasi' => $sarprasN->has('6.3') ? $sarprasN->get('6.3')->narasi_persen : 0,
                'pct_bukti' => $sarprasN->has('6.3') ? $sarprasN->get('6.3')->bukti_persen : 0,
            ],
            // K7 (1)
            [
                'no' => 7, 'kode' => '7.1', 'nama' => 'Sistem Penjaminan Mutu', 'kriteria' => 'Penjaminan Mutu', 'eu' => 5, 'bukti' => 10, 'kategori' => 'WAJIB',
                'simulasi' => $mutuN->has('7.1') ? $mutuN->get('7.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $mutuN->has('7.1') ? $mutuN->get('7.1')->narasi_persen : 0,
                'pct_bukti' => $mutuN->has('7.1') ? $mutuN->get('7.1')->bukti_persen : 0,
            ],
            // K8 (3)
            [
                'no' => 8, 'kode' => '8.1', 'nama' => 'Tata Kelola', 'kriteria' => 'Tata Kelola', 'eu' => 5, 'bukti' => 12, 'kategori' => 'WAJIB',
                'simulasi' => $tatakelolaN->has('8.1') ? $tatakelolaN->get('8.1')->status : 'Tidak Memenuhi',
                'pct_narasi' => $tatakelolaN->has('8.1') ? $tatakelolaN->get('8.1')->narasi_persen : 0,
                'pct_bukti' => $tatakelolaN->has('8.1') ? $tatakelolaN->get('8.1')->bukti_persen : 0,
            ],
            [
                'no' => 8, 'kode' => '8.2', 'nama' => 'Keterlibatan Mahasiswa dan Dosen dalam Tata Kelola', 'kriteria' => 'Tata Kelola', 'eu' => 3, 'bukti' => 5, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $tatakelolaN->has('8.2') ? $tatakelolaN->get('8.2')->status : 'Tidak Memenuhi',
                'pct_narasi' => $tatakelolaN->has('8.2') ? $tatakelolaN->get('8.2')->narasi_persen : 0,
                'pct_bukti' => $tatakelolaN->has('8.2') ? $tatakelolaN->get('8.2')->bukti_persen : 0,
            ],
            [
                'no' => 8, 'kode' => '8.3', 'nama' => 'Administrasi', 'kriteria' => 'Tata Kelola', 'eu' => 3, 'bukti' => 6, 'kategori' => 'BOLEH SEBAGIAN',
                'simulasi' => $tatakelolaN->has('8.3') ? $tatakelolaN->get('8.3')->status : 'Tidak Memenuhi',
                'pct_narasi' => $tatakelolaN->has('8.3') ? $tatakelolaN->get('8.3')->narasi_persen : 0,
                'pct_bukti' => $tatakelolaN->has('8.3') ? $tatakelolaN->get('8.3')->bukti_persen : 0,
            ],
        ];

        // Ensure proper object formatting for blade and cleanup null statuses
        $matrix = array_map(function($item) {
            if ($item['simulasi'] == 'Belum Diisi' || $item['simulasi'] == 'Draft' || empty($item['simulasi'])) {
                $item['simulasi'] = 'Tidak Memenuhi';
            }
            return (object) $item;
        }, $subKriteria);

        // Stats
        $totalSubKriteria = count($matrix);
        $totalEU = array_sum(array_column($subKriteria, 'eu'));
        $totalBukti = array_sum(array_column($subKriteria, 'bukti'));
        $wajibCount = count(array_filter($subKriteria, fn($i) => $i['kategori'] == 'WAJIB'));
        $bolehSebagianCount = count(array_filter($subKriteria, fn($i) => $i['kategori'] == 'BOLEH SEBAGIAN'));

        return view('pages.matriks.index', compact(
            'matrix',
            'totalSubKriteria',
            'totalEU',
            'totalBukti',
            'wajibCount',
            'bolehSebagianCount'
        ));
    }
}
