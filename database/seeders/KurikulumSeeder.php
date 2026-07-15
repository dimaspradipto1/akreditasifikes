<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kurikulum;
use App\Models\KurikulumBukti;
use App\Models\KurikulumNarasi;
use Illuminate\Database\Seeder;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user yang merupakan koordinator prodi
        $user = User::where('role', '=', 'koordinatorprodi')->first();
        if (!$user) {
            $user = User::first();
        }

        if (!$user) {
            return;
        }

        // Buat data Kurikulum
        $kurikulum = Kurikulum::firstOrCreate([
            'user_id' => $user->id,
            'tahun_akreditasi' => date('Y'),
        ]);

        // Buat data Narasi (Bagian A)
        $elements = [
            '2.1_EU-1' => 'Penyusunan CPL 4 komponen (sikap, pengetahuan, keterampilan umum & khusus)',
            '2.1_EU-2' => 'Kesesuaian CPL dengan SN-DIKTI & KKNI',
            '2.1_EU-3' => 'Kesesuaian CPL dengan VMT Universitas/Fakultas',
            '2.1_EU-4' => 'Profil lulusan yang jelas dan realistis',
            
            '2.2_EU-1' => 'Prinsip desain kurikulum',
            '2.2_EU-2' => 'Keseimbangan komponen teori, praktik, dan lapangan',
            '2.2_EU-3' => 'Integrasi penelitian & PkM dosen ke dalam pembelajaran',

            '2.3_EU-1' => 'Cakupan kompetensi inti',
            '2.3_EU-2' => 'Kemutakhiran isi kurikulum',
            '2.3_EU-3' => 'Penguasaan regulasi/standar bidang kesehatan',
            '2.3_EU-4' => 'Integrasi konteks sektor lokal',

            '2.4_EU-1' => 'Variasi metode pembelajaran',
            '2.4_EU-2' => 'Pengelolaan PKL/magang terstruktur',
            '2.4_EU-3' => 'Suasana akademik (seminar/kuliah tamu ≥ 2x/semester)',
        ];

        foreach ($elements as $kode => $nama) {
            KurikulumNarasi::updateOrCreate([
                'kurikulum_id' => $kurikulum->id,
                'kriteria_kode' => $kode,
            ], [
                'kriteria_nama' => $nama,
                'kondisi_saat_ini' => 'Kondisi saat ini berjalan dengan baik.',
                'data_fakta' => 'Data tersedia di tingkat program studi.',
                'analisis' => 'Capaian sudah memenuhi standar.',
                'permasalahan' => 'Tidak ada permasalahan mayor.',
                'rencana_perbaikan' => 'Monitoring berkala.',
                'status' => 'Memenuhi',
            ]);
        }
        
        $subKriteria = [
            '2.5', '2.6', '2.7', '2.8'
        ];
        
        foreach ($subKriteria as $kode) {
            KurikulumNarasi::updateOrCreate([
                'kurikulum_id' => $kurikulum->id,
                'kriteria_kode' => $kode,
            ], [
                'kriteria_nama' => 'Sub-kriteria ' . $kode,
                'kondisi_saat_ini' => 'Kondisi saat ini berjalan dengan baik.',
                'data_fakta' => 'Data tersedia.',
                'analisis' => 'Capaian sudah memenuhi.',
                'permasalahan' => 'Tidak ada.',
                'rencana_perbaikan' => 'Evaluasi rutin.',
                'status' => 'Memenuhi',
            ]);
        }

        // Buat data Bukti Pendukung (Bagian B)
        $buktis = [
            ['RPS dengan Variasi Metode Pembelajaran', 'PRODI', 'Tersedia', 'https://example.com/rps', 'Kaprodi', '2026-12-31', ''],
            ['Panduan PKL/Magang', 'PRODI', 'Tersedia', 'https://example.com/panduan-pkl', 'Koordinator PKL', '2026-12-31', ''],
            ['Laporan Evaluasi PKL', 'PRODI', 'Tersedia', 'https://example.com/evaluasi-pkl', 'Koordinator PKL', '2026-12-31', ''],
            ['Laporan Seminar/Kuliah Tamu', 'PRODI', 'Tersedia', 'https://example.com/laporan-seminar', 'Tim Prodi', '2026-12-31', ''],
        ];

        foreach ($buktis as $bukti) {
            KurikulumBukti::updateOrCreate([
                'kurikulum_id' => $kurikulum->id,
                'nama_bukti' => $bukti[0],
            ], [
                'level' => $bukti[1],
                'status' => $bukti[2],
                'link' => $bukti[3],
                'pic' => $bukti[4],
                'deadline' => $bukti[5],
                'catatan' => $bukti[6],
            ]);
        }
    }
}
