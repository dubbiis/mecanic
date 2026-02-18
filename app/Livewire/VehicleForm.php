<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class VehicleForm extends Component
{
    public ?int $vehicleId = null;
    public ?int $savedVehicleId = null;

    // Pre-selected client via query param (e.g., from client profile)
    public ?int $clientId = null;

    // Client search
    public string $clientSearch = '';

    #[Validate('required|integer|exists:clients,id')]
    public ?int $selectedClientId = null;

    #[Validate('required|string|max:255')]
    public string $car = '';

    #[Validate('required|string|max:255')]
    public string $plate = '';

    #[Validate('nullable|string|max:17')]
    public string $vin = '';

    #[Validate('required|date')]
    public string $itv_date = '';

    #[Validate('nullable|string')]
    public string $notes = '';

    public function mount(?int $vehicleId = null): void
    {
        $this->vehicleId = $vehicleId;

        if ($vehicleId) {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $this->selectedClientId = $vehicle->client_id;
            $this->car      = $vehicle->car;
            $this->plate    = $vehicle->plate;
            $this->vin      = $vehicle->vin ?? '';
            $this->itv_date = $vehicle->itv_date->format('Y-m-d');
            $this->notes    = $vehicle->notes ?? '';
        } else {
            // Lee el clientId desde el query string (?clientId=X)
            $clientIdParam = request()->query('clientId');
            if ($clientIdParam) {
                $this->selectedClientId = (int) $clientIdParam;
            }
        }
    }

    public function selectClient(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->clientSearch = '';
    }

    public function clearClient(): void
    {
        $this->selectedClientId = null;
        $this->clientSearch = '';
    }

    public function save(): void
    {
        $this->validate([
            'selectedClientId' => 'required|integer|exists:clients,id',
            'car'     => 'required|string|max:255',
            'plate'   => [
                'required', 'string', 'max:255',
                'unique:vehicles,plate' . ($this->vehicleId ? ',' . $this->vehicleId : '')
            ],
            'vin'      => 'nullable|string|max:17',
            'itv_date' => 'required|date',
            'notes'    => 'nullable|string',
        ]);

        $data = [
            'client_id' => $this->selectedClientId,
            'car'       => $this->car,
            'plate'     => strtoupper($this->plate),
            'vin'       => $this->vin ? strtoupper($this->vin) : null,
            'itv_date'  => $this->itv_date,
            'notes'     => $this->notes ?: null,
        ];

        if ($this->vehicleId) {
            Vehicle::findOrFail($this->vehicleId)->update($data);
            $this->savedVehicleId = $this->vehicleId;
            $this->dispatch('notify', message: 'Vehículo actualizado', type: 'success');
        } else {
            $vehicle = Vehicle::create($data);
            $this->savedVehicleId = $vehicle->id;
            $this->dispatch('notify', message: 'Vehículo creado', type: 'success');
        }

        $this->redirect(route('vehicles.index'));
    }

    public function saveAndCreateAppointment(): void
    {
        $this->validate([
            'selectedClientId' => 'required|integer|exists:clients,id',
            'car'     => 'required|string|max:255',
            'plate'   => [
                'required', 'string', 'max:255',
                'unique:vehicles,plate' . ($this->vehicleId ? ',' . $this->vehicleId : '')
            ],
            'vin'      => 'nullable|string|max:17',
            'itv_date' => 'required|date',
            'notes'    => 'nullable|string',
        ]);

        $data = [
            'client_id' => $this->selectedClientId,
            'car'       => $this->car,
            'plate'     => strtoupper($this->plate),
            'vin'       => $this->vin ? strtoupper($this->vin) : null,
            'itv_date'  => $this->itv_date,
            'notes'     => $this->notes ?: null,
        ];

        if ($this->vehicleId) {
            Vehicle::findOrFail($this->vehicleId)->update($data);
            $vehicleId = $this->vehicleId;
        } else {
            $vehicle = Vehicle::create($data);
            $vehicleId = $vehicle->id;
        }

        $this->redirect(route('appointments.create', ['vehicleId' => $vehicleId]));
    }

    public function cancel(): void
    {
        $this->redirect(route('vehicles.index'));
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

        return view('livewire.vehicle-form', compact('clientResults', 'selectedClient'));
    }
}
