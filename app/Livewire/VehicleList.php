<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class VehicleList extends Component
{
    /**
     * Search query.
     */
    #[Url(as: 'q')]
    public string $search = '';

    /**
     * Vehicle ID to delete (for confirmation modal).
     */
    public ?int $vehicleToDelete = null;

    /**
     * Get filtered and sorted vehicles.
     */
    #[Computed]
    public function vehicles()
    {
        $query = Vehicle::with('client');

        if ($this->search) {
            $query->search($this->search);
        }

        return $query->orderByItvDate()->get();
    }

    /**
     * Prepare to delete a vehicle.
     */
    public function confirmDelete(int $vehicleId): void
    {
        $this->vehicleToDelete = $vehicleId;
        $this->dispatch('open-delete-modal');
    }

    /**
     * Delete a vehicle.
     */
    public function deleteVehicle(): void
    {
        if ($this->vehicleToDelete) {
            Vehicle::findOrFail($this->vehicleToDelete)->delete();
            $this->vehicleToDelete = null;

            $this->dispatch('vehicle-deleted');
            $this->dispatch('close-delete-modal');
        }
    }

    /**
     * Cancel delete action.
     */
    public function cancelDelete(): void
    {
        $this->vehicleToDelete = null;
        $this->dispatch('close-delete-modal');
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
     * Navigate to create form.
     */
    public function create(): void
    {
        $this->redirect(route('vehicles.create'));
    }

    /**
     * Navigate to edit form.
     */
    public function edit(int $vehicleId): void
    {
        $this->redirect(route('vehicles.edit', $vehicleId));
    }

    /**
     * Navigate to create appointment for a vehicle.
     */
    public function createAppointment(int $vehicleId): void
    {
        $this->redirect(route('appointments.create', ['vehicleId' => $vehicleId]));
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.vehicle-list');
    }
}
