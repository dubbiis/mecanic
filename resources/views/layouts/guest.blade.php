<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>

        @livewireStyles
    </head>
    <body class="text-zinc-900 antialiased min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
          style="background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 50%, #c7d2fe 100%); background-attachment: fixed;">

        <div class="mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-zinc-900 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="font-bold text-2xl tracking-tight leading-none">Workshop<span class="text-zinc-400">CRM</span></h1>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-6 py-8 glass-card">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
