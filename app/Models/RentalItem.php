<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentalItem extends Model
{
    protected $fillable = [
        'rental_id',
        'product_id',
        'quantity',
        'pricing_type',
        'unit_price',
        'duration',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'duration' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function rentalReturnItems(): HasMany
{
    return $this->hasMany(RentalReturnItem::class);
}
}