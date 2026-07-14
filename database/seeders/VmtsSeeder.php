<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vmts;
use App\Models\VmtsBukti;
use App\Models\VmtsNarasi;
use Illuminate\Database\Seeder;

class VmtsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user yang merupakan koordinator prodi (atau dekan, dsb.)
        // Untuk contoh ini, kita ambil user pertama sebagai pengisi
        $user = User::where('role', '=', 'koordinatorprodi')->first();
        if (!$user) {
            $user = User::first();
        }

        if (!$user) {
            return;
        }

        // Buat data Vmts
        $vmts = Vmts::firstOrCreate([
            'user_id' => $user->id,
            'tahun_akreditasi' => date('Y'),
        ]);

        // Buat data Narasi (Bagian A)
        $elements = [
            'EU-1' => 'Mekanisme perumusan VMTS & unggulan PS',
            'EU-2' => 'Sosialisasi VMTS ke sivitas akademika',
            'EU-3' => 'Strategi & Indikator kinerja (KPI) pencapaian',
            'EU-4' => 'Keterkaitan VMTS — Kurikulum — Penjaminan Mutu',
            'EU-5' => 'Siklus peninjauan berkala VMTS',
            'EU-6' => 'Tata nilai program studi',
        ];

        foreach ($elements as $kode => $nama) {
            VmtsNarasi::updateOrCreate([
                'vmts_id' => $vmts->id,
                'elemen_kode' => $kode,
            ], [
                'elemen_nama' => $nama,
                'kondisi_saat_ini' => $kode == 'EU-1' ? 'Kondisi saat ini pada aspek ini sudah berjalan sesuai standar minimum, dengan dokumentasi yang tersedia di tingkat program studi.' : null,
                'data_fakta' => $kode == 'EU-1' ? 'Data pendukung terkumpul dari dokumen internal prodi dan Dokumen Bersama FIKes/Universitas yang relevan.' : null,
                'analisis' => $kode == 'EU-1' ? 'Dibandingkan dengan standar LAM-PTKes, capaian saat ini berada pada kategori memenuhi dengan beberapa catatan minor.' : null,
                'permasalahan' => $kode == 'EU-1' ? 'Masih ditemukan keterlambatan pembaruan dokumen pada beberapa siklus terakhir.' : null,
                'rencana_perbaikan' => $kode == 'EU-1' ? 'Disusun rencana pembaruan berkala setiap semester beserta penanggung jawab pelaksana.' : null,
                'status' => $kode == 'EU-1' ? 'Lengkap' : 'Draft',
            ]);
        }

        // Buat data Bukti Pendukung (Bagian B)
        $buktis = [
            ['SK Penetapan VMTS', 'PRODI', 'Tersedia', 'https://example.com/sk-vmts', 'Kaprodi', '2026-12-31', 'Sudah dilegalisir'],
            ['Notulen Penyusunan VMTS', 'PRODI', 'Tersedia', 'https://example.com/notulen', 'Sekretaris', '2026-12-31', ''],
            ['Matriks Strategi Pencapaian VMTS', 'PRODI', 'Tersedia', 'https://example.com/matriks-strategi', 'Tim Akreditasi', '2026-12-31', ''],
            ['Matriks Keterkaitan VMTS — CPL', 'PRODI', 'Tersedia', 'https://example.com/matriks-cpl', 'Tim Kurikulum', '2026-12-31', ''],
            ['SK Revisi VMTS', 'PRODI', 'Tersedia', 'https://example.com/sk-revisi', 'Kaprodi', '2026-12-31', ''],
            ['Dokumen Tata Nilai Program Studi', 'PRODI', 'Tersedia', 'https://example.com/tata-nilai', 'Tim Akreditasi', '2026-12-31', ''],
            ['Renstra FIKes', 'FIKES', 'Tersedia', 'https://example.com/renstra-fikes', 'Tim Dekanat/GPM FIKes', '2026-12-31', ''],
            ['Renstra Universitas', 'UNIV', 'Tersedia', 'https://example.com/renstra-univ', 'Tim Rektorat', '2026-12-31', ''],
            ['Kebijakan PPKS', 'UNIV', 'Tersedia', 'https://example.com/kebijakan-ppks', 'Tim Rektorat', '2026-12-31', ''],
        ];

        foreach ($buktis as $bukti) {
            VmtsBukti::updateOrCreate([
                'vmts_id' => $vmts->id,
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
