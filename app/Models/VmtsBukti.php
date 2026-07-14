<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmtsBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'vmts_id',
        'nama_bukti',
        'level',
        'status',
        'link',
        'pic',
        'deadline',
        'catatan',
    ];

    public function vmts()
    {
        return $this->belongsTo(Vmts::class);
    }
}
