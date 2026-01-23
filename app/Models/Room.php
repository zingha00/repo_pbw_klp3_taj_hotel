<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

// ✅ IMPORT MODEL RELASI
use App\Models\RoomImage;
use App\Models\RoomFacility;
use App\Models\Booking;
use App\Models\Review;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'capacity',
        'size',
        'image',
        'stock',
        'status',
        'rating',
        'reviews_count',
        'views',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'stock' => 'integer',
        'rating' => 'decimal:1',
        'reviews_count' => 'integer',
        'views' => 'integer',
    ];

    /* ================= BOOT ================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($room) {
            if (empty($room->slug)) {
                $room->slug = Str::slug($room->name);
            }

            $room->views ??= 0;
            $room->rating ??= 0;
            $room->reviews_count ??= 0;
        });

        static::updating(function ($room) {
            if ($room->isDirty('name')) {
                $room->slug = Str::slug($room->name);
            }
        });
    }

    /* ================= RELATIONS ================= */

    /**
     * ✅ RELASI UTAMA (dipakai controller)
     */
    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderBy('is_primary', 'desc');
    }



    /**
     * Alias aman (kalau sudah terlanjur dipakai)
     */
    public function roomImages()
    {
        return $this->hasMany(RoomImage::class)->orderBy('order');
    }


    public function facilities()
    {
        return $this->belongsToMany(
            RoomFacility::class,
            'room_facility',
            'room_id',
            'room_facility_id'
        );
    }

    public function roomFacilities()
    {
        return $this->facilities();
    }



    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /* ================= ACCESSORS ================= */

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }

        return asset('assets/images/default-room.jpg');
    }

    public function getAllImagesUrlAttribute()
    {
        if ($this->images->isEmpty()) {
            return [asset('assets/images/default-room.jpg')];
        }

        return $this->images->map(
            fn($img) => asset('storage/' . $img->image_path)
        )->toArray();
    }

    public function getPrimaryImageAttribute()
    {
        $primary = $this->images()->where('is_primary', true)->first();

        return $primary
            ? asset('storage/' . $primary->image_path)
            : $this->image_url;
    }

    public function getFacilityNamesAttribute()
    {
        return $this->facilities->pluck('name')->toArray();
    }

    public function getFacilityIconsAttribute()
    {
        return $this->facilities
            ->mapWithKeys(fn($f) => [$f->name => $f->icon])
            ->toArray();
    }

    /* ================= RATING ================= */

    public function getAverageRatingAttribute(): float
    {
        if ($this->reviews_count > 0) {
            return round((float) $this->rating, 1);
        }

        return round((float) ($this->reviews()->avg('rating') ?? 0), 1);
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->reviews_count = $this->reviews()->count();
        $this->save();
    }

    /* ================= AVAILABILITY ================= */

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock > 0;
    }

    /* ================= SCOPES ================= */

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('stock', '>', 0);
    }

    public function scopeHighRated($query, $min = 4.0)
    {
        return $query->where('rating', '>=', $min)->orderByDesc('rating');
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('views');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(
            fn($q) =>
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
        );
    }
}
