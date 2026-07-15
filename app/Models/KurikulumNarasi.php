<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurikulumNarasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kurikulum_id',
        'kriteria_kode',
        'kriteria_nama',
        'syarat',
        'kondisi_saat_ini',
        'data_fakta',
        'analisis',
        'permasalahan',
        'rencana_perbaikan',
        'status',
        'narasi_persen',
        'bukti_persen',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
