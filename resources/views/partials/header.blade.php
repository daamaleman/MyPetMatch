<header class="border-b border-neutral-mid/30 bg-white/80 dark:bg-neutral-dark/70 backdrop-blur supports-[backdrop-filter]:bg-white/50 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 font-semibold">
            <img src="{{ asset('storage/MyPetMatchLogo-Transparente.png') }}" alt="MyPetMatch" class="h-12 w-12 md:h-14 md:w-14 object-contain"/>
            <span>MyPetMatch</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="/" class="{{ request()->is('/') ? 'text-primary font-medium' : 'hover:text-primary' }}">Inicio</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-primary font-medium' : 'hover:text-primary' }}">Nosotros</a>
            <a href="{{ route('features') }}" class="{{ request()->routeIs('features') ? 'text-primary font-medium' : 'hover:text-primary' }}">Cómo Funciona</a>
            <a href="#contacto" class="hover:text-primary">Contacto</a>
            @auth
                @php $role = auth()->user()->role ?? null; @endphp
                @if($role === 'organizacion' || $role === 'admin')
                    <a href="{{ route('orgs.dashboard') }}" class="{{ request()->routeIs('orgs.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mi Área</a>
                @elseif($role === 'adoptante')
                    <a href="{{ route('adoptions.dashboard') }}" class="{{ request()->routeIs('adoptions.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mi Área</a>
                @endif
            @endauth
        </nav>
        <div class="flex items-center gap-3">
            @auth
                @php
                    $role = auth()->user()->role ?? null;
                    $myAreaRoute = ($role === 'organizacion' || $role === 'admin')
                        ? route('orgs.dashboard')
                        : (($role === 'adoptante') ? route('adoptions.dashboard') : route('dashboard'));
                @endphp
                <a href="{{ $myAreaRoute }}" class="hidden sm:inline-flex text-sm">Mi Área</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary text-sm">Salir</button>
                </form>
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
        @if(request()->routeIs('orgs.*') && ($role === 'organizacion' || $role === 'admin'))
            <div class="border-t border-neutral-mid/30 bg-white/70 dark:bg-neutral-dark/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center gap-6 text-sm">
                    <a href="{{ route('orgs.dashboard') }}" class="{{ request()->routeIs('orgs.dashboard') ? 'text-primary font-medium' : 'hover:text-primary' }}">Resumen</a>
                    <a href="{{ route('orgs.pets.index') }}" class="{{ request()->routeIs('orgs.pets.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mascotas</a>
                    <a href="{{ route('orgs.adoptions.index') }}" class="{{ request()->routeIs('orgs.adoptions.*') ? 'text-primary font-medium' : 'hover:text-primary' }}">Solicitudes</a>
                </div>
            </div>
        @elseif(request()->routeIs('adoptions.*') && ($role === 'adoptante' || $role === 'admin'))
            <div class="border-t border-neutral-mid/30 bg-white/70 dark:bg-neutral-dark/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center gap-6 text-sm">
                    <a href="{{ route('adoptions.dashboard') }}" class="{{ request()->routeIs('adoptions.dashboard') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mi Área</a>
                    <a href="{{ route('adoptions.index') }}" class="{{ request()->routeIs('adoptions.index') ? 'text-primary font-medium' : 'hover:text-primary' }}">Mis Solicitudes</a>
                </div>
            </div>
        @endif
    @endauth
</header>