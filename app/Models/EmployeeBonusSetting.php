<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBonusSetting extends Model
{
    protected $fillable = [
        'employee_id',
        'bonus_type',
        'bonus_value',
        'active',
    ];

    protected $casts = [
        'bonus_value' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Karyawan pemilik pengaturan bonus.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}