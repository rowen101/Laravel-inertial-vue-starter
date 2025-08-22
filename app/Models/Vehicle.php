<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_number',
        'make',
        'model',
        'year',
        'vin',
        'status',
        'mileage',
        'registration_expiry',
        'insurance_expiry',
        'notes'
    ];

    protected $casts = [
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'mileage' => 'decimal:2'
    ];

    public function loads(): HasMany
    {
        return $this->hasMany(Load::class);
    }
}
