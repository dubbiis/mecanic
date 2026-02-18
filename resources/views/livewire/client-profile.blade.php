<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <button wire:click="backToClients" class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div>
                <h2 class="text-xl font-bold text-zinc-900">{{ $this->client->name }}</h2>
                <p class="text-sm text-zinc-400 mt-0.5">Perfil del cliente</p>
            </div>
        </div>
        <button
            wire:click="editClient"
            class="flex items-center gap-2 px-4 py-2 bg-zinc-100 text-zinc-700 text-sm font-medium rounded-xl hover:bg-zinc-200 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </button>
    </div>

    {{-- Client Info Card --}}
    <div class="glass-card p-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider mb-1">Teléfono</p>
                <p class="text-sm font-semibold text-zinc-900 font-mono">{{ $this->client->phone }}</p>
            </div>
            @if($this->client->email)
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider mb-1">Email</p>
                <p class="text-sm font-semibold text-zinc-900">{{ $this->client->email }}</p>
            </div>
            @endif
            @if($this->client->dni_nif)
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider mb-1">DNI / NIF</p>
                <p class="text-sm font-semibold text-zinc-900 font-mono">{{ $this->client->dni_nif }}</p>
            </div>
            @endif
            @if($this->client->address)
            <div class="col-span-2 sm:col-span-1">
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider mb-1">Dirección</p>
                <p class="text-sm font-semibold text-zinc-900">{{ $this->client->address }}</p>
            </div>
            @endif
        </div>
        @if($this->client->notes)
        <div class="mt-4 pt-4 border-t border-zinc-100">
            <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider mb-1">Observaciones</p>
            <p class="text-sm text-zinc-600">{{ $this->client->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Vehicles --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-zinc-900">
                Vehículos
                <span class="ml-1.5 px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500 rounded-full">
                    {{ $this->client->vehicles->count() }}
                </span>
            </h3>
            <button
                wire:click="addVehicle"
                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-zinc-900 text-white rounded-lg hover:bg-zinc-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Añadir vehículo
            </button>
        </div>

        @forelse($this->client->vehicles as $vehicle)
            <div class="glass-card p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center text-zinc-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-zinc-900 text-sm">{{ $vehicle->car }}</div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="px-1.5 py-0.5 bg-zinc-100 rounded text-xs font-mono text-zinc-600 border border-zinc-200">
                                {{ $vehicle->plate }}
                            </span>
                            @if($vehicle->vin)
                                <span class="text-xs text-zinc-400 font-mono">VIN: {{ $vehicle->vin }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $status = $vehicle->getStatus();
                        $itvConfig = [
                            'expired' => ['text' => 'ITV Caducada', 'class' => 'text-red-600 bg-red-50 border-red-100'],
                            'urgent'  => ['text' => 'ITV Urgente',  'class' => 'text-red-600 bg-red-50 border-red-100'],
                            'warning' => ['text' => $vehicle->daysUntilExpiration() . ' días ITV', 'class' => 'text-amber-600 bg-amber-50 border-amber-100'],
                            'valid'   => ['text' => 'ITV Vigente',  'class' => 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded-lg text-xs font-medium border {{ $itvConfig[$status]['class'] }}">
                        {{ $itvConfig[$status]['text'] }}
                    </span>
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="createAppointment({{ $vehicle->id }})"
                            class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors"
                            title="Nueva cita">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Cita
                        </button>
                        <button
                            wire:click="editVehicle({{ $vehicle->id }})"
                            class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors"
                            title="Editar vehículo">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-panel border-dashed p-8 text-center text-zinc-400">
                <svg class="w-8 h-8 mx-auto mb-2 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
                <p class="text-sm">Este cliente no tiene vehículos registrados.</p>
                <button wire:click="addVehicle" class="mt-2 text-sm font-medium text-zinc-900 underline underline-offset-2">
                    Añadir vehículo
                </button>
            </div>
        @endforelse
    </div>

    {{-- Appointment History --}}
    <div class="space-y-3">
        <h3 class="text-base font-semibold text-zinc-900">
            Historial de citas
            <span class="ml-1.5 px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500 rounded-full">
                {{ $this->appointments->count() }}
            </span>
        </h3>

        @if($this->appointments->isEmpty())
            <div class="glass-panel border-dashed p-8 text-center text-zinc-400">
                <svg class="w-8 h-8 mx-auto mb-2 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm">No hay citas en el historial.</p>
            </div>
        @else
            <div class="glass-card overflow-hidden overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-600">
                    <thead class="bg-zinc-50/50 text-zinc-400 font-medium text-xs uppercase tracking-wider border-b border-zinc-100">
                        <tr>
                            <th class="p-4 font-medium">Fecha</th>
                            <th class="p-4 font-medium">Vehículo</th>
                            <th class="p-4 font-medium">Servicio</th>
                            <th class="p-4 font-medium">Estado</th>
                            <th class="p-4 font-medium text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($this->appointments as $appointment)
                            <tr wire:key="appt-{{ $appointment->id }}"
                                class="{{ $appointment->trashed() ? 'opacity-50' : '' }} hover:bg-zinc-50/80 transition-colors border-b border-zinc-50 last:border-0">
                                <td class="p-4">
                                    <div class="font-medium text-zinc-900">
                                        {{ $appointment->appointment_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-zinc-400 mt-0.5">
                                        {{ $appointment->appointment_date->format('H:i') }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="text-zinc-800">{{ $appointment->vehicle->car ?? '—' }}</div>
                                    <div class="inline-block mt-0.5 px-1.5 py-0.5 bg-zinc-100 rounded text-xs font-mono text-zinc-500 border border-zinc-200">
                                        {{ $appointment->vehicle->plate ?? '—' }}
                                    </div>
                                </td>
                                <td class="p-4 text-zinc-600">
                                    {{ $appointment->getServiceTypeLabel() }}
                                    @if($appointment->trashed())
                                        <span class="ml-1 text-xs text-zinc-400">(eliminada)</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $appointment->getStatusColor() }}">
                                        {{ $appointment->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="p-4 text-right font-mono text-zinc-900">
                                    @if($appointment->final_cost)
                                        {{ number_format($appointment->final_cost, 2, ',', '.') }} €
                                    @elseif($appointment->estimated_cost)
                                        <span class="text-zinc-400">~{{ number_format($appointment->estimated_cost, 2, ',', '.') }} €</span>
                                    @else
                                        <span class="text-zinc-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
