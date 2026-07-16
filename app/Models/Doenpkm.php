<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doenpkm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function narasis()
    {
        return $this->hasMany(DoenpkmNarasi::class, 'doenpkm_id');
    }

    public function buktis()
    {
        return $this->hasMany(DoenpkmBukti::class, 'doenpkm_id');
    }
}
