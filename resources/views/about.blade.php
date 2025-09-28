<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sobre MyPetMatch — {{ config('app.name', 'MyPetMatch') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
        <style>
            @keyframes fadeUp { from { opacity: 0; transform: translateY(16px);} to { opacity: 1; transform: translateY(0);} }
            @keyframes floatY { 0% { transform: translateY(0);} 50% { transform: translateY(-8px);} 100% { transform: translateY(0);} }
            .animate-fade-up { animation: fadeUp .7s ease-out both; }
            .animate-float { animation: floatY 4s ease-in-out infinite; }
        </style>
    </head>
    <body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
        <!-- Top Bar -->
        <header class="border-b border-neutral-mid/30 bg-white/80 dark:bg-neutral-dark/70 backdrop-blur supports-[backdrop-filter]:bg-white/50 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 font-semibold">
                    <img src="{{ asset('storage/MyPetMatchLogo-Transparente.png') }}" alt="MyPetMatch" class="h-12 w-12 md:h-14 md:w-14 object-contain"/>
                    <span>MyPetMatch</span>
                </a>
                <nav class="hidden md:flex items-center gap-8 text-sm">
                    <a href="/" class="hover:text-primary">Inicio</a>
                    <a href="{{ route('about') }}" class="text-primary font-medium">Nosotros</a>
                    <a href="{{ route('features') }}" class="hover:text-primary">Cómo Funciona</a>
                    <a href="#contacto" class="hover:text-primary">Contacto</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm">Iniciar Sesión</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary text-sm">Registrarse</a>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1">
            @php
                $about2Path = 'storage/foto-perritos-mypetmatch-about-2.jpg';
            @endphp
            <!-- Hero About -->
            <section class="relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div class="animate-fade-up">
                        <p class="text-sm uppercase tracking-wide text-primary">Sobre Nosotros</p>
                        <h1 class="mt-3 text-4xl md:text-5xl font-semibold leading-tight tracking-tight">
                            Conectamos <span class="text-primary">Hogares</span> y <span class="text-secondary">Mascotas</span> con Propósito
                        </h1>
                        <p class="mt-5 text-neutral-dark/80 dark:text-neutral-300 max-w-xl">
                            MyPetMatch nace para facilitar adopciones responsables, transparentes y felices. Impulsamos refugios con herramientas modernas y damos a los adoptantes una experiencia clara y confiable.
                        </p>
                        <div class="mt-7 flex items-center gap-3">
                            <a href="{{ url('/pets') }}" class="btn btn-primary">Explorar Mascotas</a>
                            <a href="#mision" class="btn btn-secondary">Nuestra Misión</a>
                        </div>
                    </div>
                    <div class="relative animate-float">
                        <div class="rounded-2xl overflow-hidden shadow-card border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                            <img src="{{ asset($about2Path) }}" alt="Equipo y mascotas" class="w-full h-[360px] object-cover"/>
                        </div>
                        <div class="mt-2 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                            Photo by <a class="underline" href="https://unsplash.com/@pavannaikfcds?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Pavan Naik</a> on <a class="underline" href="https://unsplash.com/photos/a-couple-of-dogs-laying-on-top-of-a-dog-bed-96mPr-tsIAU?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Unsplash</a>
                        </div>
                    </div>
                </div>
            </section>

            

            <!-- Misión y Visión -->
            <section id="mision-vision" class="py-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-center text-3xl md:text-4xl font-semibold tracking-tight">
                        <span class="relative inline-block after:block after:h-1 after:w-16 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">
                            <span class="text-primary">Misión</span> y <span class="text-secondary">Visión</span>
                        </span>
                    </h2>
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="mision" class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <!-- target icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/><path d="M12 2v3m0 14v3m10-10h-3M5 12H2"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-xl">Nuestra Misión</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-2">Conectar mascotas rescatadas con adoptantes responsables, apoyando a refugios mediante tecnología innovadora y una comunidad comprometida.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.05s">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                                    <!-- eye/compass icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-xl">Nuestra Visión</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-2">Ser la plataforma de referencia donde la adopción responsable sea la primera opción, asegurando que cada mascota encuentre un hogar amoroso.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Nuestros Valores -->
            <section id="valores" class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-center text-3xl md:text-4xl font-semibold tracking-tight">
                        <span class="relative inline-block after:block after:h-1 after:w-16 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">Nuestros <span class="text-primary">Valores</span></span>
                    </h2>
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <!-- heart/paw icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M11.99 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10Z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Empatía</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Creemos en el profundo vínculo entre humanos y animales, actuamos siempre desde la compasión y el respeto por cada vida.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.05s">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-warning/10 text-warning flex items-center justify-center shrink-0">
                                    <!-- checklist icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M2 20h7"/><path d="M2 10h7"/><path d="M2 6h7"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Responsabilidad</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Fomentamos la tenencia y adopción responsable, acompañando a la comunidad para asegurar un futuro feliz y definitivo para cada mascota.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.1s">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                                    <!-- community/people icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Comunidad</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Unimos a adoptantes, voluntarios y rescatistas; la colaboración genera impacto real y duradero en el bienestar animal.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.15s">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 flex items-center justify-center shrink-0">
                                    <!-- shield/check icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Integridad</h3>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Transparencia y honestidad para un espacio seguro y confiable en donaciones, voluntariado y adopciones.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Características (referencia al diseño adjunto) -->
            <section id="caracteristicas" class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-center text-3xl md:text-4xl font-semibold tracking-tight">
                        <span class="relative inline-block after:block after:h-1 after:w-20 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">Características de la <span class="text-primary">App</span></span>
                    </h2>
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-4 animate-fade-up">
                            <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                    <!-- filters icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18M6 12h12M10 17h4"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Catálogo Interactivo con Filtros Inteligentes</h4>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Perfiles completos, búsqueda por especie, tamaño, ciudad y más.</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                    <!-- flow/process icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5h6v6H3zM15 5h6v6h-6zM9 13h6v6H9z"/><path d="M9 8h6M12 11v2M12 5v3M12 19v0"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Proceso de Adopción Digital</h4>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Rápido, seguro y transparente con estados claros.</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                    <!-- dashboard/management icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Gestión para Refugios</h4>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Panel centralizado de mascotas, solicitudes y comunicación.</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                    <!-- donate/community icon -->
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Donaciones y Comunidad</h4>
                                    <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Campañas, foros y eventos de esterilización.</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="rounded-2xl overflow-hidden shadow-card border border-neutral-mid/30 animate-float transition hover:-translate-y-0.5 hover:shadow-lg">
                                <img src="{{ asset('storage/foto-perritos-mypetmatch-about-3.jpg') }}" alt="Características" class="w-full h-[320px] md:h-[380px] object-cover"/>
                            </div>
                            <div class="mt-2 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                                Photo by <a class="underline" href="https://unsplash.com/@justwaclaw?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Slava Taukachou</a> on <a class="underline" href="https://unsplash.com/photos/two-cats-playing-with-each-other-on-the-floor-7JuZAiZuXEQ?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Unsplash</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Refugios / Rescatistas -->
            <section id="contacto" class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="relative overflow-hidden rounded-2xl p-6 md:p-10 animate-fade-up"
                        style="background: radial-gradient(1200px 400px at -10% -20%, rgba(5,112,108,0.10), transparent), radial-gradient(1200px 400px at 110% 120%, rgba(245,121,82,0.10), transparent);">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                            <div class="md:col-span-2">
                                <span class="badge badge-primary">Programas para refugios</span>
                                <h3 class="text-2xl md:text-3xl font-semibold mt-3 tracking-tight">
                                    ¿Eres un <span class="text-primary">Refugio</span> o <span class="text-secondary">Rescatista</span>?
                                </h3>
                                <p class="text-sm md:text-base text-neutral-dark/80 dark:text-neutral-300 mt-2 max-w-2xl">
                                    Mejora tu alcance, publica mascotas fácilmente y administra solicitudes con herramientas diseñadas para organizaciones de rescate.
                                </p>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="mailto:contacto@mypetmatch.app" class="btn btn-primary">Escríbenos</a>
                                    <a href="{{ route('register') }}" class="btn btn-secondary">Crear cuenta de organización</a>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <div class="h-36 w-36 md:h-44 md:w-44 rounded-2xl border border-primary/20 bg-primary/5 flex items-center justify-center animate-float">
                                    <!-- Heart icon for CTA -->
                                    <svg viewBox="0 0 24 24" class="h-14 w-14 text-primary" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-auto border-t border-neutral-mid/30 bg-white/80 dark:bg-neutral-dark/70 backdrop-blur supports-[backdrop-filter]:bg-white/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-center text-xs text-neutral-dark/80 dark:text-neutral-300">
                © {{ date('Y') }} MyPetMatch · Hecho con ❤ · Desarrollado por PhantomCoders (Artemis-Devs)
            </div>
        </footer>

        <script>
            // Simple reveal on scroll for .animate-fade-up
            (function(){
                const els = Array.from(document.querySelectorAll('.animate-fade-up'));
                if (!('IntersectionObserver' in window)) { els.forEach(e => e.style.animationPlayState = 'running'); return; }
                const io = new IntersectionObserver((entries)=>{
                    entries.forEach((en)=>{
                        if(en.isIntersecting){
                            en.target.style.animationPlayState = 'running';
                            io.unobserve(en.target);
                        }
                    })
                },{threshold: 0.15});
                els.forEach(e=>{ e.style.animationPlayState = 'paused'; io.observe(e); });
            })();
        </script>
    </body>
</html>
