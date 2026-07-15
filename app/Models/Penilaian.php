<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tahun_akreditasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function narasis()
    {
        return $this->hasMany(PenilaianNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(PenilaianBukti::class);
    }
}

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

class PenilaianBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'penilaian_id',
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
