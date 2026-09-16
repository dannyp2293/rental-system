<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'address',
        'whatsapp',
        'guarantee_type',
        'guarantee_number',
        'notes',
    ];


    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function getWhatsappUrlAttribute(): string
    {
        $number = preg_replace('/[^0-9]/', '', $this->whatsapp);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return 'https://wa.me/' . $number;
    }
}