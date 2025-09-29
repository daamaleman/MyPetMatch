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
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
    @include('partials.header')

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @php
            $adIncomplete = $adopterIncomplete ?? false;
            $missing = $adopterMissingLabels ?? [];
        @endphp
        <div id="adopter-guard" data-incomplete="{{ $adIncomplete ? '1':'0' }}" data-missing="{{ htmlspecialchars(json_encode($missing ?? []), ENT_QUOTES, 'UTF-8') }}"></div>
        <h1 class="text-2xl font-semibold">Solicitud de adopción</h1>
        <div class="mt-4 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
            <div class="flex items-center gap-4">
                @if($pet->cover_image)
                    <img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-24 h-24 object-cover rounded-xl border border-neutral-mid/40">
                @endif
                <div>
                    <p class="text-sm text-neutral-dark/70">Estás iniciando una solicitud para</p>
                    <h2 class="text-xl font-semibold">{{ $pet->name }}</h2>
                </div>
            </div>
            <p class="mt-4 text-sm text-neutral-dark/80">Próximamente aquí podrás completar tu solicitud. Por ahora, esta es una vista de placeholder.</p>
            <div class="mt-6 flex items-center gap-3">
                <a class="btn btn-primary" href="{{ route('pets.details', $pet->id) }}">Volver a detalles</a>
                <a class="btn btn-secondary" href="{{ route('adoptions.index') }}">Ver más mascotas</a>
            </div>
        </div>
    </main>

    <script>
        (function(){
            const guard = document.getElementById('adopter-guard');
            const incomplete = (guard?.dataset.incomplete === '1');
            let missing = [];
            try { missing = JSON.parse(guard?.dataset.missing || '[]'); } catch(e) { missing = []; }
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
</body>
</html>
