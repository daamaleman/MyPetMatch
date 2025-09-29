<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle de Mascota</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-start justify-between gap-4">
			<div>
				<h1 class="text-2xl md:text-3xl font-semibold">{{ $pet->name }}</h1>
				<p class="text-sm text-neutral-dark/70 mt-1">
					{{ $pet->species }} @if($pet->breed) • {{ $pet->breed }} @endif @if($pet->size) • {{ $pet->size }} @endif @if($pet->sex) • {{ $pet->sex === 'male' ? 'Macho' : ($pet->sex === 'female' ? 'Hembra' : 'Desconocido') }} @endif
				</p>
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
					<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-80 object-cover rounded-2xl border border-neutral-mid/40">
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
							<span class="badge badge-primary">Edad: {{ $pet->age }}</span>
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
								<a href="{{ route('adoptions.apply', $pet->id) }}" class="btn btn-primary">Solicitar adopción</a>
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
</body>
</html>
