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
        // 1. Tabel Utama Kurikulum
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->year('tahun_akreditasi');
            $table->timestamps();
        });

        // 2. Tabel Narasi Sub-kriteria Kurikulum
        Schema::create('kurikulum_narasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->cascadeOnDelete();
            $table->string('kriteria_kode'); // e.g., '2.1'
            $table->string('kriteria_nama'); // e.g., 'Capaian Pembelajaran dalam Kurikulum'
            $table->string('syarat')->default('WAJIB'); // WAJIB, BOLEH SEBAGIAN
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('data_fakta')->nullable();
            $table->text('analisis')->nullable();
            $table->text('permasalahan')->nullable();
            $table->text('rencana_perbaikan')->nullable();
            $table->string('status')->default('Belum Memenuhi'); // Memenuhi, Memenuhi Sebagian, Belum Memenuhi
            $table->integer('narasi_persen')->default(0);
            $table->integer('bukti_persen')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Bukti Pendukung Kurikulum
        Schema::create('kurikulum_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->cascadeOnDelete();
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
        Schema::dropIfExists('kurikulum_buktis');
        Schema::dropIfExists('kurikulum_narasis');
        Schema::dropIfExists('kurikulums');
    }
};
