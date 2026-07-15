<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vmts extends Model
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
        return $this->hasMany(VmtsNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(VmtsBukti::class);
    }
}

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
        'narasi_persen',
        'bukti_persen',
    ];

    public function vmts()
    {
        return $this->belongsTo(Vmts::class);
    }
}

class VmtsBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'vmts_id',
        'elemen_kode',
        'nama_bukti',
        'level',
        'status',
        'link',
        'pic',
        'deadline',
        'catatan',
    ];

    public function vmts()
    {
        return $this->belongsTo(Vmts::class);
    }
}
