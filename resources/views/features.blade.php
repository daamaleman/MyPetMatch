<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cómo Funciona — {{ config('app.name', 'MyPetMatch') }}</title>
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

        @keyframes floatY {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .7s ease-out both;
        }

        .animate-float {
            animation: floatY 4s ease-in-out infinite;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1">
        @php
        $about3Path = 'storage/foto-perritos-mypetmatch-about-3.jpg';
        @endphp
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="animate-fade-up">
                    <p class="text-sm uppercase tracking-wide text-primary">Guía</p>
                    <h1 class="mt-3 text-4xl md:text-5xl font-semibold leading-tight tracking-tight">
                        ¿Cómo Funciona <span class="text-primary">MyPetMatch</span>?
                    </h1>
                    <p class="mt-5 text-neutral-dark/80 dark:text-neutral-300 max-w-xl">
                        Te acompañamos de punta a punta: desde descubrir a tu próximo compañero hasta completar una adopción responsable junto a refugios y rescatistas.
                    </p>
                    <div class="mt-7 flex items-center gap-3">
                        <a href="{{ route('adoptions.browse') }}" class="btn btn-primary">Explorar Mascotas</a>
                        <a href="#pasos" class="btn btn-secondary">Ver Pasos</a>
                    </div>
                </div>
                <div class="relative animate-float">
                    <div class="rounded-2xl overflow-hidden shadow-card border border-neutral-mid/30">
                        <img src="{{ asset($about3Path) }}" alt="Cómo funciona" class="w-full h-[360px] object-cover" />
                    </div>
                    <div class="mt-2 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                        Photo by <a class="underline" href="https://unsplash.com/@justwaclaw?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Slava Taukachou</a> on <a class="underline" href="https://unsplash.com/photos/two-cats-playing-with-each-other-on-the-floor-7JuZAiZuXEQ?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Unsplash</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pasos: Cómo Funciona -->
        <section id="pasos" class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-3xl md:text-4xl font-semibold tracking-tight">
                    <span class="relative inline-block after:block after:h-1 after:w-20 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">Tu Camino a la <span class="text-primary">Adopción</span></span>
                </h2>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Paso 1 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <!-- user-plus -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M19 8v6m3-3h-6" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Regístrate</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Crea tu cuenta gratuita en minutos y completa tu perfil.</p>
                    </div>
                    <!-- Paso 2 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.05s">
                        <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                            <!-- search -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M21 21l-4.3-4.3" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Busca</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Explora mascotas con filtros inteligentes por especie, tamaño y municipio.</p>
                    </div>
                    <!-- Paso 3 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.1s">
                        <div class="h-10 w-10 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                            <!-- heart -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10z" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Contacta</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Conecta con refugios y rescatistas para conocer más sobre la mascota.</p>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Paso 4 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <!-- clipboard-check -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 11l3 3L22 4" />
                                <rect x="3" y="4" width="14" height="18" rx="2" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Aplica</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Envía tu solicitud de adopción digital con datos claros y verificados.</p>
                    </div>
                    <!-- Paso 5 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.05s">
                        <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                            <!-- bell/notifications -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 01-3.46 0" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Seguimiento</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Recibe actualizaciones de estado y coordina visitas o entrevistas.</p>
                    </div>
                    <!-- Paso 6 -->
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 animate-fade-up transition hover:-translate-y-0.5 hover:shadow-lg" style="animation-delay:.1s">
                        <div class="h-10 w-10 rounded-xl bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 flex items-center justify-center">
                            <!-- home/heart -->
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 10l9-7 9 7v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                <path d="M9 21V9l6 6" />
                            </svg>
                        </div>
                        <h3 class="mt-3 font-semibold text-lg">Adopta</h3>
                        <p class="mt-1 text-sm text-neutral-dark/70 dark:text-neutral-300">Firma el acuerdo y da la bienvenida a un nuevo integrante de la familia.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Características de la App -->
        <section id="caracteristicas" class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-3xl md:text-4xl font-semibold tracking-tight">
                    <span class="relative inline-block after:block after:h-1 after:w-20 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">Características de la <span class="text-primary">App</span></span>
                </h2>
                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div class="space-y-4 animate-fade-up">
                        <!-- Catálogo -->
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                <!-- filters icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 7h18M6 12h12M10 17h4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Catálogo Interactivo con Filtros Inteligentes</h4>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Perfiles completos, fotos, historia y filtros por especie, tamaño, municipio y más.</p>
                            </div>
                        </div>
                        <!-- Adopción digital -->
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                <!-- flow/process icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 5h6v6H3zM15 5h6v6h-6zM9 13h6v6H9z" />
                                    <path d="M9 8h6M12 11v2M12 5v3M12 19v0" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Proceso de Adopción Digital</h4>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Solicitudes en línea, verificación de datos y estados claros para los usuarios.</p>
                            </div>
                        </div>
                        <!-- Gestión refugios -->
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                <!-- dashboard/management icon -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" />
                                    <rect x="14" y="3" width="7" height="7" />
                                    <rect x="14" y="14" width="7" height="7" />
                                    <rect x="3" y="14" width="7" height="7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Gestión para Refugios</h4>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Panel para administrar mascotas, solicitudes, voluntariado y comunicación.</p>
                            </div>
                        </div>
                        <!-- Comunidad y donaciones -->
                        <div class="bg-white dark:bg-neutral-dark/70 rounded-xl border border-neutral-mid/30 p-4 flex items-start gap-3 transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="h-10 w-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                                <!-- heart/community -->
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Donaciones y Comunidad</h4>
                                <p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Campañas de apoyo, foros, eventos y registro de donaciones en dinero y especie.</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="rounded-2xl overflow-hidden shadow-card border border-neutral-mid/30 animate-float transition hover:-translate-y-0.5 hover:shadow-lg">
                            <img src="{{ asset($about3Path) }}" alt="Características" class="w-full h-[320px] md:h-[380px] object-cover" />
                        </div>
                        <div class="mt-2 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                            Photo by <a class="underline" href="https://unsplash.com/@justwaclaw?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Slava Taukachou</a> on <a class="underline" href="https://unsplash.com/photos/two-cats-playing-with-each-other-on-the-floor-7JuZAiZuXEQ?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" target="_blank" rel="noopener">Unsplash</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Módulos adicionales -->
        <section class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-2xl md:text-3xl font-semibold tracking-tight">
                    <span class="relative inline-block after:block after:h-1 after:w-16 after:bg-secondary after:rounded-full after:mx-auto after:mt-2">Módulos <span class="text-primary">Avanzados</span></span>
                </h2>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Mensajería y Notificaciones</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Comunicación directa y avisos en tiempo real para coordinar entrevistas y seguimientos.</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Seguridad y Verificación</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Roles, permisos y validaciones para garantizar un entorno confiable.</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Estadísticas y Reportes</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Indicadores de adopciones, tiempos de proceso y participación comunitaria.</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Integraciones</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Vías de integración con correo, pasarelas de pago y herramientas de terceros.</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Soporte Multiorganización</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Gestión de múltiples refugios, miembros y permisos desde una misma plataforma.</p>
                    </div>
                    <div class="bg-white dark:bg-neutral-dark/70 rounded-2xl shadow-card p-6 border border-neutral-mid/30 transition hover:-translate-y-0.5 hover:shadow-lg">
                        <h3 class="font-semibold">Accesibilidad y Rendimiento</h3>
                        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-1">Interfaz optimizada, accesible y veloz para todo tipo de dispositivos.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section id="contacto" class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-2xl p-6 md:p-10 animate-fade-up"
                    style="background: radial-gradient(1200px 400px at -10% -20%, rgba(5,112,108,0.10), transparent), radial-gradient(1200px 400px at 110% 120%, rgba(245,121,82,0.10), transparent);">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div class="md:col-span-2">
                            <span class="badge badge-primary">¿Listo para empezar?</span>
                            <h3 class="text-2xl md:text-3xl font-semibold mt-3 tracking-tight">Únete a <span class="text-primary">MyPetMatch</span> hoy</h3>
                            <p class="text-sm md:text-base text-neutral-dark/80 dark:text-neutral-300 mt-2 max-w-2xl">Crea tu cuenta de adoptante o registra tu organización para publicar mascotas y gestionar solicitudes.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="mailto:contacto@mypetmatch.app" class="btn btn-primary">Escríbenos</a>
                                @guest
                                <a href="{{ route('register') }}" class="btn btn-secondary">Crear cuenta</a>
                                @endguest
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <div class="h-36 w-36 md:h-44 md:w-44 rounded-2xl border border-primary/20 bg-primary/5 flex items-center justify-center animate-float">
                                <!-- Heart icon -->
                                <svg viewBox="0 0 24 24" class="h-14 w-14 text-primary" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s-7-4.438-7-10a4 4 0 017-2 4 4 0 017 2c0 5.562-7 10-7 10z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
<script>
    // Simple reveal on scroll for .animate-fade-up
    (function() {
        const els = Array.from(document.querySelectorAll('.animate-fade-up'));
        if (!('IntersectionObserver' in window)) {
            els.forEach(e => e.style.animationPlayState = 'running');
            return;
        }
        const io = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) {
                    en.target.style.animationPlayState = 'running';
                    io.unobserve(en.target);
                }
            })
        }, {
            threshold: 0.15
        });
        els.forEach(e => {
            e.style.animationPlayState = 'paused';
            io.observe(e);
        });
    })();
</script>

</html>