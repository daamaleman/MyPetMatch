<section>
    <header>
        <h2 class="text-lg font-semibold">Información de cuenta</h2>
        <p class="mt-1 text-sm text-neutral-dark/70">Nombre y correo electrónico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-4 max-w-xl">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="text-sm">Nombre</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('name', $user->name) }}" required autocomplete="name" />
            @error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="text-sm">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Tu correo no está verificado.

                        <button form="send-verification" class="underline text-sm text-neutral-dark/70 hover:text-primary">
                            Reenviar correo de verificación
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Hemos enviado un nuevo enlace de verificación a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="btn btn-primary">Guardar</button>

            @if (session('status') === 'profile-updated')
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
