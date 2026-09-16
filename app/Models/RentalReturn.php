<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentalReturn extends Model
{
    protected $fillable = [
        'rental_id',
        'returned_at',
        'late_hours',
        'late_fee',
        'damage_fee',
        'lost_fee',
        'deposit_deduction',
        'deposit_return',
        'notes',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'late_hours' => 'integer',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'lost_fee' => 'decimal:2',
        'deposit_deduction' => 'decimal:2',
        'deposit_return' => 'decimal:2',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalReturnItem::class);
    }
}