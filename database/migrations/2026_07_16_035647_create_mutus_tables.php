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
        Schema::create('mutus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tahun', 4);
            $table->timestamps();
        });

        Schema::create('mutu_narasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutu_id')->constrained('mutus')->cascadeOnDelete();
            $table->string('kriteria_kode', 10);
            $table->string('kriteria_nama')->nullable();
            $table->text('narasi_text')->nullable();
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('data_fakta')->nullable();
            $table->text('analisis')->nullable();
            $table->integer('narasi_persen')->default(0);
            $table->integer('bukti_persen')->default(0);
            $table->string('status', 20)->default('Belum Diisi'); // Memenuhi, Memenuhi Sebagian, Belum Memenuhi, Lengkap, dll
            $table->text('permasalahan')->nullable();
            $table->text('rencana_perbaikan')->nullable();
            $table->timestamps();
        });

        Schema::create('mutu_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutu_id')->constrained('mutus')->cascadeOnDelete();
            $table->string('kriteria_kode', 10);
            $table->string('nama_bukti');
            $table->string('level', 10)->nullable(); // PRODI, FIKES, UNIV
            $table->string('status', 20)->default('Tidak Ada'); // Tersedia, Tidak Ada, Belum Memenuhi
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
        Schema::dropIfExists('mutu_buktis');
        Schema::dropIfExists('mutu_narasis');
        Schema::dropIfExists('mutus');
    }
};
