<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class AppointmentForm extends Component
{
    public $appointmentId = null;
    public $vehicleId = null; // Para preseleccionar vehículo desde otras vistas

    #[Validate('required|exists:vehicles,id')]
    public $vehicle_id = '';

    #[Validate('required|date')]
    public $appointment_date = '';

    #[Validate('required|date_format:H:i')]
    public $appointment_time = '';

    #[Validate('required|in:revision,reparacion,itv,diagnostico,mantenimiento,otro')]
    public $service_type = 'revision';

    #[Validate('required|in:aprobada,reagendada,cancelada')]
    public $status = 'aprobada';

    #[Validate('nullable|string|max:1000')]
    public $description = '';

    #[Validate('nullable|string|max:1000')]
    public $work_done = '';

    #[Validate('nullable|numeric|min:0')]
    public $estimated_cost = null;

    #[Validate('nullable|numeric|min:0')]
    public $final_cost = null;

    #[Validate('nullable|integer|min:0')]
    public $estimated_duration = null;

    #[Validate('nullable|string|max:500')]
    public $notes = '';

    public string $clientSearch = '';
    public ?int $selectedClientId = null;

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

    public function mount($appointmentId = null, $vehicleId = null)
    {
        $this->appointmentId = $appointmentId;

        // Leer vehicleId de parámetros de ruta o query string
        $vehicleIdParam = $vehicleId ?? request()->query('vehicleId');

        // Si viene vehicleId preseleccionado
        if ($vehicleIdParam) {
            $this->vehicle_id = $vehicleIdParam;
            $this->vehicleId = $vehicleIdParam;
            $vehicle = Vehicle::find($vehicleIdParam);
            if ($vehicle) {
                $this->selectedClientId = $vehicle->client_id;
            }
        }

        // Si es edición, cargar datos
        if ($appointmentId) {
            $appointment = Appointment::with('vehicle')->findOrFail($appointmentId);
            $this->vehicle_id = $appointment->vehicle_id;
            $this->selectedClientId = $appointment->vehicle->client_id ?? null;
            $this->service_type = $appointment->service_type;
            $this->status = $appointment->status;
            $this->description = $appointment->description;
            $this->work_done = $appointment->work_done;
            $this->estimated_cost = $appointment->estimated_cost;
            $this->final_cost = $appointment->final_cost;
            $this->estimated_duration = $appointment->estimated_duration;
            $this->notes = $appointment->notes;

            // Separar fecha y hora
            $date = Carbon::parse($appointment->appointment_date);
            $this->appointment_date = $date->format('Y-m-d');
            $this->appointment_time = $date->format('H:i');
        } else {
            // Valores por defecto para nueva cita
            $this->appointment_date = now()->format('Y-m-d');
            $this->appointment_time = '09:00';
        }
    }

    /**
     * Seleccionar cliente (paso 1)
     */
    public function selectClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->clientSearch = '';
        $this->vehicle_id = '';
    }

    /**
     * Limpiar cliente y vehículo seleccionados
     */
    public function clearClient(): void
    {
        $this->selectedClientId = null;
        $this->clientSearch = '';
        $this->vehicle_id = '';
        $this->resetValidation('vehicle_id');
    }

    /**
     * Seleccionar vehículo (paso 2)
     */
    public function selectVehicle($vehicleId): void
    {
        $this->vehicle_id = $vehicleId;
    }

    /**
     * Cambiar vehículo (vuelve al paso 2, mantiene cliente)
     */
    public function changeVehicle(): void
    {
        $this->vehicle_id = '';
        $this->resetValidation('vehicle_id');
    }

    /**
     * Obtener vehículo seleccionado
     */
    public function getSelectedVehicleProperty()
    {
        if ($this->vehicle_id) {
            return Vehicle::with('client')->find($this->vehicle_id);
        }
        return null;
    }

    /**
     * Guardar cita
     */
    public function save()
    {
        $this->validate();

        // Combinar fecha y hora
        $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);

        $data = [
            'vehicle_id' => $this->vehicle_id,
            'appointment_date' => $appointmentDateTime,
            'service_type' => $this->service_type,
            'status' => $this->status,
            'description' => $this->description ?: null,
            'work_done' => $this->work_done ?: null,
            'estimated_cost' => $this->estimated_cost ?: null,
            'final_cost' => $this->final_cost ?: null,
            'estimated_duration' => $this->estimated_duration ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->appointmentId) {
            // Actualizar
            $appointment = Appointment::findOrFail($this->appointmentId);
            $appointment->update($data);
            $message = 'Cita actualizada correctamente';
        } else {
            // Crear
            Appointment::create($data);
            $message = 'Cita creada correctamente';
        }

        session()->flash('success', $message);
        return redirect()->route('appointments.index');
    }

    /**
     * Cancelar y volver
     */
    public function cancel()
    {
        return redirect()->route('appointments.index');
    }

    /**
     * Enviar WhatsApp (simulated)
     */
    public function sendWhatsApp()
    {
        $this->dispatch('notify',
            message: 'Notificación enviada',
            type: 'success'
        );
    }

    public function render()
    {
        $clientResults = collect();
        if (strlen($this->clientSearch) >= 1) {
            $clientResults = Client::search($this->clientSearch)->limit(6)->get();
        }

        $selectedClient = $this->selectedClientId
            ? Client::find($this->selectedClientId)
            : null;

        $clientVehicles = $this->selectedClientId
            ? Vehicle::where('client_id', $this->selectedClientId)->orderBy('plate')->get()
            : collect();

        return view('livewire.appointment-form', compact('clientResults', 'selectedClient', 'clientVehicles'));
    }
}
