<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de adopción — {{ $pet->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
    @include('partials.header')

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
        @php
            $adIncomplete = $adopterIncomplete ?? false;
            $missing = $adopterMissingLabels ?? [];
            $hasActive = $hasActiveApplication ?? false;
        @endphp
        <div id="adopter-guard"
             data-incomplete="{{ $adIncomplete ? '1':'0' }}"
             data-missing="{{ htmlspecialchars(json_encode($missing ?? []), ENT_QUOTES, 'UTF-8') }}"
             data-active="{{ $hasActive ? '1':'0' }}"></div>

        <h1 class="text-2xl font-semibold">Solicitud de adopción</h1>

        <div class="mt-4 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
            <div class="flex items-center gap-4">
                @if($pet->cover_image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($pet->cover_image) }}" alt="{{ $pet->name }}" class="w-20 h-20 object-cover rounded-xl border border-neutral-mid/40">
                @endif
                <div>
                    <p class="text-sm text-neutral-dark/70">Estás iniciando una solicitud para</p>
                    <h2 class="text-xl font-semibold">{{ $pet->name }}</h2>
                </div>
            </div>

            @if(session('status'))
                <div class="mt-4 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 px-3 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('adoptions.store') }}" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" name="pet_id" value="{{ $pet->id }}">

                <div>
                    <label class="text-sm font-medium">Mensaje para la organización</label>
                    <textarea name="message" rows="4" class="mt-1 block w-full rounded-xl border-neutral-mid/40" placeholder="Cuéntanos por qué deseas adoptar a {{ $pet->name }}...">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Preguntas adicionales (se guardan en JSON answers[]) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm">Tipo de vivienda</label>
                        <select name="answers[home_type]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
                            <option value="" selected>Selecciona...</option>
                            <option value="house" @selected(old('answers.home_type')==='house')>Casa</option>
                            <option value="apartment" @selected(old('answers.home_type')==='apartment')>Apartamento</option>
                            <option value="other" @selected(old('answers.home_type')==='other')>Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm">¿Tienes otras mascotas?</label>
                        <select name="answers[has_pets]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
                            <option value="" selected>Selecciona...</option>
                            <option value="yes" @selected(old('answers.has_pets')==='yes')>Sí</option>
                            <option value="no" @selected(old('answers.has_pets')==='no')>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm">¿Hay niñas o niños en casa?</label>
                        <select name="answers[has_children]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
                            <option value="" selected>Selecciona...</option>
                            <option value="yes" @selected(old('answers.has_children')==='yes')>Sí</option>
                            <option value="no" @selected(old('answers.has_children')==='no')>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm">¿Cuentas con espacio exterior?</label>
                        <select name="answers[outdoor_space]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
                            <option value="" selected>Selecciona...</option>
                            <option value="yes" @selected(old('answers.outdoor_space')==='yes')>Sí</option>
                            <option value="no" @selected(old('answers.outdoor_space')==='no')>No</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button class="btn btn-primary" type="submit">Enviar solicitud</button>
                    <a class="btn" href="{{ route('pets.details', $pet->id) }}">Volver a detalles</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        (function(){
            const guard = document.getElementById('adopter-guard');
            const incomplete = (guard?.dataset.incomplete === '1');
            const hasActive = (guard?.dataset.active === '1');
            let missing = [];
            try { missing = JSON.parse(guard?.dataset.missing || '[]'); } catch(e) { missing = []; }

            if (hasActive) {
                Swal.fire({
                    title: 'Ya tienes una solicitud en curso',
                    html: 'Debes finalizarla antes de crear otra nueva.',
                    icon: 'info',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#05706C',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });
                return; // no mostrar el otro modal si ya hay activa
            }

            if(incomplete){
                let html = 'Por favor completa tu perfil de adoptante antes de solicitar una adopción.';
                if(missing && missing.length){
                    html += '<ul style="text-align:left;margin-top:8px">' + missing.map(m => `<li>• ${m}</li>`).join('') + '</ul>';
                }
                Swal.fire({
                    title: 'Perfil de adoptante incompleto',
                    html,
                    icon: 'warning',
                    confirmButtonText: 'Ir a mi perfil',
                    confirmButtonColor: '#05706C',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    window.location.href = "{{ route('profile.edit', ['from'=>'adoption','require_adopter'=>1]) }}#adoptante";
                });
            }
        })();
    </script>
    @include('partials.footer')
</body>
</html>
