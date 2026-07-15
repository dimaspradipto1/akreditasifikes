<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Utama VMTS
        Schema::create('vmts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->year('tahun_akreditasi');
            $table->timestamps();
        });

        // 2. Tabel Narasi Bagian A (EU-1 s/d EU-6)
        Schema::create('vmts_narasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vmts_id')->constrained('vmts')->cascadeOnDelete();
            $table->string('elemen_kode'); // e.g., 'EU-1'
            $table->string('elemen_nama'); // e.g., 'Mekanisme perumusan VMTS & unggulan PS'
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('data_fakta')->nullable();
            $table->text('analisis')->nullable();
            $table->text('permasalahan')->nullable();
            $table->text('rencana_perbaikan')->nullable();
            $table->string('status')->default('Draft'); // Draft, Lengkap
            $table->integer('narasi_persen')->default(0);
            $table->integer('bukti_persen')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Bukti Pendukung Bagian B
        Schema::create('vmts_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vmts_id')->constrained('vmts')->cascadeOnDelete();
            $table->string('elemen_kode');
            $table->string('nama_bukti');
            $table->enum('level', ['PRODI', 'FIKES', 'UNIV']);
            $table->enum('status', ['Tersedia', 'Tidak Ada', 'Belum Memenuhi']);
            $table->string('link')->nullable();
            $table->string('pic')->nullable();
            $table->date('deadline')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vmts_buktis');
        Schema::dropIfExists('vmts_narasis');
        Schema::dropIfExists('vmts');
    }
};
