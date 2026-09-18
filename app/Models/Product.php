<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RentalItem;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'price_per_day',
        'price_per_hour',
        'stock',
        'status',
        'image',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'price_per_hour' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rentalItems(): HasMany
{
    return $this->hasMany(
        RentalItem::class
    );
}


}