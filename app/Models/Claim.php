<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'item_id',
        'nama_pengambil',
        'NIMorKTP',
        'phone_pengambil',
        'foto_pengambil',
        'tgl_ambil'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
