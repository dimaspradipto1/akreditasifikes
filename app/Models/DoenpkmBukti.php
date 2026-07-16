<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoenpkmBukti extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function doenpkm()
    {
        return $this->belongsTo(Doenpkm::class, 'doenpkm_id');
    }
}
