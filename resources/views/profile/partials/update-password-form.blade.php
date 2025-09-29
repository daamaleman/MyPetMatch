<section>
    <header>
        <h2 class="text-lg font-semibold">Seguridad</h2>
        <p class="mt-1 text-sm text-neutral-dark/70">Actualiza tu contraseña periódicamente.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="text-sm">Contraseña actual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-xl border-neutral-mid/40" autocomplete="current-password" />
            @if ($errors->updatePassword?->has('current_password'))
                <p class="text-xs text-danger mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="text-sm">Nueva contraseña</label>
            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-xl border-neutral-mid/40" autocomplete="new-password" />
            @if ($errors->updatePassword?->has('password'))
                <p class="text-xs text-danger mt-1">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="text-sm">Confirmar contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-xl border-neutral-mid/40" autocomplete="new-password" />
            @if ($errors->updatePassword?->has('password_confirmation'))
                <p class="text-xs text-danger mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="btn btn-primary">Guardar</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-dark/70"
                >Guardado.</p>
            @endif
        </div>
    </form>
</section>
