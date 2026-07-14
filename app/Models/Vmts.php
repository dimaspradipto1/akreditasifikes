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
