<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianNarasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'penilaian_id',
        'kriteria_kode',
        'kriteria_nama',
        'kondisi_saat_ini',
        'data_fakta',
        'analisis',
        'permasalahan',
        'rencana_perbaikan',
        'status',
        'narasi_persen',
        'bukti_persen',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }
}
