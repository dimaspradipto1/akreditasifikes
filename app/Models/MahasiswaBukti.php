<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'kriteria_kode',
        'nama_bukti',
        'level',
        'status',
        'link',
        'pic',
        'deadline',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
