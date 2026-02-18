<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Resumen General</h2>
            <p class="text-zinc-500 mt-1">Estado actual del taller.</p>
        </div>
        <span class="px-3 py-1 glass-panel rounded-full text-xs font-medium text-zinc-600">
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center justify-between">
            <div>
                <p class="text-zinc-500 text-xs font-medium uppercase tracking-wider mb-1">Total Vehículos</p>
                <h3 class="text-3xl font-bold text-zinc-900 tracking-tight">{{ $this->totalVehicles }}</h3>
            </div>
            <div class="p-3 rounded-2xl bg-zinc-50 text-zinc-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                </svg>
            </div>
        </div>

        <div class="glass-card p-5 flex items-center justify-between">
            <div>
                <p class="text-zinc-500 text-xs font-medium uppercase tracking-wider mb-1">ITV Caducadas</p>
                <h3 class="text-3xl font-bold text-zinc-900 tracking-tight">{{ $this->expiredCount }}</h3>
            </div>
            <div class="p-3 rounded-2xl bg-red-50 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>

        <div class="glass-card p-5 flex items-center justify-between">
            <div>
                <p class="text-zinc-500 text-xs font-medium uppercase tracking-wider mb-1">Próx. Vencimiento</p>
                <h3 class="text-3xl font-bold text-zinc-900 tracking-tight">{{ $this->warningCount }}</h3>
            </div>
            <div class="p-3 rounded-2xl bg-amber-50 text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="glass-card p-5 flex items-center justify-between">
            <div>
                <p class="text-zinc-500 text-xs font-medium uppercase tracking-wider mb-1">En Regla</p>
                <h3 class="text-3xl font-bold text-zinc-900 tracking-tight">{{ $this->validCount }}</h3>
            </div>
            <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Citas: Hoy (Google Calendar) + Mañana --}}
    <div class="grid gap-6" style="grid-template-columns: repeat(2, minmax(0, 1fr))">

        {{-- Citas de Hoy — estilo Google Calendar día --}}
        <div class="glass-card overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="text-center bg-emerald-50 rounded-xl px-3 py-1.5 min-w-[52px]">
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider leading-none">
                            {{ now()->isoFormat('ddd') }}
                        </p>
                        <p class="text-2xl font-bold text-emerald-700 leading-tight">{{ now()->format('j') }}</p>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Citas de Hoy</h3>
                        <p class="text-xs text-zinc-400">{{ now()->isoFormat('MMMM YYYY') }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-semibold">
                    {{ $this->todayAppointments->count() }}
                </span>
            </div>

            {{-- Time Grid --}}
            @php
                $startHour = 8;
                $endHour   = 20;
                $baseSlot  = 64; // altura base por hora en px

                // Calcular máximo de citas simultáneas para escalar la altura del slot
                $maxSim = 1;
                if ($this->todayAppointments->count() > 1) {
                    $evts = $this->todayAppointments->map(fn($a) => [
                        'start' => $a->appointment_date->timestamp,
                        'end'   => $a->appointment_date->timestamp + (($a->estimated_duration ?? 60) * 60),
                    ])->toArray();
                    foreach ($evts as $ev) {
                        $cnt = count(array_filter($evts, fn($o) => $o['start'] < $ev['end'] && $o['end'] > $ev['start']));
                        $maxSim = max($maxSim, $cnt);
                    }
                }
                // Si hay más de 3 simultáneas, agrandar el espacio entre horas
                $slotPx = $maxSim > 3 ? $baseSlot * (int)ceil($maxSim / 3) : $baseSlot;
                $totalH = ($endHour - $startHour) * $slotPx;
            @endphp

            <div class="overflow-y-auto flex-1" style="max-height: 448px;">
                @if($this->todayAppointments->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full py-16 text-zinc-300">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-zinc-400">Sin citas para hoy</p>
                    </div>
                @else
                    <div class="relative px-2 pt-2 pb-4" style="height: {{ $totalH + 16 }}px;">

                        {{-- Hour rows --}}
                        @for($h = $startHour; $h <= $endHour; $h++)
                            <div class="absolute left-0 right-0 flex pointer-events-none select-none"
                                 style="top: {{ ($h - $startHour) * $slotPx }}px; height: {{ $slotPx }}px;">
                                <span class="text-[10px] text-zinc-300 w-11 text-right pr-2.5 -mt-2 shrink-0 font-mono">
                                    {{ sprintf('%02d', $h) }}:00
                                </span>
                                <div class="flex-1 border-t border-zinc-100"></div>
                            </div>
                        @endfor

                        {{-- Appointment blocks --}}
                        @php
                            // Asignar columnas para evitar solapamiento (estilo Google Calendar)
                            $apptData = [];
                            foreach ($this->todayAppointments as $appt) {
                                $startTs = $appt->appointment_date->timestamp;
                                $durMin  = $appt->estimated_duration ?? 60;
                                $endTs   = $startTs + ($durMin * 60);
                                $apptData[] = ['appt' => $appt, 'start' => $startTs, 'end' => $endTs, 'col' => 0, 'totalCols' => 1];
                            }
                            // Greedy column placement
                            $colEnds = [];
                            foreach ($apptData as &$item) {
                                $placed = false;
                                foreach ($colEnds as $c => $endTs) {
                                    if ($endTs <= $item['start']) {
                                        $item['col'] = $c;
                                        $colEnds[$c] = $item['end'];
                                        $placed = true;
                                        break;
                                    }
                                }
                                if (!$placed) {
                                    $item['col'] = count($colEnds);
                                    $colEnds[] = $item['end'];
                                }
                            }
                            unset($item);
                            // Calcular totalCols según todos los solapamientos
                            foreach ($apptData as &$item) {
                                $maxCol = 0;
                                foreach ($apptData as $other) {
                                    if ($other['start'] < $item['end'] && $other['end'] > $item['start']) {
                                        $maxCol = max($maxCol, $other['col']);
                                    }
                                }
                                $item['totalCols'] = $maxCol + 1;
                            }
                            unset($item);
                        @endphp
                        {{-- Citas posicionadas directamente (sin contenedor intermedio) --}}
                        @foreach($apptData as $item)
                            @php
                                $appointment = $item['appt'];
                                $ah       = (int) $appointment->appointment_date->format('G');
                                $am       = (int) $appointment->appointment_date->format('i');
                                $topPx    = (($ah - $startHour) * $slotPx) + (($am / 60) * $slotPx);
                                $durMin   = $appointment->estimated_duration ?? 60;
                                $hPx      = max(44, ($durMin / 60) * $baseSlot);
                                $hex      = $appointment->getBadgeColor();
                                $colCount = $item['totalCols'];
                                $colIdx   = $item['col'];
                            @endphp
                            {{--
                                left: tiempo_etiqueta (2.75rem) + columna * ancho_columna + gap
                                width: (espacio_total - etiqueta) / total_columnas - gap
                            --}}
                            <div wire:key="gcal-{{ $appointment->id }}"
                                 wire:click="editAppointment({{ $appointment->id }})"
                                 class="absolute rounded-lg px-2 py-1.5 cursor-pointer transition-all hover:opacity-90 overflow-hidden"
                                 style="top: {{ $topPx }}px;
                                        min-height: {{ $hPx }}px;
                                        left: calc(3.25rem + {{ $colIdx }} * (100% - 3.25rem) / {{ $colCount }} + 4px);
                                        width: calc((100% - 3.25rem) / {{ $colCount }} - 8px);
                                        background-color: {{ $hex }}1a;
                                        border-left: 3px solid {{ $hex }};">
                                <div class="flex items-start justify-between gap-1">
                                    <p class="font-semibold text-zinc-900 text-xs leading-tight truncate">
                                        {{ $appointment->vehicle->client->name ?? '—' }}
                                    </p>
                                    <button wire:click.stop="deleteAppointment({{ $appointment->id }})"
                                            class="shrink-0 text-red-400 hover:text-red-600 transition-colors -mt-0.5"
                                            title="Eliminar cita">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-zinc-500 text-[11px] truncate mt-0.5">
                                    {{ $appointment->appointment_date->format('H:i') }}
                                    · {{ $appointment->vehicle->plate }}
                                    · {{ $appointment->getServiceTypeLabel() }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Citas de Mañana --}}
        <div class="glass-card overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="text-center bg-blue-50 rounded-xl px-3 py-1.5 min-w-[52px]">
                        <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider leading-none">
                            {{ now()->addDay()->isoFormat('ddd') }}
                        </p>
                        <p class="text-2xl font-bold text-blue-600 leading-tight">{{ now()->addDay()->format('j') }}</p>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Citas de Mañana</h3>
                        <p class="text-xs text-zinc-400">{{ now()->addDay()->isoFormat('MMMM YYYY') }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold">
                    {{ $this->tomorrowAppointments->count() }}
                </span>
            </div>

            @php
                $tallerServiceTypes  = ['revision', 'reparacion', 'diagnostico', 'mantenimiento', 'otro'];
                $tmTallerAppts       = $this->tomorrowAppointments->filter(fn($a) => in_array($a->service_type, $tallerServiceTypes))->values();
                $tmItvAppts          = $this->tomorrowAppointments->filter(fn($a) => $a->service_type === 'itv')->values();
                $tmTallerAprobadas   = $tmTallerAppts->where('status', 'aprobada')->values();
                $tmTallerReagendadas = $tmTallerAppts->where('status', 'reagendada')->values();
                $tmTallerCanceladas  = $tmTallerAppts->where('status', 'cancelada')->values();
                $tmItvAprobadas      = $tmItvAppts->where('status', 'aprobada')->values();
                $tmItvReagendadas    = $tmItvAppts->where('status', 'reagendada')->values();
                $tmItvCanceladas     = $tmItvAppts->where('status', 'cancelada')->values();
            @endphp

            <div class="overflow-y-auto flex-1 p-4 space-y-4" style="max-height: 448px;"
                 x-data="{ open: null }">

                @if($this->tomorrowAppointments->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-zinc-300">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-zinc-400">Sin citas para mañana</p>
                    </div>
                @else

                    {{-- ── TALLER ── --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h4 class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Taller</h4>
                            <span class="ml-auto text-[11px] text-zinc-400">{{ $tmTallerAppts->count() }} {{ $tmTallerAppts->count() === 1 ? 'cita' : 'citas' }}</span>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <button @click="open = open === 'taller-aprobada' ? null : 'taller-aprobada'"
                                    :class="open === 'taller-aprobada' ? 'ring-2 ring-emerald-300 bg-emerald-50' : 'bg-zinc-50 hover:bg-emerald-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-emerald-600 leading-none">{{ $tmTallerAprobadas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Aprobadas</span>
                            </button>
                            <button @click="open = open === 'taller-reagendada' ? null : 'taller-reagendada'"
                                    :class="open === 'taller-reagendada' ? 'ring-2 ring-blue-300 bg-blue-50' : 'bg-zinc-50 hover:bg-blue-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-blue-600 leading-none">{{ $tmTallerReagendadas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Reagendadas</span>
                            </button>
                            <button @click="open = open === 'taller-cancelada' ? null : 'taller-cancelada'"
                                    :class="open === 'taller-cancelada' ? 'ring-2 ring-red-300 bg-red-50' : 'bg-zinc-50 hover:bg-red-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-red-500 leading-none">{{ $tmTallerCanceladas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Canceladas</span>
                            </button>
                        </div>

                        {{-- Lista Taller Aprobadas --}}
                        <div x-show="open === 'taller-aprobada'" x-transition class="rounded-xl border border-emerald-100 overflow-hidden">
                            @forelse($tmTallerAprobadas as $appt)
                                <div wire:key="tma-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-emerald-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }} · {{ $appt->getServiceTypeLabel() }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas aprobadas de taller</p>
                            @endforelse
                        </div>

                        {{-- Lista Taller Reagendadas --}}
                        <div x-show="open === 'taller-reagendada'" x-transition class="rounded-xl border border-blue-100 overflow-hidden">
                            @forelse($tmTallerReagendadas as $appt)
                                <div wire:key="tmr-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-blue-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }} · {{ $appt->getServiceTypeLabel() }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas reagendadas de taller</p>
                            @endforelse
                        </div>

                        {{-- Lista Taller Canceladas --}}
                        <div x-show="open === 'taller-cancelada'" x-transition class="rounded-xl border border-red-100 overflow-hidden">
                            @forelse($tmTallerCanceladas as $appt)
                                <div wire:key="tmc-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-red-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }} · {{ $appt->getServiceTypeLabel() }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas canceladas de taller</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="border-t border-zinc-100"></div>

                    {{-- ── ITV ── --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <h4 class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">ITV</h4>
                            <span class="ml-auto text-[11px] text-zinc-400">{{ $tmItvAppts->count() }} {{ $tmItvAppts->count() === 1 ? 'cita' : 'citas' }}</span>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <button @click="open = open === 'itv-aprobada' ? null : 'itv-aprobada'"
                                    :class="open === 'itv-aprobada' ? 'ring-2 ring-emerald-300 bg-emerald-50' : 'bg-zinc-50 hover:bg-emerald-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-emerald-600 leading-none">{{ $tmItvAprobadas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Aprobadas</span>
                            </button>
                            <button @click="open = open === 'itv-reagendada' ? null : 'itv-reagendada'"
                                    :class="open === 'itv-reagendada' ? 'ring-2 ring-blue-300 bg-blue-50' : 'bg-zinc-50 hover:bg-blue-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-blue-600 leading-none">{{ $tmItvReagendadas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Reagendadas</span>
                            </button>
                            <button @click="open = open === 'itv-cancelada' ? null : 'itv-cancelada'"
                                    :class="open === 'itv-cancelada' ? 'ring-2 ring-red-300 bg-red-50' : 'bg-zinc-50 hover:bg-red-50'"
                                    class="flex flex-col items-center py-3 px-1 rounded-xl border border-zinc-100 transition-all cursor-pointer">
                                <span class="text-2xl font-bold text-red-500 leading-none">{{ $tmItvCanceladas->count() }}</span>
                                <span class="text-[10px] text-zinc-500 mt-1 leading-tight">Canceladas</span>
                            </button>
                        </div>

                        {{-- Lista ITV Aprobadas --}}
                        <div x-show="open === 'itv-aprobada'" x-transition class="rounded-xl border border-emerald-100 overflow-hidden">
                            @forelse($tmItvAprobadas as $appt)
                                <div wire:key="iva-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-emerald-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas ITV aprobadas</p>
                            @endforelse
                        </div>

                        {{-- Lista ITV Reagendadas --}}
                        <div x-show="open === 'itv-reagendada'" x-transition class="rounded-xl border border-blue-100 overflow-hidden">
                            @forelse($tmItvReagendadas as $appt)
                                <div wire:key="ivr-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-blue-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas ITV reagendadas</p>
                            @endforelse
                        </div>

                        {{-- Lista ITV Canceladas --}}
                        <div x-show="open === 'itv-cancelada'" x-transition class="rounded-xl border border-red-100 overflow-hidden">
                            @forelse($tmItvCanceladas as $appt)
                                <div wire:key="ivc-{{ $appt->id }}"
                                     class="flex items-center gap-3 px-3 py-2.5 bg-white hover:bg-red-50/50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <span class="text-xs font-mono text-zinc-400 shrink-0">{{ $appt->appointment_date->format('H:i') }}</span>
                                    <div class="flex-1 min-w-0 cursor-pointer" wire:click="editAppointment({{ $appt->id }})">
                                        <p class="text-sm font-medium text-zinc-900 truncate">{{ $appt->vehicle->client->name ?? '—' }}</p>
                                        <p class="text-[11px] text-zinc-400 font-mono truncate">{{ $appt->vehicle->plate }}</p>
                                    </div>
                                    <button wire:click.stop="deleteAppointment({{ $appt->id }})" class="shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-zinc-400 text-center py-3 bg-white">Sin citas ITV canceladas</p>
                            @endforelse
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>

    {{-- Calendario del Mes --}}
    <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900">Calendario del Mes</h3>
                    <p class="text-xs text-zinc-500">{{ now()->isoFormat('MMMM YYYY') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="createAppointment" class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                    Nueva Cita
                </button>
                <button wire:click="goToAppointments" class="px-4 py-2 border border-zinc-200 text-zinc-700 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                    Ver Calendario →
                </button>
            </div>
        </div>
        <div wire:ignore id="dashboard-calendar" class="min-h-[500px]"></div>
    </div>

    {{-- ITV: Caducadas + Próximos 30 días --}}
    <div class="grid md:grid-cols-2 gap-8">
        <div class="space-y-4">
            <h3 class="font-semibold text-lg flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                ITV Caducadas
            </h3>
            <div class="glass-card overflow-hidden min-h-[300px]">
                @forelse($this->expiredVehicles as $vehicle)
                    <div wire:key="expired-{{ $vehicle->id }}" class="p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between group border-b border-zinc-50 last:border-0">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 font-bold text-sm shrink-0">!</div>
                            <div>
                                <p class="font-semibold text-zinc-900">{{ $vehicle->client->name ?? 'Sin cliente' }}</p>
                                <p class="text-xs text-zinc-500">
                                    {{ $vehicle->car }} <span class="text-zinc-300">·</span> {{ $vehicle->plate }}
                                </p>
                            </div>
                        </div>
                        <button
                            wire:click="sendNotification({{ $vehicle->id }})"
                            class="px-3 py-1.5 text-xs font-medium border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors">
                            Notificar
                        </button>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400"><p>Todo en orden</p></div>
                @endforelse
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="font-semibold text-lg flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                Próximos 30 Días
            </h3>
            <div class="glass-card overflow-hidden min-h-[300px]">
                @forelse($this->warningVehicles as $vehicle)
                    <div wire:key="warning-{{ $vehicle->id }}" class="p-4 hover:bg-zinc-50 transition-colors flex items-center justify-between border-b border-zinc-50 last:border-0">
                        <div>
                            <p class="font-medium text-zinc-900">{{ $vehicle->client->name ?? 'Sin cliente' }}</p>
                            <p class="text-xs text-zinc-500">{{ $vehicle->car }} · {{ $vehicle->plate }}</p>
                            <p class="text-xs text-amber-600 font-medium mt-0.5">
                                Vence el {{ $vehicle->getFormattedItvDate() }}
                            </p>
                        </div>
                        <button
                            wire:click="sendNotification({{ $vehicle->id }})"
                            class="text-zinc-400 hover:text-zinc-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400"><p>Sin vencimientos cercanos</p></div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        import { Calendar } from '@fullcalendar/core';
        import dayGridPlugin from '@fullcalendar/daygrid';
        import interactionPlugin from '@fullcalendar/interaction';

        document.addEventListener('livewire:initialized', () => {
            const calendarEl = document.getElementById('dashboard-calendar');
            if (calendarEl) {
                const calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
                    buttonText: { today: 'Hoy' },
                    events: @js($this->monthEvents),
                    eventClick: function(info) { @this.editAppointment(info.event.id); },
                    height: 'auto',
                    contentHeight: 500,
                });
                calendar.render();
            }
        });
    </script>
    @endpush
</div>
