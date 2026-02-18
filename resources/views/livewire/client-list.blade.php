<div class="space-y-6" @confirm-delete.window="$wire.deleteClient()" @cancel-delete.window="$wire.cancelDelete()">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900">Clientes</h2>
            <p class="text-sm text-zinc-400 mt-0.5">Gestión del listado de clientes</p>
        </div>
        <button
            wire:click="create"
            class="flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-medium rounded-xl hover:bg-zinc-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo cliente
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-zinc-100 shadow-sm">
        <div class="relative w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar por nombre, teléfono, email o DNI..."
                class="w-full pl-10 pr-4 py-2 bg-zinc-50 border-none rounded-xl focus:ring-2 focus:ring-zinc-900/10 outline-none text-sm placeholder:text-zinc-400"
            />
        </div>
    </div>

    {{-- Loading State --}}
    <div wire:loading class="text-center py-4">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-zinc-900"></div>
    </div>

    {{-- Clients Table --}}
    <div wire:loading.remove class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-left text-sm text-zinc-600">
            <thead class="bg-zinc-50/50 text-zinc-400 font-medium text-xs uppercase tracking-wider border-b border-zinc-100">
                <tr>
                    <th class="p-5 font-medium">Cliente</th>
                    <th class="p-5 font-medium">Contacto</th>
                    <th class="p-5 font-medium">DNI/NIF</th>
                    <th class="p-5 font-medium text-center">Vehículos</th>
                    <th class="p-5 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($this->clients as $client)
                    <tr wire:key="client-{{ $client->id }}"
                        wire:click="profile({{ $client->id }})"
                        class="hover:bg-zinc-50/80 transition-colors group border-b border-zinc-50 last:border-0 cursor-pointer">
                        <td class="p-5">
                            <div class="font-semibold text-zinc-900">{{ $client->name }}</div>
                            @if($client->address)
                                <div class="text-xs text-zinc-400 mt-0.5 truncate max-w-[200px]">{{ $client->address }}</div>
                            @endif
                        </td>
                        <td class="p-5">
                            <div class="text-zinc-800 font-mono text-xs">{{ $client->phone }}</div>
                            @if($client->email)
                                <div class="text-xs text-zinc-400 mt-0.5">{{ $client->email }}</div>
                            @endif
                        </td>
                        <td class="p-5">
                            @if($client->dni_nif)
                                <span class="px-2 py-0.5 bg-zinc-100 rounded text-xs font-mono text-zinc-600 border border-zinc-200">
                                    {{ $client->dni_nif }}
                                </span>
                            @else
                                <span class="text-zinc-300">—</span>
                            @endif
                        </td>
                        <td class="p-5 text-center">
                            <span class="text-sm font-semibold {{ $client->vehicles_count > 0 ? 'text-zinc-900' : 'text-zinc-300' }}">
                                {{ $client->vehicles_count }}
                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                <button
                                    wire:click.stop="edit({{ $client->id }})"
                                    class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button
                                    wire:click.stop="confirmDelete({{ $client->id }})"
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
                        <td colspan="5" class="p-12 text-center text-zinc-400">
                            @if($search)
                                No se encontraron clientes para "{{ $search }}"
                            @else
                                No hay clientes registrados.
                                <br>
                                <button wire:click="create" class="mt-3 text-sm font-medium text-zinc-900 underline underline-offset-2">
                                    Añadir el primero
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-data="{ open: false }"
        @open-delete-modal.window="open = true"
        @close-delete-modal.window="open = false"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
        style="display: none;">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            @click.outside="$dispatch('cancel-delete')"
            class="bg-white rounded-2xl shadow-xl border border-zinc-100 p-6 max-w-sm w-full mx-4">
            <h3 class="font-semibold text-zinc-900 text-lg">Eliminar cliente</h3>
            <p class="text-sm text-zinc-500 mt-2">Esta acción eliminará el cliente permanentemente. Sus vehículos quedarán sin propietario asignado.</p>
            <div class="flex gap-3 mt-6 justify-end">
                <button
                    @click="$dispatch('cancel-delete')"
                    class="px-4 py-2 text-sm font-medium text-zinc-600 bg-zinc-100 rounded-xl hover:bg-zinc-200 transition-colors">
                    Cancelar
                </button>
                <button
                    @click="$dispatch('confirm-delete')"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
