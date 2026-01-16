<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['category_id', 'name', 'price', 'link', 'is_completed'];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected function price(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => 'Rp ' . number_format((float) $value, 0, ',', '.'),
            set: fn($value) => (float) str_replace(['Rp', '.', ' '], '', $value),
        );
    }
}
