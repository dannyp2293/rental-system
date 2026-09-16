<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Rental extends Model
{
    protected $fillable = [
        'rental_code',
        'customer_id',
        'employee_id',
        'courier_id',
        'rental_start',
        'rental_end',
        'returned_at',
        'subtotal',
        'discount',
        'deposit',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'rental_start' => 'datetime',
        'rental_end' => 'datetime',
        'returned_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }
    public function rentalReturn(): HasOne
{
    return $this->hasOne(RentalReturn::class);
}

public function payments(): HasMany
{
    return $this->hasMany(Payment::class);
}
}