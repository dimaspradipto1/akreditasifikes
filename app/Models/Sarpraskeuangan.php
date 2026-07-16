<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarpraskeuangan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function narasis()
    {
        return $this->hasMany(SarpraskeuanganNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(SarpraskeuanganBukti::class);
    }
}

class SarpraskeuanganNarasi extends Model
{
    use HasFactory;
    
    protected $table = 'sarpraskeuangan_narasis';
    protected $guarded = [];

    public function sarpraskeuangan()
    {
        return $this->belongsTo(Sarpraskeuangan::class);
    }
}

class SarpraskeuanganBukti extends Model
{
    use HasFactory;

    protected $table = 'sarpraskeuangan_buktis';
    protected $guarded = [];

    public function sarpraskeuangan()
    {
        return $this->belongsTo(Sarpraskeuangan::class);
    }
}
