<x-guest-layout>
    <div class="text-center mb-6">
        <span class="badge badge-primary">Bienvenido</span>
        <h1 class="text-2xl md:text-3xl font-semibold mt-2 tracking-tight">
            Crea tu <span class="text-primary">cuenta</span>
        </h1>
        <p class="text-sm text-neutral-dark/70 dark:text-neutral-300 mt-2">Únete a MyPetMatch y encuentra tu compañero ideal.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Tipo de registro (switch mejorado, centrado) -->
        <div class="text-center">
            <label for="is_organization" class="inline-flex items-center gap-3 cursor-pointer select-none">
                <input id="is_organization" name="is_organization" type="checkbox" value="1" class="sr-only peer" {{ old('is_organization') ? 'checked' : '' }}>
                <span class="w-11 h-6 rounded-full bg-neutral-mid/40 relative transition-colors peer-checked:bg-primary">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                </span>
                <span class="text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Registrarme como organización</span>
            </label>
            <div class="mt-1 text-xs text-neutral-dark/60 dark:text-neutral-300">
                Podrás completar datos de organización más tarde
            </div>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Nombre</label>
            <input id="name" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Correo electrónico</label>
            <input id="email" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Contraseña</label>
            <div class="relative">
                <input id="password" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary pr-10" type="password" name="password" required autocomplete="new-password" />
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Confirmar contraseña</label>
            <div class="relative">
                <input id="password_confirmation" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary pr-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-xl border border-neutral-mid/40 hover:bg-neutral-mid/10 text-neutral-dark/70 dark:text-neutral-300" aria-label="Mostrar contraseña" aria-pressed="false" data-toggle-password data-target="password_confirmation">
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
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Campos de Organización (integrados, no dentro de otra tarjeta) -->
        <div id="org-fields" class="mt-2 {{ old('is_organization') ? '' : 'hidden' }}">
            <div class="text-center mb-3">
                <div><span class="badge badge-secondary">Cuenta de organización</span></div>
                <p class="mt-1 text-xs text-neutral-dark/70 dark:text-neutral-300">
                    Completa estos datos para validar tu organización. 
                    <span class="font-medium">Los campos marcados con <span class="text-danger">*</span> son obligatorios</span>; el resto puedes completarlos más tarde.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="org_name" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Nombre de la organización <span class="text-danger">*</span></label>
                    <input id="org_name" name="org_name" type="text" value="{{ old('org_name') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" placeholder="Ej. Fundación Patitas" />
                    <x-input-error :messages="$errors->get('org_name')" class="mt-2" />
                </div>
                <div>
                    <label for="org_email" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Email de contacto <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <input id="org_email" name="org_email" type="email" value="{{ old('org_email') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" />
                    <x-input-error :messages="$errors->get('org_email')" class="mt-2" />
                </div>
                <div>
                    <label for="org_phone" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Teléfono <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <input id="org_phone" name="org_phone" type="text" value="{{ old('org_phone') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" />
                    <x-input-error :messages="$errors->get('org_phone')" class="mt-2" />
                </div>
                <div>
                    <label for="org_city" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Municipio <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <input id="org_city" name="org_city" type="text" value="{{ old('org_city') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" />
                    <x-input-error :messages="$errors->get('org_city')" class="mt-2" />
                </div>
                <div>
                    <label for="org_state" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Departamento <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <input id="org_state" name="org_state" type="text" value="{{ old('org_state') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" />
                    <x-input-error :messages="$errors->get('org_state')" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <label for="org_country" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">País <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <input id="org_country" name="org_country" type="text" value="{{ old('org_country') }}" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" />
                    <x-input-error :messages="$errors->get('org_country')" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                    <label for="org_description" class="block text-sm font-medium text-neutral-dark/90 dark:text-neutral-200">Descripción <span class="text-xs font-normal text-neutral-dark/60 dark:text-neutral-300">(opcional)</span></label>
                    <textarea id="org_description" name="org_description" rows="3" class="mt-1 block w-full rounded-md border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/60 text-neutral-dark dark:text-neutral-white shadow-sm focus:border-primary focus:ring-primary" placeholder="Cuéntanos sobre tu organización">{{ old('org_description') }}</textarea>
                    <x-input-error :messages="$errors->get('org_description')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-primary hover:underline" href="{{ route('login') }}">
                ¿Ya tienes cuenta?
            </a>
            <button type="submit" class="btn btn-primary px-6 py-2">
                Registrarse
            </button>
        </div>
    </form>
    <script>
        // Toggle password visibility for inputs with [data-toggle-password]
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
        // Mostrar/ocultar campos de organización
        (function(){
            const cb = document.getElementById('is_organization');
            const block = document.getElementById('org-fields');
            const sync = () => {
                const isOrg = !!cb.checked;
                block?.classList.toggle('hidden', !isOrg);
            };
            if (cb) {
                cb.addEventListener('change', sync);
                // init
                sync();
            }
        })();
    </script>
</x-guest-layout>