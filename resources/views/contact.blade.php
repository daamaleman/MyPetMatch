<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacto — {{ config('app.name', 'MyPetMatch') }}</title>
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
            animation: fadeUp .7s ease-out both;
        }
    </style>
</head>

<body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1">
        <!-- Hero Contact -->
        <section class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="animate-fade-up">
                    <p class="text-sm uppercase tracking-wide text-primary">Hablemos</p>
                    <h1 class="mt-3 text-4xl md:text-5xl font-semibold leading-tight tracking-tight">
                        ¿Tienes preguntas? <span class="text-primary">Conversemos</span>
                    </h1>
                    <p class="mt-5 text-neutral-dark/80 dark:text-neutral-300 max-w-xl">
                        Escríbenos por nuestras redes o visita nuestra landing. ¡Nos encantará escucharte!
                    </p>
                    <div class="mt-7 flex items-center gap-3">
                        <a href="https://www.instagram.com/artemis_devs/" target="_blank" rel="noopener" class="btn btn-primary">Instagram de Artemis-Devs →</a>
                        <a href="https://didudi.lat/" target="_blank" rel="noopener" class="btn btn-secondary">Landing</a>
                    </div>
                </div>
                <div class="relative animate-fade-up" style="animation-delay:.05s">
                    <div class="rounded-2xl overflow-hidden shadow-card border border-neutral-mid/30 bg-white/70 dark:bg-neutral-dark/60 p-3 flex items-center justify-center">
                        <!-- Logo Artemis-Devs según modo -->
                        <picture>
                            <!-- Prefer dark logo when dark mode -->
                            <source srcset="{{ asset('storage/ARTEMIS-TRANSPARENTE-FONDOBLANCO.png') }}" media="(prefers-color-scheme: dark)">
                            <img src="{{ asset('storage/ARTEMIS-TRANSPARENTE-FONDOAZUL.png') }}" alt="Artemis-Devs" class="h-80 w-auto object-contain" />
                        </picture>
                    </div>
                    <div class="mt-1 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                        Artemis-Devs — Enseñar es la mejor forma de aprender.
                    </div>
                </div>
            </div>
        </section>

        <!-- Tarjetas de enlaces -->
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instagram: Diedereich Alemán -->
                    <a href="https://www.instagram.com/itsdaam_code/" target="_blank" rel="noopener" class="group bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-5 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">Instagram — Diedereich Alemán</h3>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">@itsdaam_code</p>
                            </div>
                            <span class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <!-- instagram icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                                    <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3zm11 1a1 1 0 100 2 1 1 0 000-2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-3 text-xs text-primary opacity-0 group-hover:opacity-100 transition">Abrir Instagram →</div>
                    </a>
                    <!-- Instagram: Diego Mora -->
                    <a href="https://www.instagram.com/dbeewms/" target="_blank" rel="noopener" class="group bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-5 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">Instagram — Diego Mora</h3>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">@dbeewms</p>
                            </div>
                            <span class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <!-- instagram icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                                    <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3zm11 1a1 1 0 100 2 1 1 0 000-2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-3 text-xs text-primary opacity-0 group-hover:opacity-100 transition">Abrir Instagram →</div>
                    </a>
                    <!-- GitHub: Diedereich -->
                    <a href="https://github.com/daamaleman" target="_blank" rel="noopener" class="group bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-5 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">GitHub — Diedereich Alemán</h3>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">github.com/daamaleman</p>
                            </div>
                            <span class="h-10 w-10 rounded-xl bg-neutral-mid/20 text-neutral-dark flex items-center justify-center dark:bg-neutral-dark/50 dark:text-neutral-white">
                                <!-- github icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                                    <path d="M12 2C6.477 2 2 6.486 2 12.021c0 4.424 2.865 8.18 6.839 9.504.5.092.682-.218.682-.486 0-.24-.009-.877-.014-1.721-2.782.606-3.369-1.342-3.369-1.342-.454-1.158-1.11-1.468-1.11-1.468-.909-.624.069-.611.069-.611 1.004.071 1.532 1.033 1.532 1.033.893 1.537 2.343 1.093 2.912.836.091-.651.35-1.094.636-1.346-2.221-.253-4.555-1.114-4.555-4.956 0-1.094.39-1.99 1.03-2.69-.103-.254-.447-1.274.098-2.654 0 0 .84-.27 2.75 1.027A9.564 9.564 0 0112 6.844c.85.004 1.706.115 2.507.338 1.909-1.297 2.748-1.027 2.748-1.027.546 1.38.202 2.4.099 2.654.64.7 1.029 1.596 1.029 2.69 0 3.852-2.337 4.701-4.566 4.95.359.31.678.92.678 1.855 0 1.34-.012 2.42-.012 2.75 0 .269.18.583.688.484A10.02 10.02 0 0022 12.02C22 6.486 17.523 2 12 2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-3 text-xs text-primary opacity-0 group-hover:opacity-100 transition">Abrir GitHub →</div>
                    </a>
                    <!-- GitHub: Diego -->
                    <a href="https://github.com/dbeewms" target="_blank" rel="noopener" class="group bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-5 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">GitHub — Diego Mora</h3>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">github.com/dbeewms</p>
                            </div>
                            <span class="h-10 w-10 rounded-xl bg-neutral-mid/20 text-neutral-dark flex items-center justify-center dark:bg-neutral-dark/50 dark:text-neutral-white">
                                <!-- github icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
                                    <path d="M12 2C6.477 2 2 6.486 2 12.021c0 4.424 2.865 8.18 6.839 9.504.5.092.682-.218.682-.486 0-.24-.009-.877-.014-1.721-2.782.606-3.369-1.342-3.369-1.342-.454-1.158-1.11-1.468-1.11-1.468-.909-.624.069-.611.069-.611 1.004.071 1.532 1.033 1.532 1.033.893 1.537 2.343 1.093 2.912.836.091-.651.35-1.094.636-1.346-2.221-.253-4.555-1.114-4.555-4.956 0-1.094.39-1.99 1.03-2.69-.103-.254-.447-1.274.098-2.654 0 0 .84-.27 2.75 1.027A9.564 9.564 0 0112 6.844c.85.004 1.706.115 2.507.338 1.909-1.297 2.748-1.027 2.748-1.027.546 1.38.202 2.4.099 2.654.64.7 1.029 1.596 1.029 2.69 0 3.852-2.337 4.701-4.566 4.95.359.31.678.92.678 1.855 0 1.34-.012 2.42-.012 2.75 0 .269.18.583.688.484A10.02 10.02 0 0022 12.02C22 6.486 17.523 2 12 2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-3 text-xs text-primary opacity-0 group-hover:opacity-100 transition">Abrir GitHub →</div>
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA final -->
        <section class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl p-6 md:p-10 animate-fade-up bg-white/80 dark:bg-neutral-dark/80 shadow-card border border-neutral-mid/30 flex flex-col md:flex-row items-end md:items-end justify-start gap-8" style="background: radial-gradient(1200px 400px at -10% -20%, rgba(5,112,108,0.10), transparent), radial-gradient(1200px 400px at 110% 120%, rgba(245,121,82,0.10), transparent);">
                <div class="flex-1">
                    <span class="badge badge-primary">Trabajemos juntos</span>
                    <h3 class="text-2xl md:text-3xl font-semibold mt-3 tracking-tight">¿Tienes una idea o proyecto?</h3>
                    <p class="text-sm md:text-base text-neutral-dark/80 dark:text-neutral-300 mt-2 max-w-2xl">Hablemos sobre cómo Artemis-Devs puede ayudarte a construirlo.</p>
                </div>
                <div class="flex flex-col items-start gap-4 flex-shrink-0">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Mini Card: Diedereich -->
                        <div class="flex items-center gap-3 bg-neutral-light/80 dark:bg-neutral-dark/60 border border-primary/20 rounded-xl p-4 shadow-sm min-w-[220px]">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                DA
                            </div>
                            <div>
                                <div class="font-semibold text-neutral-dark dark:text-neutral-white">Diedereich Alemán</div>
                                <a href="mailto:diedereicha@uamv.edu.ni" class="text-xs text-primary hover:underline">diedereicha@uamv.edu.ni</a>
                            </div>
                        </div>
                        <!-- Mini Card: Diego -->
                        <div class="flex items-center gap-3 bg-neutral-light/80 dark:bg-neutral-dark/60 border border-primary/20 rounded-xl p-4 shadow-sm min-w-[220px]">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                DM
                            </div>
                            <div>
                                <div class="font-semibold text-neutral-dark dark:text-neutral-white">Diego Mora</div>
                                <a href="mailto:difmora@uamv.edu.ni" class="text-xs text-primary hover:underline">difmora@uamv.edu.ni</a>
                            </div>
                        </div>
                        <!-- Mini Card: Escríbenos -->
                        <a href="https://www.instagram.com/artemis_devs/" target="_blank" rel="noopener"
                           class="flex items-center gap-3 bg-primary/90 hover:bg-primary text-white border border-primary/20 rounded-xl p-4 shadow-sm min-w-[220px] transition-all duration-200">
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-white">Escríbenos</div>
                                <span class="text-xs text-white/80">Instagram Artemis-Devs</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
                </div>
            </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>

</html>