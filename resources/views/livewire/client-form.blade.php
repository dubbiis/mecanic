<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <button wire:click="cancel" class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <div>
            <h2 class="text-xl font-bold text-zinc-900">
                {{ $clientId ? 'Editar cliente' : 'Nuevo cliente' }}
            </h2>
            <p class="text-sm text-zinc-400 mt-0.5">
                {{ $clientId ? 'Actualiza los datos del cliente' : 'Añade un nuevo cliente al sistema' }}
            </p>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="glass-card p-6 space-y-5">

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                Nombre completo <span class="text-red-500">*</span>
            </label>
            <input
                wire:model="name"
                type="text"
                placeholder="Juan García López"
                class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors"
            />
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="phone"
                    type="tel"
                    placeholder="612 345 678"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors"
                />
                @error('phone')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                    Email
                </label>
                <input
                    wire:model="email"
                    type="email"
                    placeholder="juan@ejemplo.com"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors"
                />
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- DNI/NIF + Address --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                    DNI / NIF
                </label>
                <input
                    wire:model="dni_nif"
                    type="text"
                    placeholder="12345678A"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors font-mono"
                />
                @error('dni_nif')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                    Dirección
                </label>
                <input
                    wire:model="address"
                    type="text"
                    placeholder="Calle Mayor 1, 28001 Madrid"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors"
                />
                @error('address')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1.5">
                Observaciones
            </label>
            <textarea
                wire:model="notes"
                rows="3"
                placeholder="Notas internas sobre el cliente..."
                class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-400 outline-none text-sm transition-colors resize-none"
            ></textarea>
            @error('notes')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3 pt-2 border-t border-zinc-100">
            <button
                type="button"
                wire:click="cancel"
                class="px-4 py-2 text-sm font-medium text-zinc-600 bg-zinc-100 rounded-xl hover:bg-zinc-200 transition-colors">
                Cancelar
            </button>
            <div class="flex items-center gap-2">
                @if(!$clientId)
                    <button
                        type="button"
                        wire:click="saveAndAddVehicle"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-700 bg-zinc-100 border border-zinc-200 rounded-xl hover:bg-zinc-200 transition-colors disabled:opacity-60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear y añadir vehículo
                    </button>
                @endif
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-5 py-2 text-sm font-medium bg-zinc-900 text-white rounded-xl hover:bg-zinc-700 transition-colors disabled:opacity-60">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    {{ $clientId ? 'Guardar cambios' : 'Crear cliente' }}
                </button>
            </div>
        </div>
    </form>
</div>
