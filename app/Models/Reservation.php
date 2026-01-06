<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'guest_name',
        'phone',
        'check_in',
        'check_out',
        'total_price',
        'status'
    ];

    // Relasi: 1 Reservation milik 1 Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}