<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Appointment;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ClientProfile extends Component
{
    public int $clientId;

    #[Computed]
    public function client(): Client
    {
        return Client::with(['vehicles'])->findOrFail($this->clientId);
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::withTrashed()
            ->whereHas('vehicle', fn ($q) => $q->where('client_id', $this->clientId))
            ->with('vehicle')
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    public function mount(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function editClient(): void
    {
        $this->redirect(route('clients.edit', $this->clientId));
    }

    public function addVehicle(): void
    {
        $this->redirect(route('vehicles.create', ['clientId' => $this->clientId]));
    }

    public function editVehicle(int $vehicleId): void
    {
        $this->redirect(route('vehicles.edit', $vehicleId));
    }

    public function createAppointment(int $vehicleId): void
    {
        $this->redirect(route('appointments.create', ['vehicleId' => $vehicleId]));
    }

    public function backToClients(): void
    {
        $this->redirect(route('clients.index'));
    }

    public function render()
    {
        return view('livewire.client-profile');
    }
}
