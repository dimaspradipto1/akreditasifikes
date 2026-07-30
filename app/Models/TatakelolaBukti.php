<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
