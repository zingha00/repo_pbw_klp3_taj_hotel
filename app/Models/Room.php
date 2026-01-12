<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

     // Konstanta tipe kamar
    const TYPE_SINGLE = 'single';
    const TYPE_DOUBLE = 'double';
    const TYPE_SUITE = 'suite';
    const TYPE_COUPLE = 'couple';
    const TYPE_LUXURY = 'luxury';

    // Array semua tipe
    public static $types = [
        'single' => 'Single Room',
        'double' => 'Double Room',
        'suite' => 'Suite',
        'couple' => 'Couple Room',
        'luxury' => 'Luxury Room'
    ];


        protected $fillable = [
        'room_number',
        'name',
        'type',
        'price',
        'capacity',
        'description',
        'image',
        'length',
        'width',
        'facilities',
        'status',
        'available'
    ];

        protected $casts = [
        'facilities' => 'array',
        'available' => 'boolean'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}