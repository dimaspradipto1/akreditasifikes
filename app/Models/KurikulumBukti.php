<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KurikulumBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'kurikulum_id',
        'kriteria_kode',
        'nama_bukti',
        'level',
        'status',
        'link',
        'pic',
        'deadline',
        'catatan',
    ];

    protected $attributes = [
        'kriteria_kode' => '',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
