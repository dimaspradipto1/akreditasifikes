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
        Schema::create('doenpkms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tahun_akreditasi', 4)->nullable();
            $table->timestamps();
        });

        Schema::create('doenpkm_narasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doenpkm_id')->constrained('doenpkms')->cascadeOnDelete();
            $table->string('elemen_kode', 10);
            $table->string('elemen_nama');
            $table->string('status', 20)->default('Draft');
            $table->integer('narasi_persen')->default(0);
            $table->integer('bukti_persen')->default(0);
            $table->timestamps();
        });

        Schema::create('doenpkm_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doenpkm_id')->constrained('doenpkms')->cascadeOnDelete();
            $table->string('elemen_kode', 10);
            $table->string('nama_bukti');
            $table->string('level', 10)->nullable();
            $table->string('status', 20)->default('Tidak Ada');
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
        Schema::dropIfExists('doenpkm_buktis');
        Schema::dropIfExists('doenpkm_narasis');
        Schema::dropIfExists('doenpkms');
    }
};
