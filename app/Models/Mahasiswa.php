<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
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
        return $this->hasMany(MahasiswaNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(MahasiswaBukti::class);
    }
}

class MahasiswaNarasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
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

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}

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
