<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MyPetMatch') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .6s ease-out both;
        }
    </style>
</head>

<body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1 flex items-center justify-center py-12">
        <div class="w-full sm:max-w-md mx-auto px-6 py-6 bg-white/90 dark:bg-neutral-dark/80 rounded-2xl shadow-card border border-neutral-mid/30 animate-fade-up">
            {{ $slot }}
        </div>
    </main>

    @include('partials.footer')
</body>

</html>