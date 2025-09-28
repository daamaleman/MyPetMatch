<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MyPetMatch') }}</title>

        {{-- Fonts: se cargan vía @fontsource en resources/js/app.js --}}
        {{-- <link rel="preconnect" href="https://fonts.bunny.net"> --}}

        {{-- Scripts / Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
        <div class="min-h-screen">
            @include('layouts.navigation')

            {{-- Page Heading --}}
            @if (isset($header))
                <header class="bg-white/90 dark:bg-neutral-dark/80 backdrop-blur border-b border-neutral-mid/40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
