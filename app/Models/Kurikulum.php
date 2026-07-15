<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;

    protected $table = 'kurikulums';

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
        return $this->hasMany(KurikulumNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(KurikulumBukti::class);
    }
}

class KurikulumNarasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kurikulum_id',
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

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}

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

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
