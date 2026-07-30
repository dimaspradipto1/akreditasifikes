<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'penilaian_id',
        'kriteria_kode',
        'nama_bukti',
        'level',
        'status',
        'link',
        'pic',
        'deadline',
        'catatan',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }
}
