<div class="space-y-6">
    {{-- Header con título y botones --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-zinc-900">Calendario de Citas</h2>
            <p class="mt-1 text-sm text-zinc-600">Gestiona las citas y servicios del taller</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="showHistory" class="inline-flex items-center px-5 py-2.5 glass-btn-secondary font-semibold text-sm focus:outline-none transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Registro Completo
            </button>
            <button wire:click="create" class="inline-flex items-center px-5 py-2.5 bg-zinc-900 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-zinc-800 focus:bg-zinc-800 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 shadow-lg shadow-zinc-900/10 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Cita
            </button>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-zinc-900">Filtros</h3>
            @if($filterStatus || $filterServiceType)
                <button wire:click="clearFilters" class="text-sm text-zinc-600 hover:text-zinc-900">
                    Limpiar filtros
                </button>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Filtro por estado --}}
            <div>
                <label for="filterStatus" class="block text-sm font-medium text-zinc-700 mb-2">Estado</label>
                <select wire:model.live="filterStatus" id="filterStatus" class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por tipo de servicio --}}
            <div>
                <label for="filterServiceType" class="block text-sm font-medium text-zinc-700 mb-2">Tipo de Servicio</label>
                <select wire:model.live="filterServiceType" id="filterServiceType" class="w-full px-3 py-2.5 bg-white/60 border border-white/80 rounded-xl focus:ring-2 focus:ring-violet-200 focus:outline-none text-sm transition-all backdrop-blur-sm">
                    <option value="">Todos los servicios</option>
                    @foreach($serviceTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Citas de hoy --}}
    @if($this->todayAppointments->count() > 0)
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-zinc-900">Citas de Hoy ({{ $this->todayAppointments->count() }})</h3>
        </div>
        <div class="space-y-3">
            @foreach($this->todayAppointments as $appointment)
                <div class="bg-white rounded-lg p-4 flex items-center justify-between shadow-sm border border-amber-100">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->getStatusColor() }}">
                                {{ $appointment->getStatusLabel() }}
                            </span>
                            <span class="text-sm font-medium text-zinc-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</span>
                        </div>
                        <p class="mt-1 text-sm font-semibold text-zinc-900">{{ $appointment->vehicle->name }} - {{ $appointment->vehicle->plate }}</p>
                        <p class="text-sm text-zinc-600">{{ $this->serviceTypes[$appointment->service_type] ?? $appointment->service_type }}</p>
                        @if($appointment->description)
                            <p class="mt-1 text-xs text-zinc-500">{{ Str::limit($appointment->description, 60) }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="edit({{ $appointment->id }})" class="p-2 text-zinc-600 hover:text-zinc-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button wire:click="sendWhatsApp({{ $appointment->id }})" class="p-2 text-green-600 hover:text-green-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Calendario --}}
    <div class="glass-card p-6" wire:ignore>
        <div id="calendar"></div>
    </div>

    {{-- Modal de confirmación de eliminación --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelDelete"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-zinc-900">Eliminar Cita</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">¿Estás seguro de que deseas eliminar esta cita? Esta acción no se puede deshacer.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteAppointment" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Eliminar
                        </button>
                        <button wire:click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-zinc-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Registro Completo --}}
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-6 md:p-12">
                <div class="fixed inset-0 bg-zinc-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" wire:click="closeHistory"></div>

                <div class="relative glass-card transform transition-all w-full max-w-6xl max-h-[90vh] flex flex-col">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-12 py-8 border-b border-zinc-200">
                        <div>
                            <h3 class="text-2xl font-bold text-zinc-900">Registro Completo de Citas</h3>
                            <p class="text-sm text-zinc-500 mt-1">Visualiza y gestiona todas las citas del taller</p>
                        </div>
                        <button wire:click="closeHistory" class="text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-lg p-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Toggle Vista --}}
                    <div class="px-12 py-6 border-b border-zinc-100 bg-zinc-50">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex rounded-lg border border-zinc-200 bg-white p-1 shadow-sm">
                                <button
                                    wire:click="switchHistoryView('table')"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $historyView === 'table' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Tabla
                                </button>
                                <button
                                    wire:click="switchHistoryView('calendar')"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $historyView === 'calendar' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50' }}"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Calendario
                                </button>
                            </div>
                            <div class="text-sm text-zinc-600">
                                <span class="font-medium text-zinc-900">{{ $this->allAppointments->count() }}</span> citas registradas
                            </div>
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="flex-1 overflow-hidden px-12 py-8">
                        @if($historyView === 'table')
                            {{-- Vista de Tabla --}}
                            <div class="overflow-auto h-full border border-zinc-200 rounded-lg">
                                <table class="min-w-full divide-y divide-zinc-200">
                                    <thead class="bg-zinc-50 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Fecha</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Cliente</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Vehículo</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Servicio</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Coste</th>
                                            <th class="px-6 py-4 text-right text-xs font-semibold text-zinc-600 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-zinc-100">
                                        @forelse($this->allAppointments as $appointment)
                                            <tr wire:key="history-{{ $appointment->id }}" class="hover:bg-zinc-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-zinc-900">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                                    <div class="text-xs text-zinc-500">{{ $appointment->appointment_date->format('H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-zinc-900">{{ $appointment->vehicle->name }}</div>
                                                    <div class="text-xs text-zinc-500">{{ $appointment->vehicle->phone }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-zinc-900">{{ $appointment->vehicle->plate }}</div>
                                                    <div class="text-xs text-zinc-500">{{ $appointment->vehicle->car }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                                    {{ $this->serviceTypes[$appointment->service_type] ?? $appointment->service_type }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $appointment->getStatusColor() }}">
                                                        {{ $appointment->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900">
                                                    @if($appointment->final_cost)
                                                        {{ number_format($appointment->final_cost, 2) }}€
                                                    @elseif($appointment->estimated_cost)
                                                        <span class="text-zinc-500">~{{ number_format($appointment->estimated_cost, 2) }}€</span>
                                                    @else
                                                        <span class="text-zinc-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button wire:click="edit({{ $appointment->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            Editar
                                                        </button>
                                                        <button wire:click="confirmDelete({{ $appointment->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center text-zinc-400">
                                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No hay citas registradas</p>
                                                        <p class="text-sm mt-1">Crea tu primera cita para comenzar</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- Vista de Calendario --}}
                            <div class="h-full">
                                <div wire:ignore id="history-calendar" class="h-full"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-12 py-6 border-t border-zinc-200 bg-zinc-50">
                        <div class="text-sm text-zinc-600">
                            Mostrando <span class="font-medium text-zinc-900">{{ $this->allAppointments->count() }}</span> citas
                        </div>
                        <button wire:click="closeHistory" type="button" class="inline-flex items-center px-6 py-2.5 bg-white border border-zinc-300 rounded-lg text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 transition-colors shadow-sm">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts de FullCalendar --}}
    @push('scripts')
    <script type="module">
        import { Calendar } from '@fullcalendar/core';
        import dayGridPlugin from '@fullcalendar/daygrid';
        import timeGridPlugin from '@fullcalendar/timegrid';
        import interactionPlugin from '@fullcalendar/interaction';
        import listPlugin from '@fullcalendar/list';

        document.addEventListener('livewire:initialized', () => {
            const calendarEl = document.getElementById('calendar');
            let calendar = null;

            function initCalendar() {
                if (calendar) {
                    calendar.destroy();
                }

                calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día',
                        list: 'Lista'
                    },
                    editable: true,
                    droppable: true,
                    eventResizableFromStart: true,
                    events: @js($this->events),
                    eventClick: function(info) {
                        const event = info.event;
                        const props = event.extendedProps;

                        // Mostrar menú contextual con opciones
                        if (confirm('¿Qué deseas hacer?\n\nOK = Editar\nCancelar = Ver opciones')) {
                            @this.edit(event.id);
                        } else {
                            showEventOptions(event);
                        }
                    },
                    eventDrop: function(info) {
                        const newDate = info.event.start.toISOString();
                        @this.updateAppointmentDate(info.event.id, newDate);
                    },
                    eventResize: function(info) {
                        const newDate = info.event.start.toISOString();
                        @this.updateAppointmentDate(info.event.id, newDate);
                    },
                    height: 'auto',
                    contentHeight: 650,
                });

                calendar.render();
            }

            function showEventOptions(event) {
                const props = event.extendedProps;
                const options = [
                    'Editar',
                    'Cambiar a Aprobada',
                    'Cambiar a Reagendada',
                    'Cancelar Cita',
                    'Eliminar'
                ];

                // Simple prompt para demostración
                const choice = prompt(
                    `Cita: ${props.vehicle_name} - ${props.vehicle_plate}\n\n` +
                    `1. ${options[0]}\n2. ${options[1]}\n3. ${options[2]}\n4. ${options[3]}\n5. ${options[4]}\n\n` +
                    'Elige una opción (1-5):'
                );

                switch(choice) {
                    case '1':
                        @this.edit(event.id);
                        break;
                    case '2':
                        @this.changeStatus(event.id, 'aprobada');
                        break;
                    case '3':
                        @this.changeStatus(event.id, 'reagendada');
                        break;
                    case '4':
                        @this.changeStatus(event.id, 'cancelada');
                        break;
                    case '5':
                        @this.confirmDelete(event.id);
                        break;
                }
            }

            // Inicializar calendario
            initCalendar();

            // Recargar cuando se actualice
            Livewire.on('appointment-updated', () => {
                initCalendar();
            });

            // Escuchar cambios en filtros
            Livewire.hook('morph.updated', () => {
                setTimeout(() => initCalendar(), 100);
            });
        });

        // Calendario del historial completo
        let historyCalendar = null;

        function initHistoryCalendar() {
            const historyCalendarEl = document.getElementById('history-calendar');

            // Destruir calendario previo si existe
            if (historyCalendar) {
                historyCalendar.destroy();
                historyCalendar = null;
            }

            // Crear nuevo calendario si el elemento existe
            if (historyCalendarEl) {
                setTimeout(() => {
                    historyCalendar = new Calendar(historyCalendarEl, {
                        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,listMonth'
                        },
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Semana',
                            list: 'Lista'
                        },
                        height: '100%',
                        events: @js($this->events),
                        eventClick: function(info) {
                            @this.edit(info.event.id);
                        },
                        eventDrop: function(info) {
                            const newDate = info.event.start.toISOString();
                            @this.updateAppointmentDate(info.event.id, newDate);
                        },
                        editable: true,
                    });

                    historyCalendar.render();
                }, 100);
            }
        }

        // Escuchar cuando se actualicen las citas
        Livewire.on('appointment-updated', () => {
            if (historyCalendar) {
                historyCalendar.refetchEvents();
            }
        });

        // Inicializar cuando Livewire esté listo y cuando cambien las vistas
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                initHistoryCalendar();
            });
        });
    </script>
    @endpush
</div>
