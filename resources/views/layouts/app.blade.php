<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Workshop CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script type="importmap">
    {
        "imports": {
            "@fullcalendar/core": "https://esm.sh/@fullcalendar/core@6.1.20",
            "@fullcalendar/daygrid": "https://esm.sh/@fullcalendar/daygrid@6.1.20",
            "@fullcalendar/timegrid": "https://esm.sh/@fullcalendar/timegrid@6.1.20",
            "@fullcalendar/interaction": "https://esm.sh/@fullcalendar/interaction@6.1.20",
            "@fullcalendar/list": "https://esm.sh/@fullcalendar/list@6.1.20"
        }
    }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @livewireStyles
</head>
<body class="text-zinc-900 h-screen flex overflow-hidden selection:bg-violet-900 selection:text-white antialiased" style="background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 50%, #c7d2fe 100%); background-attachment: fixed;">

    <!-- Sidebar (Desktop) -->
    <aside class="w-72 glass-sidebar hidden md:flex flex-col p-6">
        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-8 h-8 bg-zinc-900 rounded-lg flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight leading-none">Workshop<span class="text-zinc-400">CRM</span></h1>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-2">
            <p class="px-4 text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2 mt-2">Menú</p>

            <a
                href="{{ route('dashboard') }}"
                @class([
                    'w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
                    'bg-white/70 backdrop-blur-sm shadow-sm text-zinc-900' => request()->routeIs('dashboard'),
                    'text-zinc-500 hover:bg-white/50 hover:text-zinc-900' => !request()->routeIs('dashboard')
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Dashboard
                </div>
            </a>

            <a
                href="{{ route('clients.index') }}"
                @class([
                    'w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
                    'bg-white/70 backdrop-blur-sm shadow-sm text-zinc-900' => request()->routeIs('clients.*'),
                    'text-zinc-500 hover:bg-white/50 hover:text-zinc-900' => !request()->routeIs('clients.*')
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Clientes
                </div>
            </a>

            <a
                href="{{ route('vehicles.index') }}"
                @class([
                    'w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
                    'bg-white/70 backdrop-blur-sm shadow-sm text-zinc-900' => request()->routeIs('vehicles.*'),
                    'text-zinc-500 hover:bg-white/50 hover:text-zinc-900' => !request()->routeIs('vehicles.*')
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                    </svg>
                    Vehículos
                </div>
            </a>

            <a
                href="{{ route('appointments.index') }}"
                @class([
                    'w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group',
                    'bg-white/70 backdrop-blur-sm shadow-sm text-zinc-900' => request()->routeIs('appointments.*'),
                    'text-zinc-500 hover:bg-white/50 hover:text-zinc-900' => !request()->routeIs('appointments.*')
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Citas
                </div>
            </a>

            <div class="mt-8">
                <p class="px-4 text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Acciones</p>
                <a
                    href="{{ route('clients.create') }}"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl border border-zinc-200 border-dashed text-zinc-500 hover:border-zinc-400 hover:text-zinc-900 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nuevo Cliente</span>
                </a>
            </div>
        </nav>

        {{-- User Profile --}}
        <div class="mt-auto pt-6 border-t border-zinc-100">
            <div class="flex items-center gap-3 px-2">
                <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-zinc-400 truncate">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-zinc-400 hover:text-zinc-900 transition-colors" title="Cerrar sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="max-w-[1200px] mx-auto p-8">

            <!-- Mobile Header -->
            <div class="md:hidden mb-8 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-zinc-900 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-lg">Workshop</span>
                </div>
            </div>

            <!-- Page Content -->
            <div class="fade-in">
                {{ $slot }}
            </div>

        </div>
    </main>

    <!-- Notifications Component -->
    <x-notifications />

    @livewireScripts
    @stack('scripts')
</body>
</html>
