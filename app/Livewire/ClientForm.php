<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ClientForm extends Component
{
    public ?int $clientId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $dni_nif = '';

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    #[Validate('nullable|string')]
    public string $notes = '';

    public function mount(?int $clientId = null): void
    {
        $this->clientId = $clientId;

        if ($clientId) {
            $client = Client::findOrFail($clientId);
            $this->fill([
                'name'    => $client->name,
                'phone'   => $client->phone,
                'email'   => $client->email ?? '',
                'dni_nif' => $client->dni_nif ?? '',
                'address' => $client->address ?? '',
                'notes'   => $client->notes ?? '',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'    => $this->name,
            'phone'   => $this->phone,
            'email'   => $this->email ?: null,
            'dni_nif' => $this->dni_nif ?: null,
            'address' => $this->address ?: null,
            'notes'   => $this->notes ?: null,
        ];

        if ($this->clientId) {
            Client::findOrFail($this->clientId)->update($data);
            $this->dispatch('notify', message: 'Cliente actualizado', type: 'success');
        } else {
            $client = Client::create($data);
            $this->dispatch('notify', message: 'Cliente creado', type: 'success');
        }

        $this->redirect(route('clients.index'));
    }

    public function saveAndAddVehicle(): void
    {
        $this->validate();

        $data = [
            'name'    => $this->name,
            'phone'   => $this->phone,
            'email'   => $this->email ?: null,
            'dni_nif' => $this->dni_nif ?: null,
            'address' => $this->address ?: null,
            'notes'   => $this->notes ?: null,
        ];

        if ($this->clientId) {
            Client::findOrFail($this->clientId)->update($data);
            $clientId = $this->clientId;
        } else {
            $client = Client::create($data);
            $clientId = $client->id;
        }

        $this->redirect(route('vehicles.create', ['clientId' => $clientId]));
    }

    public function cancel(): void
    {
        $this->redirect(route('clients.index'));
    }

    public function render()
    {
        return view('livewire.client-form');
    }
}
