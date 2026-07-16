<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function narasis()
    {
        return $this->hasMany(MutuNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(MutuBukti::class);
    }
}

class MutuNarasi extends Model
{
    use HasFactory;
    
    protected $table = 'mutu_narasis';
    protected $guarded = [];

    public function mutu()
    {
        return $this->belongsTo(Mutu::class);
    }
}

class MutuBukti extends Model
{
    use HasFactory;

    protected $table = 'mutu_buktis';
    protected $guarded = [];

    public function mutu()
    {
        return $this->belongsTo(Mutu::class);
    }
}
