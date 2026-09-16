<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalReturnItem extends Model
{
    protected $fillable = [
        'rental_return_id',
        'rental_item_id',
        'condition',
        'damage_fee',
        'lost_fee',
        'notes',
    ];

    protected $casts = [
        'damage_fee' => 'decimal:2',
        'lost_fee' => 'decimal:2',
    ];

    public function rentalReturn(): BelongsTo
    {
        return $this->belongsTo(RentalReturn::class);
    }

    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(RentalItem::class);
    }
}