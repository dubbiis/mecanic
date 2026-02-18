<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class ClientList extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    public ?int $clientToDelete = null;

    #[Computed]
    public function clients()
    {
        $query = Client::withCount('vehicles');

        if ($this->search) {
            $query->search($this->search);
        }

        return $query->orderBy('name')->get();
    }

    public function confirmDelete(int $clientId): void
    {
        $this->clientToDelete = $clientId;
        $this->dispatch('open-delete-modal');
    }

    public function deleteClient(): void
    {
        if ($this->clientToDelete) {
            Client::findOrFail($this->clientToDelete)->delete();
            $this->clientToDelete = null;

            $this->dispatch('notify', message: 'Cliente eliminado', type: 'success');
            $this->dispatch('close-delete-modal');
        }
    }

    public function cancelDelete(): void
    {
        $this->clientToDelete = null;
        $this->dispatch('close-delete-modal');
    }

    public function create(): void
    {
        $this->redirect(route('clients.create'));
    }

    public function edit(int $clientId): void
    {
        $this->redirect(route('clients.edit', $clientId));
    }

    public function profile(int $clientId): void
    {
        $this->redirect(route('clients.profile', $clientId));
    }

    public function render()
    {
        return view('livewire.client-list');
    }
}
