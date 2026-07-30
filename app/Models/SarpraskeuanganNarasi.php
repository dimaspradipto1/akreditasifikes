<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
