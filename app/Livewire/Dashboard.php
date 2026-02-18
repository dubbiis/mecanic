<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Appointment;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    /**
     * Get total vehicles count.
     */
    #[Computed]
    public function totalVehicles(): int
    {
        return Vehicle::count();
    }

    /**
     * Get expired vehicles count.
     */
    #[Computed]
    public function expiredCount(): int
    {
        return Vehicle::expired()->count();
    }

    /**
     * Get warning vehicles count (expires within 30 days).
     */
    #[Computed]
    public function warningCount(): int
    {
        return Vehicle::warning()->count();
    }

    /**
     * Get valid vehicles count (more than 30 days).
     */
    #[Computed]
    public function validCount(): int
    {
        return Vehicle::valid()->count();
    }

    /**
     * Get expired vehicles list.
     */
    #[Computed]
    public function expiredVehicles()
    {
        return Vehicle::with('client')
            ->expired()
            ->orderByItvDate()
            ->get();
    }

    /**
     * Get warning vehicles list (expires within 30 days).
     */
    #[Computed]
    public function warningVehicles()
    {
        return Vehicle::with('client')
            ->warning()
            ->orderByItvDate()
            ->get();
    }

    /**
     * Get today's appointments.
     */
    #[Computed]
    public function todayAppointments()
    {
        return Appointment::with('vehicle.client')
            ->today()
            ->orderBy('appointment_date', 'asc')
            ->get();
    }

    /**
     * Get upcoming appointments (next 7 days).
     */
    #[Computed]
    public function upcomingAppointments()
    {
        return Appointment::with('vehicle.client')
            ->where('appointment_date', '>=', now())
            ->where('appointment_date', '<=', now()->addDays(7))
            ->where('status', '!=', 'cancelada')
            ->orderBy('appointment_date', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Get tomorrow's appointments.
     */
    #[Computed]
    public function tomorrowAppointments()
    {
        return Appointment::with('vehicle.client')
            ->whereDate('appointment_date', now()->addDay())
            ->orderBy('appointment_date', 'asc')
            ->get();
    }

    /**
     * Get this month's appointments for calendar.
     */
    #[Computed]
    public function monthAppointments()
    {
        return Appointment::with('vehicle.client')
            ->whereYear('appointment_date', now()->year)
            ->whereMonth('appointment_date', now()->month)
            ->orderBy('appointment_date', 'asc')
            ->get();
    }

    /**
     * Get month appointments formatted for FullCalendar.
     */
    public function getMonthEventsProperty()
    {
        return $this->monthAppointments->map(function ($appointment) {
            $clientName = $appointment->vehicle->client->name ?? $appointment->vehicle->car;
            return [
                'id' => $appointment->id,
                'title' => $clientName . ' - ' . $appointment->vehicle->plate,
                'start' => $appointment->appointment_date->toIso8601String(),
                'backgroundColor' => $appointment->getBadgeColor(),
                'borderColor' => $appointment->getBadgeColor(),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'client_name' => $clientName,
                    'vehicle_plate' => $appointment->vehicle->plate,
                    'service_type' => $appointment->service_type,
                    'status' => $appointment->status,
                    'phone' => $appointment->vehicle->client->phone ?? '',
                ]
            ];
        })->toArray();
    }

    /**
     * Send notification (simulated).
     */
    public function sendNotification(int $vehicleId): void
    {
        $this->dispatch('notify',
            message: 'Notificación enviada',
            type: 'success'
        );
    }

    /**
     * Navigate to appointments calendar.
     */
    public function goToAppointments(): void
    {
        $this->redirect(route('appointments.index'));
    }

    /**
     * Navigate to create appointment.
     */
    public function createAppointment(?int $vehicleId = null): void
    {
        if ($vehicleId) {
            $this->redirect(route('appointments.create', ['vehicleId' => $vehicleId]));
        } else {
            $this->redirect(route('appointments.create'));
        }
    }

    /**
     * Navigate to edit appointment.
     */
    public function editAppointment(int $appointmentId): void
    {
        $this->redirect(route('appointments.edit', $appointmentId));
    }

    /**
     * Delete appointment.
     */
    public function deleteAppointment(int $appointmentId): void
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->delete();

        $this->dispatch('notify',
            message: 'Cita eliminada correctamente',
            type: 'success'
        );
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.dashboard');
    }
}
