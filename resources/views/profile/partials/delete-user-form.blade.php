<section class="space-y-4">
    <header>
        <h2 class="text-lg font-semibold">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-neutral-dark/70">Esta acción no se puede deshacer. Descarga cualquier dato que quieras conservar.</p>
    </header>

    <button
        class="btn btn-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Eliminar cuenta</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold">¿Seguro que quieres eliminar tu cuenta?</h2>
            <p class="mt-1 text-sm text-neutral-dark/70">Una vez eliminada, todos tus datos serán borrados. Ingresa tu contraseña para confirmar.</p>

            <div class="mt-6">
                <label for="password" class="sr-only">Contraseña</label>
                <input id="password" name="password" type="password" class="mt-1 block w-3/4 rounded-xl border-neutral-mid/40" placeholder="Contraseña" />
                @if ($errors->userDeletion?->has('password'))
                    <p class="text-xs text-danger mt-1">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" class="btn" x-on:click="$dispatch('close')">Cancelar</button>
                <button class="btn btn-danger ms-3">Eliminar cuenta</button>
            </div>
        </form>
    </x-modal>
</section>
