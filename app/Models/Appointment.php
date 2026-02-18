<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'appointment_date',
        'service_type',
        'status',
        'description',
        'work_done',
        'estimated_cost',
        'final_cost',
        'estimated_duration',
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
            'appointment_date' => 'datetime',
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'estimated_duration' => 'integer',
        ];
    }

    /**
     * Relación: Una cita pertenece a un vehículo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope: Citas aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'aprobada');
    }

    /**
     * Scope: Citas reagendadas
     */
    public function scopeRescheduled($query)
    {
        return $query->where('status', 'reagendada');
    }

    /**
     * Scope: Citas canceladas
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelada');
    }

    /**
     * Scope: Citas de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Scope: Citas de esta semana
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('appointment_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope: Citas futuras
     */
    public function scopeFuture($query)
    {
        return $query->where('appointment_date', '>', now());
    }

    /**
     * Scope: Citas pasadas
     */
    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now());
    }

    /**
     * Scope: Ordenar por fecha de cita
     */
    public function scopeOrderByDate($query, $direction = 'asc')
    {
        return $query->orderBy('appointment_date', $direction);
    }

    /**
     * Verificar si la cita es hoy
     */
    public function isToday(): bool
    {
        return $this->appointment_date->isToday();
    }

    /**
     * Verificar si la cita está atrasada
     */
    public function isOverdue(): bool
    {
        return $this->appointment_date->isPast()
            && in_array($this->status, ['aprobada', 'reagendada']);
    }

    /**
     * Obtener color según el estado (verde, azul, rojo)
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'aprobada' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'reagendada' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'cancelada' => 'bg-red-50 text-red-700 border border-red-200',
            default => 'bg-zinc-50 text-zinc-700 border border-zinc-200'
        };
    }

    /**
     * Obtener color de badge para calendario (verde, azul, rojo)
     */
    public function getBadgeColor(): string
    {
        return match($this->status) {
            'aprobada' => '#10b981',      // Verde emerald
            'reagendada' => '#3b82f6',    // Azul
            'cancelada' => '#ef4444',     // Rojo
            default => '#6b7280'
        };
    }

    /**
     * Obtener texto del estado en español
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'aprobada' => 'Aprobada',
            'reagendada' => 'Reagendada',
            'cancelada' => 'Cancelada',
            default => 'Desconocido'
        };
    }

    /**
     * Obtener texto del tipo de servicio
     */
    public function getServiceTypeLabel(): string
    {
        return match($this->service_type) {
            'revision' => 'Revisión',
            'reparacion' => 'Reparación',
            'itv' => 'ITV',
            'diagnostico' => 'Diagnóstico',
            'mantenimiento' => 'Mantenimiento',
            'otro' => 'Otro',
            default => 'No especificado'
        };
    }

    /**
     * Formatear fecha de la cita
     */
    public function getFormattedDate(): string
    {
        return $this->appointment_date->format('d/m/Y H:i');
    }

    /**
     * Formatear solo la hora
     */
    public function getFormattedTime(): string
    {
        return $this->appointment_date->format('H:i');
    }
}
