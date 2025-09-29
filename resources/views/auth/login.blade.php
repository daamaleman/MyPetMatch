<x-guest-layout>
    <div class="text-center mb-6">
        <span class="badge badge-primary">Bienvenido de vuelta</span>
        <h1 class="text-2xl md:text-3xl font-semibold mt-2 tracking-tight">
            Inicia <span class="text-primary">sesión</span>
        </h1>
        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-2">Accede a tu cuenta para continuar.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Correo electrónico</label>
            <input id="email" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Contraseña</label>
            <div class="relative">
                <input id="password" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary pr-10" type="password" name="password" required autocomplete="current-password" />
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-xl border border-neutral-mid/40 hover:bg-neutral-mid/10 text-neutral-dark/70 dark:text-neutral-300" aria-label="Mostrar contraseña" aria-pressed="false" data-toggle-password data-target="password">
                    <svg data-eye xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg data-eye-off xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 hidden">
                        <path d="M3 3l18 18" />
                        <path d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                        <path d="M9.88 5.06A10.94 10.94 0 0112 5c7 0 11 7 11 7a18.92 18.92 0 01-5.06 5.94" />
                        <path d="M6.61 6.61A18.9 18.9 0 001 12s4 7 11 7a10.9 10.9 0 005.39-1.39" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-neutral-dark/80 dark:text-neutral-300">
                <input id="remember_me" type="checkbox" class="rounded border-neutral-mid/40 text-primary shadow-sm focus:ring-primary" name="remember">
                <span>Recordarme</span>
            </label>
            @if (Route::has('password.request'))
            <a class="text-sm text-primary hover:underline" href="{{ route('password.request') }}">
                ¿Olvidaste tu contraseña?
            </a>
            @endif
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-primary hover:underline" href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
            <button type="submit" class="btn btn-primary px-6 py-2">Iniciar sesión</button>
        </div>
    </form>

    <script>
        // Toggle password visibility (same approach as register)
        (function() {
            const toggles = document.querySelectorAll('[data-toggle-password]');
            toggles.forEach(btn => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;
                btn.addEventListener('click', () => {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', String(show));
                    btn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    const eye = btn.querySelector('[data-eye]');
                    const eyeOff = btn.querySelector('[data-eye-off]');
                    if (eye && eyeOff) {
                        eye.classList.toggle('hidden', !show);
                        eyeOff.classList.toggle('hidden', show);
                    }
                });
            });
        })();
    </script>
</x-guest-layout>