<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmtsNarasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'vmts_id',
        'elemen_kode',
        'elemen_nama',
        'kondisi_saat_ini',
        'data_fakta',
        'analisis',
        'permasalahan',
        'rencana_perbaikan',
        'status',
    ];

    public function vmts()
    {
        return $this->belongsTo(Vmts::class);
    }
}
