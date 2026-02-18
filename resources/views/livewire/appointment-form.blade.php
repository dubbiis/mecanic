<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-zinc-900">
            {{ $appointmentId ? 'Editar Cita' : 'Nueva Cita' }}
        </h2>
        <p class="mt-1 text-sm text-zinc-600">
            {{ $appointmentId ? 'Actualiza los detalles de la cita' : 'Programa una nueva cita para un vehículo' }}
        </p>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Tarjeta principal --}}
        <div class="glass-card p-6 space-y-6">

            {{-- Selección de Cliente y Vehículo --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">
                    Cliente y Vehículo <span class="text-red-500">*</span>
                </label>

                @if($this->selectedVehicle)
                    {{-- Paso 3: Vehículo seleccionado --}}
                    <div class="flex items-center justify-between p-4 bg-zinc-50 border border-zinc-200 rounded-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-zinc-900">{{ $this->selectedVehicle->client->name ?? '—' }}</p>
                            <p class="text-sm text-zinc-600">{{ $this->selectedVehicle->plate }} — {{ $this->selectedVehicle->car }}</p>
                            <p class="text-sm text-zinc-500">Tel: {{ $this->selectedVehicle->client->phone ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(!$appointmentId)
                                <button type="button" wire:click="changeVehicle"
                                    class="text-sm text-zinc-500 hover:text-zinc-900 px-3 py-1.5 rounded-lg hover:bg-zinc-200 transition-colors">
                                    Cambiar vehículo
                                </button>
                            @endif
                            <button type="button" wire:click="sendWhatsApp" class="p-2 text-green-600 hover:text-green-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                @elseif($selectedClientId && $selectedClient)
                    {{-- Paso 2: Cliente seleccionado, elegir vehículo --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-lg">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ $selectedClient->name }}</p>
                                @if($selectedClient->phone)
                                    <p class="text-xs text-zinc-400 mt-0.5">{{ $selectedClient->phone }}</p>
                                @endif
                            </div>
                            @if(!$appointmentId)
                                <button type="button" wire:click="clearClient"
                                    class="text-xs text-zinc-400 hover:text-zinc-700 px-2 py-1 rounded hover:bg-zinc-200 transition-colors">
                                    Cambiar cliente
                                </button>
                            @endif
                        </div>

                        @if($clientVehicles->isEmpty())
                            <p class="text-sm text-zinc-400 px-1">Este cliente no tiene vehículos registrados.</p>
                        @else
                            <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider px-1">Selecciona un vehículo</p>
                            <div class="grid gap-2">
                                @foreach($clientVehicles as $vehicle)
                                    <button
                                        type="button"
                                        wire:click="selectVehicle({{ $vehicle->id }})"
                                        class="w-full text-left px-4 py-3 bg-white border border-zinc-200 rounded-xl hover:border-zinc-400 hover:bg-zinc-50 transition-colors"
                                    >
                                        <p class="font-semibold text-zinc-900 text-sm">{{ $vehicle->car }}</p>
                                        <p class="text-xs text-zinc-500 font-mono mt-0.5">{{ $vehicle->plate }}</p>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                @else
                    {{-- Paso 1: Buscar cliente --}}
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="clientSearch"
                            placeholder="Buscar cliente por nombre o teléfono..."
                            class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                            autocomplete="off"
                        >
                        @if($clientResults->isNotEmpty())
                            <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-zinc-200 max-h-60 overflow-auto">
                                @foreach($clientResults as $client)
                                    <button
                                        type="button"
                                        wire:click="selectClient({{ $client->id }})"
                                        class="w-full text-left px-4 py-3 hover:bg-zinc-50 border-b border-zinc-100 last:border-b-0"
                                    >
                                        <p class="font-semibold text-zinc-900 text-sm">{{ $client->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $client->phone }}</p>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @error('vehicle_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha y Hora --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-zinc-700 mb-2">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="appointment_date"
                        wire:model="appointment_date"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                    @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-zinc-700 mb-2">
                        Hora <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="time"
                        id="appointment_time"
                        wire:model="appointment_time"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                    @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tipo de Servicio y Estado --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="service_type" class="block text-sm font-medium text-zinc-700 mb-2">
                        Tipo de Servicio <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="service_type"
                        wire:model="service_type"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                        @foreach($serviceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('service_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-zinc-700 mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="status"
                        wire:model="status"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label for="description" class="block text-sm font-medium text-zinc-700 mb-2">
                    Descripción del Trabajo
                </label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="3"
                    placeholder="Describe el trabajo a realizar..."
                    class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                ></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trabajo Realizado (solo si es edición) --}}
            @if($appointmentId)
            <div>
                <label for="work_done" class="block text-sm font-medium text-zinc-700 mb-2">
                    Trabajo Realizado
                </label>
                <textarea
                    id="work_done"
                    wire:model="work_done"
                    rows="3"
                    placeholder="Describe el trabajo que se realizó..."
                    class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                ></textarea>
                @error('work_done')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Costes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-zinc-700 mb-2">
                        Coste Estimado (€)
                    </label>
                    <input
                        type="number"
                        id="estimated_cost"
                        wire:model="estimated_cost"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                    @error('estimated_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="final_cost" class="block text-sm font-medium text-zinc-700 mb-2">
                        Coste Final (€)
                    </label>
                    <input
                        type="number"
                        id="final_cost"
                        wire:model="final_cost"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                    >
                    @error('final_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Duración Estimada --}}
            <div>
                <label for="estimated_duration" class="block text-sm font-medium text-zinc-700 mb-2">
                    Duración Estimada (minutos)
                </label>
                <input
                    type="number"
                    id="estimated_duration"
                    wire:model="estimated_duration"
                    min="0"
                    placeholder="60"
                    class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                >
                @error('estimated_duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notas --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-zinc-700 mb-2">
                    Notas Adicionales
                </label>
                <textarea
                    id="notes"
                    wire:model="notes"
                    rows="2"
                    placeholder="Notas internas..."
                    class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm"
                ></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="flex items-center justify-end space-x-3">
            <button
                type="button"
                wire:click="cancel"
                class="glass-btn-secondary px-5 py-2.5 text-sm"
            >
                Cancelar
            </button>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex items-center px-5 py-2.5 bg-zinc-900 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-zinc-800 focus:bg-zinc-800 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 shadow-lg shadow-zinc-900/10 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">{{ $appointmentId ? 'Actualizar Cita' : 'Crear Cita' }}</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>
