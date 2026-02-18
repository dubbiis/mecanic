<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'car',
        'plate',
        'vin',
        'itv_date',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'itv_date' => 'date',
        ];
    }

    /**
     * Calculate days remaining until ITV expiration.
     */
    public function daysUntilExpiration(): int
    {
        return now()->startOfDay()->diffInDays($this->itv_date, false);
    }

    /**
     * Check if ITV is expired.
     */
    public function isExpired(): bool
    {
        return $this->daysUntilExpiration() < 0;
    }

    /**
     * Check if ITV is urgent (expires within 7 days or already expired).
     */
    public function isUrgent(): bool
    {
        $days = $this->daysUntilExpiration();
        return $days < 0 || $days <= 7;
    }

    /**
     * Check if ITV expires within 30 days.
     */
    public function isWarning(): bool
    {
        $days = $this->daysUntilExpiration();
        return $days >= 0 && $days <= 30;
    }

    /**
     * Check if ITV is valid (more than 30 days).
     */
    public function isValid(): bool
    {
        return $this->daysUntilExpiration() > 30;
    }

    /**
     * Get ITV status: 'expired', 'urgent', 'warning', or 'valid'.
     */
    public function getStatus(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isUrgent()) {
            return 'urgent';
        }

        if ($this->isWarning()) {
            return 'warning';
        }

        return 'valid';
    }

    /**
     * Get formatted ITV date (DD/MM/YYYY).
     */
    public function getFormattedItvDate(): string
    {
        return $this->itv_date->format('d/m/Y');
    }

    /**
     * Scope a query to only include expired vehicles.
     */
    public function scopeExpired($query)
    {
        return $query->whereDate('itv_date', '<', now());
    }

    /**
     * Scope a query to only include urgent vehicles (expires within 7 days).
     */
    public function scopeUrgent($query)
    {
        return $query->whereDate('itv_date', '<=', now()->addDays(7));
    }

    /**
     * Scope a query to only include warning vehicles (expires within 30 days).
     */
    public function scopeWarning($query)
    {
        return $query->whereBetween('itv_date', [now(), now()->addDays(30)]);
    }

    /**
     * Scope a query to only include valid vehicles (more than 30 days).
     */
    public function scopeValid($query)
    {
        return $query->whereDate('itv_date', '>', now()->addDays(30));
    }

    /**
     * Scope a query to search by client name, plate, or car.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('plate', 'like', "%{$search}%")
              ->orWhere('car', 'like', "%{$search}%")
              ->orWhereHas('client', function ($cq) use ($search) {
                  $cq->where('name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Scope to order by ITV date (soonest first).
     */
    public function scopeOrderByItvDate($query)
    {
        return $query->orderBy('itv_date', 'asc');
    }

    /**
     * Relación: Un vehículo pertenece a un cliente
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación: Un vehículo tiene muchas citas
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Obtener citas futuras del vehículo
     */
    public function futureAppointments()
    {
        return $this->appointments()
            ->where('appointment_date', '>', now())
            ->orderBy('appointment_date', 'asc');
    }

    /**
     * Obtener histórico de citas (ordenado por más reciente)
     */
    public function appointmentHistory()
    {
        return $this->appointments()
            ->orderBy('appointment_date', 'desc');
    }

    /**
     * Obtener la próxima cita del vehículo
     */
    public function nextAppointment()
    {
        return $this->appointments()
            ->where('appointment_date', '>', now())
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->orderBy('appointment_date', 'asc')
            ->first();
    }
}
