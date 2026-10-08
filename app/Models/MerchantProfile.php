<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'description',
        'phone',
        'address',
        'city',
        'logo',
        'banner',
        'cuisine_type',
        'min_order_amount',
        'rating_avg',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'merchant_profile_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'merchant_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'merchant_id');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('storage/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500&auto=format&fit=crop&q=60';
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner && file_exists(public_path('storage/' . $this->banner))) {
            return asset('storage/' . $this->banner);
        }
        return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&auto=format&fit=crop&q=80';
    }
}
