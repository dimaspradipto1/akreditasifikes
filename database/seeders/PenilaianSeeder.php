<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penilaian;
use App\Models\PenilaianBukti;
use App\Models\PenilaianNarasi;
use Illuminate\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil admin (Atau user pertama)
        $user = User::first();
        if (!$user) {
            return;
        }

        // 2. Buat atau update instance Penilaian
        $penilaian = Penilaian::firstOrCreate(
            ['user_id' => $user->id, 'tahun_akreditasi' => date('Y')]
        );

        // 3. Daftar Sub-Kriteria 3.1 (Dengan 3 EU)
        $sub31EUs = [
            '3.1_EU-1' => 'Metode penilaian per CPL',
            '3.1_EU-2' => 'Penetapan komponen & jadwal penilaian',
            '3.1_EU-3' => 'Koordinasi/peer-review penilaian lintas mata kuliah',
        ];

        // Induk 3.1
        PenilaianNarasi::updateOrCreate(
            ['penilaian_id' => $penilaian->id, 'kriteria_kode' => '3.1'],
            [
                'kriteria_nama' => 'Kebijakan dan Sistem Penilaian',
                'status' => 'Belum Memenuhi', // auto-calc later
            ]
        );

        // EU dari 3.1
        foreach ($sub31EUs as $kode => $nama) {
            PenilaianNarasi::updateOrCreate(
                ['penilaian_id' => $penilaian->id, 'kriteria_kode' => $kode],
                [
                    'kriteria_nama' => $nama,
                    'status' => 'Belum Diisi',
                ]
            );
        }

        // 4. Daftar Sub-Kriteria Non-EU
        $nonEUs = [
            '3.2' => 'Penilaian dalam Mendukung Pembelajaran',
            '3.3' => 'Penilaian untuk Mendukung Pengambilan Keputusan',
            '3.4' => 'Pengendalian/Penjaminan Mutu Penilaian',
        ];

        foreach ($nonEUs as $kode => $nama) {
            PenilaianNarasi::updateOrCreate(
                ['penilaian_id' => $penilaian->id, 'kriteria_kode' => $kode],
                [
                    'kriteria_nama' => $nama,
                    'status' => 'Belum Memenuhi',
                ]
            );
        }

        // 5. Seed Bukti Dummy untuk Bagian B
        $dummyBuktis = [
            [
                'nama_bukti' => 'Matriks CPL — Metode & Rubrik Penilaian',
                'level'      => 'PRODI',
                'status'     => 'Tersedia',
            ],
            [
                'nama_bukti' => 'RPS dengan Komponen Penilaian',
                'level'      => 'PRODI',
                'status'     => 'Tersedia',
            ],
            [
                'nama_bukti' => 'Notulen Koordinasi Penilaian',
                'level'      => 'PRODI',
                'status'     => 'Tersedia',
            ],
            [
                'nama_bukti' => 'Berita Acara Review Soal',
                'level'      => 'PRODI',
                'status'     => 'Tersedia',
            ]
        ];

        foreach ($dummyBuktis as $bukti) {
            PenilaianBukti::updateOrCreate(
                [
                    'penilaian_id' => $penilaian->id,
                    'nama_bukti'   => $bukti['nama_bukti'],
                ],
                [
                    'level'  => $bukti['level'],
                    'status' => $bukti['status'],
                ]
            );
        }
    }
}
