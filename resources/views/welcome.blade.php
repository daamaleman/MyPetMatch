<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'MyPetMatch') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
    </head>
    <body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
        @include('partials.header')

    <main class="flex-1">
            <!-- HERO -->
            <section class="relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-primary">Encuentra tu compañero</p>
                        <h1 class="mt-3 text-4xl md:text-5xl font-semibold leading-tight">
                            Transforma una Vida,<br>
                            <span class="text-primary">Adopta una Mascota</span>
                        </h1>
                        <p class="mt-5 text-neutral-dark/80 dark:text-neutral-300 max-w-xl">
                            Encuentra tu compañero peludo ideal hoy. Descubramos juntos tu mascota perfecta.
                        </p>
                        <div class="mt-7 flex items-center gap-3">
                            <a href="{{ url('/pets') }}" class="btn btn-primary">Empezar Búsqueda →</a>
                            <a href="#como-funciona" class="btn btn-secondary">Conoce Más</a>
                        </div>
                        <div class="mt-6 text-xs text-neutral-dark/70 dark:text-neutral-300">
                            <div class="flex items-center gap-3">
                                <span>Confiado por 1,000+ adoptantes</span>
                                <div class="flex -space-x-2">
                                    <!-- Avatar placeholders (SVG circles) -->
                                    <span class="h-7 w-7 rounded-full bg-neutral-mid/60 flex items-center justify-center ring-2 ring-white dark:ring-neutral-dark">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-white/90" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/></svg>
                                    </span>
                                    <span class="h-7 w-7 rounded-full bg-neutral-mid/60 flex items-center justify-center ring-2 ring-white dark:ring-neutral-dark">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-white/90" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/></svg>
                                    </span>
                                    <span class="h-7 w-7 rounded-full bg-neutral-mid/60 flex items-center justify-center ring-2 ring-white dark:ring-neutral-dark">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-white/90" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/></svg>
                                    </span>
                                    <span class="h-7 w-7 rounded-full bg-neutral-mid/60 flex items-center justify-center ring-2 ring-white dark:ring-neutral-dark">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-white/90" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/></svg>
                                    </span>
                                    <span class="h-7 w-7 rounded-full bg-secondary text-white text-[10px] font-medium flex items-center justify-center ring-2 ring-white dark:ring-neutral-dark">+12</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="rounded-2xl overflow-hidden shadow-card">
                            <img src="{{ asset('storage/foto-perritos-mypetmatch-portada.jpg') }}" alt="Perritos descansando - Portada MyPetMatch" class="w-full h-[380px] md:h-[420px] object-cover"/>
                        </div>
                        <!-- Unsplash credit -->
                        <div class="mt-2 text-[10px] text-neutral-dark/70 dark:text-neutral-300">
                            Photo by <a class="underline hover:text-primary" href="https://unsplash.com/@farzanleli?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" rel="noopener" target="_blank">Farzan Lelinwalla</a> on
                            <a class="underline hover:text-primary" href="https://unsplash.com/photos/brown-short-coated-dog-lying-on-brown-textile-MVaU_krWVRY?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash" rel="noopener" target="_blank">Unsplash</a>
                        </div>

                        <!-- Testimonials carousel moved here (replaces Ana López quote) -->
                        <div class="absolute -bottom-10 md:-bottom-12 left-4 bg-white/90 dark:bg-neutral-dark/80 backdrop-blur rounded-2xl shadow-card p-4 w-[80%] border border-neutral-mid/40">
                            <div class="relative">
                                <!-- Slides -->
                                <div class="space-y-3 min-h-[0]">
                                    <article data-testimonial-slide class="transition-opacity duration-300">
                                        <div class="text-sm leading-relaxed">“Proceso rápido y claro. El equipo fue muy amable y hoy Luna es parte de la familia.”</div>
                                        <div class="mt-1 text-xs text-neutral-dark/70 dark:text-neutral-300">— Carlos Méndez</div>
                                    </article>
                                    <article data-testimonial-slide class="hidden opacity-0 transition-opacity duration-300">
                                        <div class="text-sm leading-relaxed">“Excelente experiencia. Nos guiaron en cada paso y adoptamos a Rocky sin complicaciones.”</div>
                                        <div class="mt-1 text-xs text-neutral-dark/70 dark:text-neutral-300">— Sofía Herrera</div>
                                    </article>
                                    <article data-testimonial-slide class="hidden opacity-0 transition-opacity duration-300">
                                        <div class="text-sm leading-relaxed">“Plataforma confiable y con muchas opciones. Se nota el cuidado por el bienestar animal.”</div>
                                        <div class="mt-1 text-xs text-neutral-dark/70 dark:text-neutral-300">— Martín Díaz</div>
                                    </article>
                                </div>

                                <!-- Controls -->
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex gap-2" role="tablist" aria-label="Testimonios">
                                        <button class="h-2 w-2 rounded-full bg-primary/70" data-testimonial-dot aria-label="Testimonio 1"></button>
                                        <button class="h-2 w-2 rounded-full bg-neutral-mid" data-testimonial-dot aria-label="Testimonio 2"></button>
                                        <button class="h-2 w-2 rounded-full bg-neutral-mid" data-testimonial-dot aria-label="Testimonio 3"></button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="h-7 w-7 rounded-xl border border-neutral-mid/40 hover:bg-neutral-mid/10" data-testimonial-prev aria-label="Anterior">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                        </button>
                                        <button class="h-7 w-7 rounded-xl border border-neutral-mid/40 hover:bg-neutral-mid/10" data-testimonial-next aria-label="Siguiente">
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TRUST BAR -->
            <section class="py-10 border-t border-neutral-mid/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-neutral-dark/70">Confiado por organizaciones líderes</p>
                    <!-- Logos carousel (template) -->
                    <div class="mt-6 relative overflow-hidden" aria-label="Carrusel de logos de organizaciones">
                        <div class="flex items-center gap-12 overflow-x-auto no-scrollbar opacity-80" data-logos-track>
                            <!-- Repeatable logo placeholders -->
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <!-- Duplicate for seamless loop -->
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
                            <div class="h-10 w-28 bg-neutral-mid/40 rounded-xl shrink-0"></div>
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

            <style>
                /* Ocultar scrollbar horizontal para el carrusel de logos */
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>

            <script>
                // Simple testimonials carousel without external deps
                (function(){
                    const slides = Array.from(document.querySelectorAll('[data-testimonial-slide]'));
                    const dots = Array.from(document.querySelectorAll('[data-testimonial-dot]'));
                    const btnNext = document.querySelector('[data-testimonial-next]');
                    const btnPrev = document.querySelector('[data-testimonial-prev]');
                    if (!slides.length) return;
                    let i = 0, timer;

                    function render(){
                        slides.forEach((el, idx) => {
                            const active = idx === i;
                            el.classList.toggle('hidden', !active);
                            el.classList.toggle('opacity-0', !active);
                        });
                        dots.forEach((d, idx) => {
                            d.classList.toggle('bg-primary/70', idx === i);
                            d.classList.toggle('bg-neutral-mid', idx !== i);
                        });
                    }
                    function next(){ i = (i + 1) % slides.length; render(); }
                    function prev(){ i = (i - 1 + slides.length) % slides.length; render(); }
                    function play(){ stop(); timer = setInterval(next, 6000); }
                    function stop(){ if (timer) clearInterval(timer); }

                    btnNext && btnNext.addEventListener('click', () => { next(); play(); });
                    btnPrev && btnPrev.addEventListener('click', () => { prev(); play(); });
                    dots.forEach((d, idx) => d.addEventListener('click', () => { i = idx; render(); play(); }));
                    render();
                    play();
                })();

                // Auto-scrolling logo carousel (template)
                (function(){
                    const track = document.querySelector('[data-logos-track]');
                    if (!track) return;
                    let pos = 0, timer;
                    function step(){
                        pos += 1; // speed
                        track.scrollLeft = pos;
                        const max = track.scrollWidth - track.clientWidth;
                        if (pos >= max - 1) pos = 0;
                    }
                    function play(){ stop(); timer = setInterval(step, 24); }
                    function stop(){ if (timer) clearInterval(timer); }
                    track.addEventListener('mouseenter', stop);
                    track.addEventListener('mouseleave', play);
                    play();
                })();
            </script>
    </body>
    </html>
