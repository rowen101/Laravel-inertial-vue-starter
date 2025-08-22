<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'license_number',
        'phone',
        'email',
        'hire_date',
        'license_expiry',
        'status',
        'address',
        'birth_date',
        'notes'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'license_expiry' => 'date',
        'birth_date' => 'date'
    ];

    public function loads(): HasMany
    {
        return $this->hasMany(Load::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
