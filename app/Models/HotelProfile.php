<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelProfile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'hotel_profiles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hotel_name',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'description',
        'hero_title',
        'hero_subtitle',
        'logo',
        'favicon',
        'facebook',
        'instagram',
        'twitter',
        'check_in_time',
        'check_out_time',
        'primary_color',
        'secondary_color',
        'background_color',
    ];

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-logo.png');
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon) {
            return asset('storage/' . $this->favicon);
        }
        return asset('images/default-favicon.png');
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->country}";
    }

    /**
     * Check if social media links exist
     */
    public function hasSocialMedia()
    {
        return !empty($this->facebook) || !empty($this->instagram) || !empty($this->twitter);
    }
}