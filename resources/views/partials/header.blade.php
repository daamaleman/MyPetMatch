<header class="border-b border-neutral-mid/30 bg-white/80 dark:bg-neutral-dark/70 backdrop-blur supports-[backdrop-filter]:bg-white/50 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 font-semibold">
            <img src="{{ asset('storage/MyPetMatchLogo-Transparente.png') }}" alt="MyPetMatch" class="h-12 w-12 md:h-14 md:w-14 object-contain" />
            <span>MyPetMatch</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="/" class="{{ request()->is('/') ? 'text-primary font-medium' : 'hover:text-primary' }}">Inicio</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary font-medium' : 'hover:text-primary' }}">Nosotros</a>
            <a href="{{ route('features') }}" class="{{ request()->routeIs('features') ? 'text-primary font-medium' : 'hover:text-primary' }}">Cómo Funciona</a>
            <a href="{{ route('orgs.index') }}" class="{{ request()->routeIs('orgs.index') ? 'text-primary font-medium' : 'hover:text-primary' }}">Organizaciones</a>
            <a href="{{ route('adoptions.browse') }}" class="{{ request()->routeIs('adoptions.browse') ? 'text-primary font-medium' : 'hover:text-primary' }}">Adoptar</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-primary font-medium' : 'hover:text-primary' }}">Contacto</a>
        </nav>
        <div class="flex items-center gap-3">
            <!-- Mobile hamburger -->
            <button id="mobileNavBtn" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100" aria-expanded="false" aria-controls="mobileNav">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <!-- Mobile menu (hidden on md+) -->
            <div id="mobileNav" class="absolute top-16 left-0 right-0 bg-white/95 dark:bg-neutral-dark/90 border-t border-neutral-mid/30 shadow-md p-4 hidden md:hidden z-40">
                <div class="flex flex-col gap-3">
                    <a href="/" class="px-3 py-2 rounded-lg">Inicio</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg">Nosotros</a>
                    <a href="{{ route('features') }}" class="px-3 py-2 rounded-lg">Cómo Funciona</a>
                    <a href="{{ route('orgs.index') }}" class="px-3 py-2 rounded-lg">Organizaciones</a>
                    <a href="{{ route('adoptions.browse') }}" class="px-3 py-2 rounded-lg">Adoptar</a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 rounded-lg">Contacto</a>
                    @auth
                    {{-- Authenticated users: no large "Mi Área" here (keep top CTA) --}}
                    @else
                    <a href="{{ route('login') }}" class="px-3 py-2">Iniciar Sesión</a>
                    @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    @endif
                    @endauth
                </div>
            </div>
            @auth
            @php
            $user = auth()->user();
            $role = $user->role ?? null;
            $myAreaRoute = ($role === 'organizacion' || $role === 'admin')
            ? route('orgs.dashboard')
            : (($role === 'adoptante') ? route('adoptions.dashboard') : route('dashboard'));
            @endphp

            <!-- CTA principal: Mi Área -->
            <a href="{{ $myAreaRoute }}" class="btn btn-primary text-sm">Mi Área</a>

            <!-- Menú de usuario (Perfil / Cerrar sesión) -->
            <div class="relative">
                <button type="button" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-neutral-mid/40 bg-white/70 dark:bg-neutral-dark/60 hover:border-primary/60 transition text-sm"
                    aria-haspopup="menu" aria-expanded="false" data-user-menu-button>
                    <!-- Icono usuario -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-neutral-dark/80 dark:text-neutral-200">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.741.895H4.492a.75.75 0 01-.741-.895z" clip-rule="evenodd" />
                    </svg>
                    <span class="hidden sm:inline">{{ $user->name ?? 'Usuario' }}</span>
                    <!-- Caret -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 opacity-70">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-56 rounded-2xl border border-neutral-mid/40 bg-white dark:bg-neutral-dark shadow-card p-1 hidden" role="menu" aria-label="Menú de usuario" data-user-menu>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-neutral-mid/20 dark:hover:bg-neutral-dark/40 text-sm" role="menuitem">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M11.25 4.5a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5zM4.5 18.75A6.75 6.75 0 0111.25 12h1.5A6.75 6.75 0 0119.5 18.75v.75a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-.75z" clip-rule="evenodd" />
                        </svg>
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" role="menuitem">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-neutral-mid/20 dark:hover:bg-neutral-dark/40 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M7.5 3.75A2.25 2.25 0 009.75 6v12A2.25 2.25 0 017.5 20.25h-3A2.25 2.25 0 012.25 18V6A2.25 2.25 0 014.5 3.75h3zm9.72 5.03a.75.75 0 011.06 1.06l-2.72 2.72H21a.75.75 0 010 1.5h-5.44l2.72 2.72a.75.75 0 11-1.06 1.06l-4.25-4.25a.75.75 0 010-1.06l4.25-4.25z" clip-rule="evenodd" />
                            </svg>
                            Salir
                        </button>
                    </form>
                </div>
            </div>

            <script>
                (function() {
                    const btn = document.querySelector('[data-user-menu-button]');
                    const menu = document.querySelector('[data-user-menu]');
                    if (!btn || !menu) return;
                    let open = false;

                    function closeMenu() {
                        menu.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                        open = false;
                    }

                    function openMenu() {
                        menu.classList.remove('hidden');
                        btn.setAttribute('aria-expanded', 'true');
                        open = true;
                    }
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        open ? closeMenu() : openMenu();
                    });
                    document.addEventListener('click', (e) => {
                        if (open && !menu.contains(e.target) && e.target !== btn) closeMenu();
                    });
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && open) closeMenu();
                    });
                })();
            </script>

            @else
            <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm">Iniciar Sesión</a>
            @if(Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary text-sm">Registrarse</a>
            @endif
            @endauth
        </div>
    </div>

    {{-- Subnavegación contextual para paneles --}}
    @auth
    @php $role = auth()->user()->role ?? null; @endphp
    @if(($role === 'organizacion' || $role === 'admin') && (request()->routeIs('orgs.*') || request()->routeIs('adoptions.*') || request()->routeIs('submissions.*') || request()->routeIs('pets.*')))
    <div class="border-t border-neutral-mid/30 bg-white/70 dark:bg-neutral-dark/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center gap-6 text-sm">
            <a href="{{ route('orgs.dashboard') }}" class="{{ request()->routeIs('orgs.dashboard') ? 'text-primary font-medium' : 'hover:text-primary' }}">Resumen</a>
            <a href="{{ route('orgs.pets.index') }}" class="{{ (request()->routeIs('orgs.pets.*') || request()->routeIs('pets.*')) ? 'text-primary font-medium' : 'hover:text-primary' }}">Mascotas</a>
            <a href="{{ route('submissions.index') }}" class="{{ request()->routeIs('submissions.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Solicitudes</a>
        </div>
    </div>
    @elseif(($role === 'adoptante' || $role === 'admin') && (request()->routeIs('adoptions.*') || request()->routeIs('orgs.*') || request()->routeIs('submissions.*') || request()->routeIs('pets.*')))
    <div class="border-t border-neutral-mid/30 bg-white/70 dark:bg-neutral-dark/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center gap-6 text-sm">
            <a href="{{ route('adoptions.dashboard') }}" class="{{ request()->routeIs('adoptions.dashboard') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mi Área</a>
            <a href="{{ route('adoptions.browse') }}" class="{{ (request()->routeIs('adoptions.browse') || request()->routeIs('pets.details')) ? 'text-primary font-medium' : 'hover:text-primary' }}">Adoptar</a>
            <a href="{{ route('submissions.index') }}" class="{{ request()->routeIs('submissions.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mis solicitudes</a>
        </div>
    </div>
    @endif
    @endauth
</header>

<script>
    // Public header mobile toggle: ensure hamburger works on mobile pages
    (function() {
        if (typeof window === 'undefined') return;
        // wait a tick in case DOM not fully parsed
        function init() {
            const btn = document.getElementById('mobileNavBtn');
            const nav = document.getElementById('mobileNav');
            if (!btn || !nav) return;

            const openMenu = function() {
                nav.classList.remove('hidden');
                nav.classList.add('block');
                btn.setAttribute('aria-expanded', 'true');
                document.documentElement.classList.add('no-scroll');
                document.body.style.overflow = 'hidden';
            };

            const closeMenu = function() {
                nav.classList.remove('block');
                nav.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                document.documentElement.classList.remove('no-scroll');
                document.body.style.overflow = '';
            };

            const toggle = function(e) {
                if (e && e.preventDefault) e.preventDefault();
                if (nav.classList.contains('hidden')) openMenu();
                else closeMenu();
            };

            btn.addEventListener('click', toggle);
            btn.addEventListener('touchstart', function(e) {
                e.preventDefault();
                toggle(e);
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!nav.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
                    if (!nav.classList.contains('hidden')) closeMenu();
                }
            });
            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
    })();
</script>