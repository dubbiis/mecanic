<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'dni_nif',
        'address',
        'notes',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function appointments(): HasManyThrough
    {
        return $this->hasManyThrough(Appointment::class, Vehicle::class);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('dni_nif', 'like', "%{$search}%");
        });
    }

    public function getVehicleCountAttribute(): int
    {
        return $this->vehicles()->count();
    }
}
