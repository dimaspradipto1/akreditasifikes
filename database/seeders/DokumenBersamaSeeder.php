<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DokumenBersama;

class DokumenBersamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokumenUniv = [
            [
                'kode' => 'SK_IZIN_UNIV',
                'nama_dokumen' => 'SK izin institusi Universitas XYZ yang masih berlaku',
                'deskripsi' => 'SK Izin institusi Universitas XYZ yang masih berlaku; dasar legalitas semua prodi di Universitas XYZ',
                'jenis' => 'SK Izin',
            ],
            [
                'kode' => 'STATUTA',
                'nama_dokumen' => 'Statuta Universitas XYZ',
                'deskripsi' => 'Statuta Universitas XYZ yang mengatur tata kelola, struktur, dan fungsi universitas',
                'jenis' => 'Statuta',
            ],
            [
                'kode' => 'SK_REKTOR',
                'nama_dokumen' => 'SK Pengangkatan Rektor Universitas XYZ (aktif)',
                'deskripsi' => 'SK aktif Rektor Universitas XYZ; legitimasi kepemimpinan untuk kebijakan universitas',
                'jenis' => 'SK',
            ],
            [
                'kode' => 'RENSTRA_UNIV',
                'nama_dokumen' => 'Rencana Strategis (Renstra) Universitas XYZ',
                'deskripsi' => 'Renstra Universitas XYZ; memuat visi, misi, tujuan, dan program strategis universitas',
                'jenis' => 'Renstra',
            ],
            [
                'kode' => 'SPMI_KEBIJAKAN',
                'nama_dokumen' => 'Dokumen Kebijakan SPMI Universitas XYZ (SK Rektor)',
                'deskripsi' => 'SK Kebijakan Mutu dan Dokumen Kebijakan SPMI yang ditetapkan Rektor Universitas XYZ',
                'jenis' => 'Kebijakan Mutu',
            ],
            [
                'kode' => 'SPMI_STANDAR',
                'nama_dokumen' => 'Standar Mutu Universitas XYZ (SN-Dikti + standar PT)',
                'deskripsi' => 'Dokumen standar mutu Universitas XYZ mengacu SN-Dikti; berlaku untuk semua prodi',
                'jenis' => 'Standar Mutu',
            ],
            [
                'kode' => 'SPMI_MANUAL',
                'nama_dokumen' => 'Manual Mutu dan Formulir SPMI Universitas XYZ',
                'deskripsi' => 'Manual prosedur SPMI beserta formulir; mekanisme PPEPP di seluruh unit Universitas XYZ',
                'jenis' => 'Manual Mutu',
            ],
            [
                'kode' => 'RIP_UNIV',
                'nama_dokumen' => 'Rencana Induk Penelitian (RIP) Universitas XYZ',
                'deskripsi' => 'Peta jalan penelitian tingkat institusi yang menjadi rujukan seluruh Fakultas',
                'jenis' => 'Pedoman',
            ],
            [
                'kode' => 'RENSTRA_PKM_UNIV',
                'nama_dokumen' => 'Rencana Strategis Pengabdian Masyarakat Universitas XYZ',
                'deskripsi' => 'Peta jalan pengabdian masyarakat tingkat institusi universitas',
                'jenis' => 'Renstra PkM',
            ],
            [
                'kode' => 'PEDOMAN_AKADEMIK_UNIV',
                'nama_dokumen' => 'Buku Pedoman Akademik Universitas XYZ',
                'deskripsi' => 'Buku pedoman penyelenggaraan pendidikan tingkat universitas',
                'jenis' => 'Buku Pedoman',
            ],
            [
                'kode' => 'SOP_KEUANGAN_UNIV',
                'nama_dokumen' => 'SOP Pengelolaan Keuangan Universitas',
                'deskripsi' => 'Standar operasional pengelolaan dan pencairan dana di universitas',
                'jenis' => 'SOP',
            ],
            [
                'kode' => 'SK_SDM_UNIV',
                'nama_dokumen' => 'Pedoman Pengelolaan SDM Universitas',
                'deskripsi' => 'Kebijakan rekrutmen, penempatan, pengembangan, dan evaluasi dosen serta tendik',
                'jenis' => 'Pedoman SDM',
            ],
            [
                'kode' => 'MOU_KEMENDIKBUD',
                'nama_dokumen' => 'SK/MoU Kerjasama Universitas dengan Institusi Pemerintah',
                'deskripsi' => 'Bukti kerjasama tingkat universitas dengan lembaga pemerintah',
                'jenis' => 'MoU',
            ],
            [
                'kode' => 'LAPORAN_AMI_UNIV',
                'nama_dokumen' => 'Laporan Pelaksanaan AMI Universitas (2 Tahun Terakhir)',
                'deskripsi' => 'Laporan komprehensif audit mutu internal dari LPM Universitas',
                'jenis' => 'Laporan',
            ],
            [
                'kode' => 'LAPORAN_RTM_UNIV',
                'nama_dokumen' => 'Notulen Rapat Tinjauan Manajemen (RTM) Universitas',
                'deskripsi' => 'Tindak lanjut dan hasil RTM tingkat pimpinan universitas',
                'jenis' => 'Laporan',
            ],
        ];

        $dokumenFikes = [
            [
                'kode' => 'OTK_FIKES',
                'nama_dokumen' => 'Organisasi dan Tata Kerja (OTK) FIKes Universitas XYZ',
                'deskripsi' => 'OTK FIKes; struktur, tupoksi Dekan, Wadek, Kaprodi semua prodi FIKes',
                'jenis' => 'Dokumen OTK',
            ],
            [
                'kode' => 'SK_DEKAN',
                'nama_dokumen' => 'SK Pengangkatan Dekan FIKes Universitas XYZ (aktif)',
                'deskripsi' => 'SK aktif Dekan FIKes dari Rektor Universitas XYZ; legitimasi kepemimpinan FIKes',
                'jenis' => 'SK',
            ],
            [
                'kode' => 'RENSTRA_FIKES',
                'nama_dokumen' => 'Rencana Strategis FIKes Universitas XYZ (4-5 tahun)',
                'deskripsi' => 'Renstra FIKes selaras Renstra Universitas XYZ; program strategis semua prodi FIKes',
                'jenis' => 'Renstra',
            ],
            [
                'kode' => 'KEU_FIKES',
                'nama_dokumen' => 'Laporan Keuangan FIKes (2 tahun terakhir)',
                'deskripsi' => 'Laporan anggaran dan realisasi FIKes; kecukupan pembiayaan per prodi',
                'jenis' => 'Laporan Keuangan',
            ],
            [
                'kode' => 'SK_GPM_FK',
                'nama_dokumen' => 'SK UPMI/GKM FIKes',
                'deskripsi' => 'SK penetapan GPM FIKes dari Dekan; nama anggota, tupoksi, program kerja',
                'jenis' => 'SK',
            ],
            [
                'kode' => 'PROGKER_GPM',
                'nama_dokumen' => 'Program Kerja GPM FIKes Terbaru',
                'deskripsi' => 'Rencana kerja GPM FIKes: audit, evaluasi standar, pelaporan; mencakup semua prodi',
                'jenis' => 'Program Kerja',
            ],
            [
                'kode' => 'AMI_FK',
                'nama_dokumen' => 'Laporan AMI FIKes (2 tahun)',
                'deskripsi' => 'Laporan AMI FIKes: temuan, rekomendasi, CAPA; mencakup semua prodi di FIKes',
                'jenis' => 'Laporan AMI',
            ],
            [
                'kode' => 'RTM_FK',
                'nama_dokumen' => 'Laporan RTM FIKes',
                'deskripsi' => 'Laporan Rapat Tinjauan Manajemen (RTM) Fakultas Ilmu Kesehatan',
                'jenis' => 'Laporan RTM',
            ],
            [
                'kode' => 'PEDOMAN_AKADEMIK_FK',
                'nama_dokumen' => 'Buku Pedoman Akademik FIKes',
                'deskripsi' => 'Pedoman akademik spesifik fakultas yang memandu aktivitas pembelajaran',
                'jenis' => 'Pedoman Akademik',
            ],
            [
                'kode' => 'SK_KALENDER_FK',
                'nama_dokumen' => 'Kalender Akademik FIKes',
                'deskripsi' => 'SK Dekan tentang Kalender Akademik FIKes',
                'jenis' => 'SK',
            ],
            [
                'kode' => 'SOP_PENELITIAN_FK',
                'nama_dokumen' => 'SOP Pelaksanaan Penelitian dan Pengabdian Masyarakat FIKes',
                'deskripsi' => 'SOP tingkat fakultas untuk memfasilitasi riset dosen dan mahasiswa',
                'jenis' => 'SOP',
            ],
            [
                'kode' => 'BUKTI_KERJASAMA_FK',
                'nama_dokumen' => 'Daftar Perjanjian Kerjasama (MoA) Tingkat FIKes',
                'deskripsi' => 'Kumpulan dokumen kerjasama dengan Rumah Sakit, Dinkes, dan Puskesmas',
                'jenis' => 'Dokumen Kerjasama',
            ],
            [
                'kode' => 'LAPORAN_IKU_FK',
                'nama_dokumen' => 'Laporan Pencapaian IKU FIKes',
                'deskripsi' => 'Laporan tahunan kinerja dekanat dan pencapaian Indikator Kinerja Utama',
                'jenis' => 'Laporan Kinerja',
            ],
            [
                'kode' => 'SK_DOSEN_WALI',
                'nama_dokumen' => 'SK Penunjukan Dosen Pembimbing Akademik / Wali',
                'deskripsi' => 'SK Dekan untuk plotting DPA mahasiswa tiap prodi di lingkungan FIKes',
                'jenis' => 'SK',
            ],
            [
                'kode' => 'PEDOMAN_SKRIPSI_FK',
                'nama_dokumen' => 'Buku Pedoman Penulisan Skripsi / Tugas Akhir FIKes',
                'deskripsi' => 'Panduan tugas akhir yang diseragamkan untuk semua prodi di FIKes',
                'jenis' => 'Buku Pedoman',
            ],
        ];

        foreach ($dokumenUniv as $doc) {
            DokumenBersama::firstOrCreate(
                ['kode' => $doc['kode']],
                [
                    'nama_dokumen' => $doc['nama_dokumen'],
                    'deskripsi' => $doc['deskripsi'],
                    'jenis' => $doc['jenis'],
                    'level' => 'UNIV',
                    'status' => 'Belum Ada',
                ]
            );
        }

        foreach ($dokumenFikes as $doc) {
            DokumenBersama::firstOrCreate(
                ['kode' => $doc['kode']],
                [
                    'nama_dokumen' => $doc['nama_dokumen'],
                    'deskripsi' => $doc['deskripsi'],
                    'jenis' => $doc['jenis'],
                    'level' => 'FIKES',
                    'status' => 'Belum Ada',
                ]
            );
        }
    }
}
