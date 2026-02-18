{{-- Toast Notifications System --}}
<div
    x-data="{
        notifications: [],
        add(type, message) {
            const id = Date.now();
            this.notifications.push({ id, type, message });
            setTimeout(() => this.remove(id), 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @vehicle-created.window="add('success', 'Vehículo creado correctamente')"
    @vehicle-updated.window="add('success', 'Vehículo actualizado correctamente')"
    @vehicle-deleted.window="add('success', 'Vehículo eliminado correctamente')"
    @notify.window="add($event.detail.type || 'info', $event.detail.message || 'Notificación')"
    class="fixed top-4 right-4 z-50 space-y-2"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-center gap-3 min-w-[320px] p-4 rounded-xl shadow-lg border"
            :class="{
                'bg-green-50 border-green-200 text-green-800': notification.type === 'success',
                'bg-red-50 border-red-200 text-red-800': notification.type === 'error',
                'bg-blue-50 border-blue-200 text-blue-800': notification.type === 'info',
                'bg-amber-50 border-amber-200 text-amber-800': notification.type === 'warning'
            }"
        >
            <div class="flex-shrink-0">
                <template x-if="notification.type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </template>
            </div>
            <p class="flex-1 text-sm font-medium" x-text="notification.message"></p>
            <button
                @click="remove(notification.id)"
                class="flex-shrink-0 text-current opacity-70 hover:opacity-100 transition-opacity"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- Delete Confirmation Modal --}}
<div
    x-data="{
        show: false,
        deleteAction: null
    }"
    @open-delete-modal.window="show = true"
    @close-delete-modal.window="show = false"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="fixed inset-0 bg-zinc-900/50 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div class="flex min-h-screen items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
        >
            {{-- Icon --}}
            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            {{-- Content --}}
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-zinc-900 mb-2">¿Eliminar vehículo?</h3>
                <p class="text-sm text-zinc-500">Esta acción no se puede deshacer. El vehículo será eliminado permanentemente.</p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button
                    @click="show = false; $dispatch('cancel-delete')"
                    type="button"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-zinc-700 bg-zinc-100 rounded-xl hover:bg-zinc-200 transition-colors"
                >
                    Cancelar
                </button>
                <button
                    @click="$dispatch('confirm-delete')"
                    type="button"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors"
                >
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
