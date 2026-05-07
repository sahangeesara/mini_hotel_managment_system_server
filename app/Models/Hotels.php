<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hotels extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'hotel_code',
        'hotel_name',
        'location',
        'city',
        'city',
        'country',
        'image',
        'image_url',
        'price_per_night',
        'star_rating',
        'amenities',
        'description',
        'is_available',
    ];

    protected $appends = ['image_url'];


    protected function getImageUrlAttribute(): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return $this->attributes['image_url'] = url(Storage::url($this->image));
    }

    protected $casts = [
        'amenities' => 'array',
        'is_available' => 'boolean',
    ];


}
