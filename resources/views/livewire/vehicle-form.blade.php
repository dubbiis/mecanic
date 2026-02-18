<div class="max-w-2xl mx-auto">
    {{-- Back Button --}}
    <button
        wire:click="cancel"
        class="mb-6 flex items-center gap-2 text-zinc-500 hover:text-zinc-900 transition-colors text-sm font-medium">
        ← Volver a la lista
    </button>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-zinc-900">
            {{ $vehicleId ? 'Editar Vehículo' : 'Registrar Nuevo' }}
        </h1>
        <p class="text-zinc-500 mt-2">Asocia el vehículo a un cliente y completa sus datos.</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-zinc-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] p-6">
        <form wire:submit="save" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">

                {{-- Cliente Section --}}
                <div class="md:col-span-2 pb-2 border-b border-zinc-100">
                    <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Propietario</h3>
                </div>

                {{-- Client Selector --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-medium text-zinc-700">Cliente <span class="text-red-500">*</span></label>

                    @if($selectedClient)
                        {{-- Selected client display --}}
                        <div class="flex items-center justify-between p-3 bg-zinc-50 border border-zinc-200 rounded-xl">
                            <div>
                                <span class="font-semibold text-zinc-900 text-sm">{{ $selectedClient->name }}</span>
                                <span class="ml-2 text-xs text-zinc-400 font-mono">{{ $selectedClient->phone }}</span>
                            </div>
                            <button type="button" wire:click="clearClient" class="text-zinc-400 hover:text-zinc-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @else
                        {{-- Search input --}}
                        <div class="relative">
                            <input
                                wire:model.live.debounce.300ms="clientSearch"
                                type="text"
                                placeholder="Buscar cliente por nombre, teléfono o DNI..."
                                class="w-full p-3 bg-zinc-50 border rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all text-sm {{ $errors->has('selectedClientId') ? 'border-red-500' : 'border-zinc-200' }}"
                            />
                            {{-- Dropdown --}}
                            @if($clientResults->isNotEmpty())
                                <div class="absolute z-20 top-full mt-1 left-0 right-0 bg-white border border-zinc-100 rounded-xl shadow-lg overflow-hidden">
                                    @foreach($clientResults as $client)
                                        <button
                                            type="button"
                                            wire:click="selectClient({{ $client->id }})"
                                            class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-zinc-50 transition-colors border-b border-zinc-50 last:border-0">
                                            <div>
                                                <span class="text-sm font-medium text-zinc-900">{{ $client->name }}</span>
                                                @if($client->dni_nif)
                                                    <span class="ml-2 text-xs text-zinc-400 font-mono">{{ $client->dni_nif }}</span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-zinc-400 font-mono">{{ $client->phone }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen($clientSearch) > 0)
                                <div class="absolute z-20 top-full mt-1 left-0 right-0 bg-white border border-zinc-100 rounded-xl shadow-lg">
                                    <div class="px-4 py-3 text-sm text-zinc-400 text-center">
                                        No se encontraron clientes.
                                        <a href="{{ route('clients.create') }}" class="text-zinc-900 font-medium underline underline-offset-2 ml-1">Crear nuevo</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-400">
                            ¿El cliente no existe aún?
                            <a href="{{ route('clients.create') }}" class="text-zinc-700 font-medium underline underline-offset-2">Crear cliente primero</a>
                        </p>
                    @endif

                    @error('selectedClientId')
                        <p class="text-xs text-red-600">Debes seleccionar un cliente.</p>
                    @enderror
                </div>

                {{-- Vehículo Section --}}
                <div class="md:col-span-2 pb-2 border-b border-zinc-100 mt-2">
                    <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Datos del Vehículo</h3>
                </div>

                {{-- Marca y Modelo --}}
                <div class="space-y-2">
                    <label for="car" class="text-sm font-medium text-zinc-700">Marca y Modelo</label>
                    <input
                        wire:model="car"
                        type="text"
                        id="car"
                        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all @error('car') border-red-500 @enderror"
                        placeholder="Ej. Seat Ibiza"
                    />
                    @error('car')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Matrícula --}}
                <div class="space-y-2">
                    <label for="plate" class="text-sm font-medium text-zinc-700">Matrícula</label>
                    <input
                        wire:model="plate"
                        type="text"
                        id="plate"
                        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all font-mono uppercase @error('plate') border-red-500 @enderror"
                        placeholder="0000XXX"
                    />
                    @error('plate')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Bastidor (VIN) --}}
                <div class="space-y-2">
                    <label for="vin" class="text-sm font-medium text-zinc-700">Número de Bastidor (VIN)</label>
                    <input
                        wire:model="vin"
                        type="text"
                        id="vin"
                        maxlength="17"
                        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all font-mono uppercase @error('vin') border-red-500 @enderror"
                        placeholder="Opcional - máx. 17 caracteres"
                    />
                    @error('vin')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha ITV --}}
                <div class="space-y-2">
                    <label for="itv_date" class="text-sm font-medium text-zinc-700">Vencimiento ITV</label>
                    <input
                        wire:model="itv_date"
                        type="date"
                        id="itv_date"
                        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all @error('itv_date') border-red-500 @enderror"
                    />
                    @error('itv_date')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notas --}}
                <div class="md:col-span-2 space-y-2">
                    <label for="notes" class="text-sm font-medium text-zinc-700">Notas</label>
                    <textarea
                        wire:model="notes"
                        id="notes"
                        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all min-h-[100px] resize-none @error('notes') border-red-500 @enderror"
                        placeholder="Detalles adicionales..."
                    ></textarea>
                    @error('notes')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-between items-center pt-4">
                <button
                    type="button"
                    wire:click="cancel"
                    class="bg-zinc-100 text-zinc-700 hover:bg-zinc-200 px-5 py-2.5 text-sm rounded-xl font-medium transition-all">
                    Cancelar
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="bg-zinc-900 text-white hover:bg-zinc-800 shadow-lg shadow-zinc-900/10 px-5 py-2.5 text-sm rounded-xl font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">Guardar Datos</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>
