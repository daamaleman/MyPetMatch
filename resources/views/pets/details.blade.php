<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle de Mascota</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>
		html {
			scroll-behavior: smooth
		}
	</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
		<div class="flex items-start justify-between gap-4">
			<div>
				<h1 class="text-2xl md:text-3xl font-semibold">{{ $pet->name }}</h1>
				@php
				$infoBits = [];
				if (!empty($pet->species)) { $infoBits[] = $pet->species; }
				if (!empty($pet->breed)) { $infoBits[] = $pet->breed; }
				if (!empty($pet->size)) { $infoBits[] = $pet->size; }
				if (is_numeric($pet->age ?? null)) { $infoBits[] = ($pet->age.' años'); }
				if (!empty($pet->sex)) { $infoBits[] = ($pet->sex === 'male' ? 'Macho' : ($pet->sex === 'female' ? 'Hembra' : 'Desconocido')); }
				// Attempt to extract weight from story
				$weightFromStory = null; $heightFromStory = null;
				if (preg_match('/^\s*Peso:\s*([0-9]+(?:\.[0-9])?)\s*kg/im', (string)($pet->story ?? ''), $m)) { $weightFromStory = $m[1]; }
				if (preg_match('/^\s*Altura:\s*([0-9]+)\s*cm/im', (string)($pet->story ?? ''), $m2)) { $heightFromStory = $m2[1]; }
				if ($weightFromStory !== null) { $infoBits[] = ($weightFromStory.' kg'); }
				if ($heightFromStory !== null) { $infoBits[] = ($heightFromStory.' cm'); }
				@endphp
				<p class="text-sm text-neutral-dark/70 mt-1">{{ implode(' • ', $infoBits) }}</p>
			</div>
			<div class="flex items-center gap-3">
				@auth
				@if(auth()->user()->isOrganizacion() || auth()->user()->isAdmin())
				<a href="{{ route('orgs.pets.edit', $pet->id) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Volver</a>
				@endif
				@endauth
			</div>
		</div>

		<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
			<div class="lg:col-span-2">
				@if($pet->cover_image)
				<img src="{{ \Illuminate\Support\Facades\Storage::url($pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-80 object-cover rounded-2xl border border-neutral-mid/40">
				@else
				<div class="w-full h-80 rounded-2xl border border-neutral-mid/40 flex items-center justify-center text-sm text-neutral-dark/70">Sin imagen</div>
				@endif

				@if($pet->story)
				<div class="mt-6 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
					<h2 class="text-lg font-semibold">Historia</h2>
					<p class="mt-2 text-sm leading-7 text-neutral-dark/80 whitespace-pre-line">{{ $pet->story }}</p>
				</div>
				@endif
			</div>
			<aside class="space-y-4">
				<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
					<h3 class="font-semibold">Información</h3>
					<div class="mt-3 flex flex-wrap gap-2">
						@if($pet->status)
						<span class="badge badge-secondary capitalize">{{ $pet->status === 'published' ? 'Publicado' : ($pet->status === 'draft' ? 'Borrador' : 'Archivado') }}</span>
						@endif
						@if($pet->age)
						<span class="badge badge-primary">Edad: {{ is_numeric($pet->age ?? null) ? ($pet->age.' años') : $pet->age }}</span>
						@endif
					</div>
				</div>
				@if($pet->organization)
				@php $org = $pet->organization; @endphp
				<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
					<h3 class="font-semibold">Organización</h3>
					<p class="mt-2 text-sm font-medium">{{ $org->name }}</p>
					@php $loc = collect([$org->city,$org->state,$org->country])->filter()->implode(', '); @endphp
					<p class="text-xs text-neutral-dark/70">{{ $loc ?: 'Ubicación no especificada' }}</p>
					<div class="mt-3 space-y-1 text-sm">
						@if($org->email)
						<p>Email: <a href="mailto:{{ $org->email }}" class="text-primary hover:underline">{{ $org->email }}</a></p>
						@endif
						@if($org->phone)
						@php $telHref = 'tel:'.preg_replace('/[^+\d]/','',$org->phone); @endphp
						<p>Teléfono: <a href="{{ $telHref }}" class="text-primary hover:underline">{{ $org->phone }}</a></p>
						@endif
					</div>
					<a href="{{ route('orgs.details', $org->id) }}" class="inline-block mt-3 text-sm hover:text-primary">Ver organización</a>
				</div>
				@endif

				<div id="adoptar" class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
					<h3 class="font-semibold">¿Te interesa adoptar?</h3>
					<p class="text-sm text-neutral-dark/70 mt-1">Envía una solicitud y la organización se pondrá en contacto contigo.</p>
					<div class="mt-3">
						@auth
						@if(auth()->user()->isAdoptante() || auth()->user()->isAdmin())
						@php
						$hasApplied = false;
						if(auth()->check()) {
						$hasApplied = \App\Models\AdoptionApplication::where('user_id', auth()->id())
						->where('pet_id', $pet->id)
						->exists();
						}
						@endphp

						<a href="{{ route('adoptions.apply', $pet->id) }}"
							id="apply-link"
							data-applied="{{ $hasApplied ? '1' : '0' }}"
							data-apply-url="{{ route('adoptions.apply', $pet->id) }}"
							class="btn btn-primary">
							Solicitar adopción
						</a>
						<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
						<script>
							document.addEventListener('DOMContentLoaded', function() {
								var link = document.getElementById('apply-link');
								if (!link) return;
								link.addEventListener('click', function(e) {
									var applied = link.dataset.applied === '1';
									if (applied) {
										e.preventDefault();
										Swal.fire({
											title: 'Ya has solicitado esta adopción',
											text: 'Parece que ya enviaste una solicitud para esta mascota.',
											icon: 'info',
											showCancelButton: true,
											confirmButtonText: 'Ver mis solicitudes',
											cancelButtonText: 'Cancelar',
											reverseButtons: true
										}).then(function(result) {
											if (result.isConfirmed) {
												window.location.href = "{{ route('submissions.index') }}";
											}
										});
									}
									// otherwise let the anchor follow the href normally
								});
							});
						</script>
						@else
						<p class="text-sm text-neutral-dark/70">Inicia sesión como adoptante para solicitar la adopción.</p>
						@endif
						@else
						<a href="{{ route('login') }}" class="btn btn-primary">Inicia sesión para adoptar</a>
						@endauth
					</div>
				</div>
			</aside>
		</div>
	</div>
	@include('partials.footer')
</body>

</html>