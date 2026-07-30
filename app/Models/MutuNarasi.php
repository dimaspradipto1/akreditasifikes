<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
