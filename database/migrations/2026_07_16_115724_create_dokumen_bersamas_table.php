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
        Schema::create('dokumen_bersamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('nama_dokumen');
            $table->text('deskripsi')->nullable();
            $table->string('jenis')->nullable();
            $table->enum('level', ['UNIV', 'FIKES'])->default('UNIV');
            $table->string('status')->default('Belum Ada');
            $table->string('link')->nullable();
            $table->string('pic')->nullable();
            $table->string('deadline')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_bersamas');
    }
};
