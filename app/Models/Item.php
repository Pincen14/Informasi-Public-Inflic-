<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_item',
        'description',
        'image',
        'location_found',
        'date_found',
        'time_found',
        'finder_name',
        'finder_contact', // Private - hanya untuk admin
        'admin_contact',  // Public - ditampilkan di dashboard
        'status',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claim()
    {
        return $this->hasOne(Claim::class);
    }
}
