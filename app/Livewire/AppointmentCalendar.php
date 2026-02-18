<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class AppointmentCalendar extends Component
{
    #[Url]
    public $filterStatus = '';

    #[Url]
    public $filterServiceType = '';

    public $showDeleteModal = false;
    public $appointmentToDelete = null;
    public $showHistoryModal = false;
    public $historyView = 'table'; // 'table' o 'calendar'

    // Estados disponibles
    public $statuses = [
        'aprobada' => 'Aprobada',
        'reagendada' => 'Reagendada',
        'cancelada' => 'Cancelada'
    ];

    // Tipos de servicio disponibles
    public $serviceTypes = [
        'revision' => 'Revisión',
        'reparacion' => 'Reparación',
        'itv' => 'ITV',
        'diagnostico' => 'Diagnóstico',
        'mantenimiento' => 'Mantenimiento',
        'otro' => 'Otro'
    ];

    /**
     * Obtiene todas las citas en formato JSON para FullCalendar
     */
    public function getEventsProperty()
    {
        $query = Appointment::with('vehicle');

        // Aplicar filtros
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterServiceType) {
            $query->where('service_type', $this->filterServiceType);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->get();

        // Transformar a formato FullCalendar
        return $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->vehicle->name . ' - ' . $appointment->vehicle->plate,
                'start' => $appointment->appointment_date,
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $appointment->isToday() ? '#fbbf24' : $this->getStatusColor($appointment->status),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'vehicle_id' => $appointment->vehicle_id,
                    'vehicle_name' => $appointment->vehicle->name,
                    'vehicle_plate' => $appointment->vehicle->plate,
                    'service_type' => $appointment->service_type,
                    'status' => $appointment->status,
                    'description' => $appointment->description,
                    'estimated_cost' => $appointment->estimated_cost,
                    'phone' => $appointment->vehicle->phone,
                ]
            ];
        })->toArray();
    }

    /**
     * Obtiene las citas de hoy
     */
    public function getTodayAppointmentsProperty()
    {
        $query = Appointment::with('vehicle')->today();

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterServiceType) {
            $query->where('service_type', $this->filterServiceType);
        }

        return $query->orderBy('appointment_date', 'asc')->get();
    }

    /**
     * Obtiene color según el estado
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'aprobada' => '#10b981',       // Verde emerald
            'reagendada' => '#3b82f6',     // Azul
            'cancelada' => '#ef4444',      // Rojo
            default => '#6b7280'
        };
    }

    /**
     * Cambiar estado de una cita
     */
    public function changeStatus($appointmentId, $newStatus)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->status = $newStatus;
        $appointment->save();

        $this->dispatch('appointment-updated');
        $this->dispatch('notify',
            message: 'Estado actualizado correctamente',
            type: 'success'
        );
    }

    /**
     * Actualizar fecha de una cita (drag & drop)
     */
    public function updateAppointmentDate($appointmentId, $newDate)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->appointment_date = Carbon::parse($newDate);
        $appointment->save();

        $this->dispatch('appointment-updated');
        $this->dispatch('notify',
            message: 'Cita reprogramada correctamente',
            type: 'success'
        );
    }

    /**
     * Confirmar eliminación
     */
    public function confirmDelete($appointmentId)
    {
        $this->appointmentToDelete = $appointmentId;
        $this->showDeleteModal = true;
    }

    /**
     * Eliminar cita
     */
    public function deleteAppointment()
    {
        if ($this->appointmentToDelete) {
            $appointment = Appointment::findOrFail($this->appointmentToDelete);
            $appointment->delete();

            $this->showDeleteModal = false;
            $this->appointmentToDelete = null;

            $this->dispatch('appointment-updated');
            $this->dispatch('notify',
                message: 'Cita eliminada correctamente',
                type: 'success'
            );
        }
    }

    /**
     * Cancelar eliminación
     */
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->appointmentToDelete = null;
    }

    /**
     * Navegar a crear cita
     */
    public function create()
    {
        return redirect()->route('appointments.create');
    }

    /**
     * Navegar a editar cita
     */
    public function edit($appointmentId)
    {
        return redirect()->route('appointments.edit', $appointmentId);
    }

    /**
     * Enviar notificación WhatsApp (simulated)
     */
    public function sendWhatsApp($appointmentId)
    {
        $this->dispatch('notify',
            message: 'Notificación enviada',
            type: 'success'
        );
    }

    /**
     * Limpiar filtros
     */
    public function clearFilters()
    {
        $this->filterStatus = '';
        $this->filterServiceType = '';
    }

    /**
     * Mostrar historial completo de citas
     */
    public function showHistory()
    {
        $this->showHistoryModal = true;
        $this->historyView = 'table'; // Por defecto tabla
    }

    /**
     * Cerrar modal de historial
     */
    public function closeHistory()
    {
        $this->showHistoryModal = false;
    }

    /**
     * Cambiar vista del historial
     */
    public function switchHistoryView($view)
    {
        $this->historyView = $view;
    }

    /**
     * Obtener todas las citas (historial completo)
     */
    public function getAllAppointmentsProperty()
    {
        $query = Appointment::with('vehicle');

        // Aplicar filtros si existen
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterServiceType) {
            $query->where('service_type', $this->filterServiceType);
        }

        return $query->orderBy('appointment_date', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.appointment-calendar');
    }
}
