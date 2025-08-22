<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Load extends Model
{
    protected $fillable = [
        'load_number',
        'customer_name',
        'pickup_location',
        'delivery_location',
        'pickup_date',
        'delivery_date',
        'weight',
        'rate',
        'load_type',
        'status',
        'driver_id',
        'vehicle_id',
        'notes'
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'delivery_date' => 'date',
        'weight' => 'decimal:2',
        'rate' => 'decimal:2'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
