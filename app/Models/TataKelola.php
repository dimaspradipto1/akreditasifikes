<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tatakelola extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function narasis()
    {
        return $this->hasMany(TatakelolaNarasi::class);
    }

    public function buktis()
    {
        return $this->hasMany(TatakelolaBukti::class);
    }
}

class TatakelolaNarasi extends Model
{
    use HasFactory;
    
    protected $table = 'tatakelola_narasis';
    protected $guarded = [];

    public function tatakelola()
    {
        return $this->belongsTo(Tatakelola::class);
    }
}

class TatakelolaBukti extends Model
{
    use HasFactory;

    protected $table = 'tatakelola_buktis';
    protected $guarded = [];

    public function tatakelola()
    {
        return $this->belongsTo(Tatakelola::class);
    }
}
