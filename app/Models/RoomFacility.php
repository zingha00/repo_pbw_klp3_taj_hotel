<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    /**
     * Relasi ke Room (Many to Many)
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_facility');
    }

    /**
     * Get icon class untuk frontend
     */
    public function getIconClassAttribute()
    {
        // Jika menggunakan FontAwesome
        return 'fa fa-' . $this->icon;
    }

    /**
     * Scope untuk fasilitas aktif/populer
     */
    public function scopePopular($query)
    {
        return $query->withCount('rooms')
            ->orderBy('rooms_count', 'desc');
    }
}