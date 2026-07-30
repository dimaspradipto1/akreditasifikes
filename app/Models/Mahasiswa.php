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
