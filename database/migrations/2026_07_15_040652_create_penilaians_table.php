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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('tahun_akreditasi');
            $table->timestamps();
        });

        Schema::create('penilaian_narasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained()->onDelete('cascade');
            $table->string('kriteria_kode');
            $table->string('kriteria_nama');
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('data_fakta')->nullable();
            $table->text('analisis')->nullable();
            $table->text('permasalahan')->nullable();
            $table->text('rencana_perbaikan')->nullable();
            $table->enum('status', ['Memenuhi', 'Memenuhi Sebagian', 'Belum Memenuhi', 'Lengkap', 'Draft', 'Belum Diisi'])->default('Belum Diisi');
            $table->integer('narasi_persen')->default(0);
            $table->integer('bukti_persen')->default(0);
            $table->timestamps();
        });

        Schema::create('penilaian_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained()->onDelete('cascade');
            $table->string('nama_bukti');
            $table->enum('level', ['PRODI', 'FIKES', 'UNIV']);
            $table->enum('status', ['Tersedia', 'Tidak Ada', 'Belum Memenuhi'])->default('Tidak Ada');
            $table->string('link')->nullable();
            $table->string('pic')->nullable();
            $table->date('deadline')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_buktis');
        Schema::dropIfExists('penilaian_narasis');
        Schema::dropIfExists('penilaians');
    }
};
