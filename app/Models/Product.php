<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
        'status',
        'discount'
    ];

    protected $attributes = [
        'status' => 'active',
        'discount' => 0
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/placeholder.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return Storage::disk('public')->exists($this->image)
            ? Storage::disk('public')->url($this->image)
            : asset('images/placeholder.jpg');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
