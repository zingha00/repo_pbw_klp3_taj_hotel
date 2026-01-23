<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = [
        'room_id',
        'image_path',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Relasi ke Room (Many to One)
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        // RoomImage hanya punya image_path, langsung return URL-nya
        return asset('storage/' . $this->image_path);
    }

    /**
     * Boot method - Set sebagai primary jika gambar pertama
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            // Jika belum ada gambar untuk room ini, set sebagai primary
            $existingImages = self::where('room_id', $image->room_id)->count();
            
            if ($existingImages === 0) {
                $image->is_primary = true;
                $image->order = 0;
            } else {
                // Set order ke urutan terakhir
                $maxOrder = self::where('room_id', $image->room_id)->max('order');
                $image->order = $maxOrder + 1;
            }
        });

        // Jika gambar primary dihapus, set gambar pertama sebagai primary
        static::deleted(function ($image) {
            if ($image->is_primary) {
                $firstImage = self::where('room_id', $image->room_id)
                    ->orderBy('order')
                    ->first();
                
                if ($firstImage) {
                    $firstImage->is_primary = true;
                    $firstImage->save();
                }
            }
        });
    }
}