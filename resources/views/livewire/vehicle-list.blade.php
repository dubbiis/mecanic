<div class="space-y-6" @confirm-delete.window="$wire.deleteVehicle()" @cancel-delete.window="$wire.cancelDelete()">
    {{-- Search Bar --}}
    <div class="glass-card p-4">
        <div class="relative w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar por matrícula, nombre..."
                class="w-full pl-10 pr-4 py-2 bg-zinc-50 border-none rounded-xl focus:ring-2 focus:ring-zinc-900/10 outline-none text-sm placeholder:text-zinc-400"
            />
        </div>
    </div>

    {{-- Loading State --}}
    <div wire:loading class="text-center py-4">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-zinc-900"></div>
    </div>

    {{-- Vehicles Table --}}
    <div wire:loading.remove class="glass-card overflow-hidden overflow-x-auto">
        <table class="w-full text-left text-sm text-zinc-600">
            <thead class="bg-zinc-50/50 text-zinc-400 font-medium text-xs uppercase tracking-wider border-b border-zinc-100">
                <tr>
                    <th class="p-5 font-medium">Cliente</th>
                    <th class="p-5 font-medium">Vehículo</th>
                    <th class="p-5 font-medium">ITV</th>
                    <th class="p-5 font-medium">Estado</th>
                    <th class="p-5 font-medium text-center">Pedir Cita ITV</th>
                    <th class="p-5 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($this->vehicles as $vehicle)
                    <tr wire:key="vehicle-{{ $vehicle->id }}" class="hover:bg-zinc-50/80 transition-colors group border-b border-zinc-50 last:border-0">
                        <td class="p-5">
                            @if($vehicle->client)
                                <a href="{{ route('clients.profile', $vehicle->client_id) }}" class="font-semibold text-zinc-900 hover:text-zinc-600 transition-colors">
                                    {{ $vehicle->client->name }}
                                </a>
                                <div class="text-xs text-zinc-400 mt-1 font-mono">{{ $vehicle->client->phone }}</div>
                            @else
                                <span class="text-zinc-400 text-sm italic">Sin cliente</span>
                            @endif
                        </td>
                        <td class="p-5">
                            <div class="text-zinc-800">{{ $vehicle->car }}</div>
                            <div class="inline-block mt-1 px-1.5 py-0.5 bg-zinc-100 rounded text-xs font-mono text-zinc-600 border border-zinc-200">
                                {{ $vehicle->plate }}
                            </div>
                        </td>
                        <td class="p-5 font-medium font-mono text-zinc-500">
                            {{ $vehicle->getFormattedItvDate() }}
                        </td>
                        <td class="p-5">
                            @php
                                $status = $vehicle->getStatus();
                                $statusConfig = [
                                    'expired' => [
                                        'text' => 'Caducada',
                                        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                        'class' => 'text-red-600 bg-red-50 border-red-100'
                                    ],
                                    'urgent' => [
                                        'text' => 'Urgente',
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'class' => 'text-red-600 bg-red-50 border-red-100'
                                    ],
                                    'warning' => [
                                        'text' => $vehicle->daysUntilExpiration() . ' días',
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'class' => 'text-amber-600 bg-amber-50 border-amber-100'
                                    ],
                                    'valid' => [
                                        'text' => 'Vigente',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'class' => 'text-emerald-600 bg-emerald-50 border-emerald-100'
                                    ]
                                ];
                                $config = $statusConfig[$status];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-xs font-medium border flex items-center gap-1.5 w-fit {{ $config['class'] }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                </svg>
                                {{ $config['text'] }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <a href="https://www.itvcita.com/Welcome.do" target="_blank" class="inline-block hover:opacity-80 transition-opacity" title="Pedir cita ITV">
                                <img src="{{ asset('images/ITV_logo.png') }}" alt="ITV Logo" class="h-8 w-auto mx-auto">
                            </a>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                <button
                                    wire:click="sendNotification({{ $vehicle->id }})"
                                    class="p-2 text-zinc-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                    title="Enviar notificación WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </button>
                                <button
                                    wire:click="createAppointment({{ $vehicle->id }})"
                                    class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Nueva cita">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                <button
                                    wire:click="edit({{ $vehicle->id }})"
                                    class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button
                                    wire:click="confirmDelete({{ $vehicle->id }})"
                                    class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-zinc-400">
                            @if($search)
                                No se encontraron resultados para "{{ $search }}"
                            @else
                                No hay vehículos registrados.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
