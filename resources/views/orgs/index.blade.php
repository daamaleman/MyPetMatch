<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Organizaciones — {{ config('app.name', 'MyPetMatch') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
			<div>
				<h1 class="text-2xl md:text-3xl font-semibold">Organizaciones de rescate</h1>
				<p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Explora refugios y rescatistas. Filtra por nombre y ubicación.</p>
			</div>
		</div>

		<!-- Filtros (estilo similar al de adoptions/index) -->
		<section id="filtros" class="mt-6">
			<div class="rounded-2xl border border-neutral-mid/30 bg-white p-3">
				<form method="GET" action="{{ route('orgs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-2">
					<label class="sr-only" for="q">Buscar</label>
					<input id="q" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nombre de la organización" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light" />
					<label class="sr-only" for="city">Municipio</label>
					<input id="city" type="text" name="city" value="{{ $city ?? '' }}" placeholder="Municipio" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light" />
					<label class="sr-only" for="state">Departamento</label>
					<input id="state" type="text" name="state" value="{{ $state ?? '' }}" placeholder="Departamento" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light" />
					<label class="sr-only" for="country">País</label>
					<input id="country" type="text" name="country" value="{{ $country ?? '' }}" placeholder="País" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light" />
					<div class="md:col-span-4 flex gap-2">
						<button class="btn btn-primary">Filtrar</button>
						<a href="{{ route('orgs.index') }}" class="btn btn-secondary">Limpiar</a>
					</div>
				</form>

				@php $params = request()->query(); @endphp
				@if(($q ?? null) || ($city ?? null) || ($state ?? null) || ($country ?? null))
				<div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
					<span class="text-neutral-dark/70">Filtros activos:</span>
					@if(!empty($q))
						@php $urlNoQ = route('orgs.index', collect($params)->except(['q'])->toArray()); @endphp
						<a href="{{ $urlNoQ }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
							<span>Buscar: "{{ Str::limit($q, 24) }}"</span><span aria-hidden="true">✕</span>
						</a>
					@endif
					@if(!empty($city))
						@php $urlNoCity = route('orgs.index', collect($params)->except(['city'])->toArray()); @endphp
						<a href="{{ $urlNoCity }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
							<span>Municipio: {{ $city }}</span><span aria-hidden="true">✕</span>
						</a>
					@endif
					@if(!empty($state))
						@php $urlNoState = route('orgs.index', collect($params)->except(['state'])->toArray()); @endphp
						<a href="{{ $urlNoState }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
							<span>Departamento: {{ $state }}</span><span aria-hidden="true">✕</span>
						</a>
					@endif
					@if(!empty($country))
						@php $urlNoCountry = route('orgs.index', collect($params)->except(['country'])->toArray()); @endphp
						<a href="{{ $urlNoCountry }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
							<span>País: {{ $country }}</span><span aria-hidden="true">✕</span>
						</a>
					@endif

					<a href="{{ route('orgs.index') }}" class="ms-2 text-primary hover:underline">Limpiar todo</a>
				</div>
				@endif
			</div>
		</section>

		<!-- Listado -->
		<section class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($orgs as $org)
				<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark shadow-card p-4 flex flex-col transition hover:-translate-y-0.5 hover:shadow-lg">
					<div class="flex items-center justify-between">
						<h3 class="font-semibold text-lg tracking-tight">
							<a href="{{ route('orgs.details', $org) }}" class="hover:underline">{{ $org->name }}</a>
						</h3>
						@if(property_exists($org, 'pets_count') && $org->pets_count)
							<span class="badge badge-secondary">{{ $org->pets_count }} mascotas</span>
						@endif
					</div>
					<p class="mt-1 text-sm text-neutral-dark/70">
						@php
							$loc = collect([
								$org->city ?? null,
								$org->state ?? null,
								$org->country ?? null,
							])->filter()->implode(', ');
						@endphp
						{{ $loc ?: 'Ubicación no especificada' }}
					</p>
					@if(!empty($org->description))
						<p class="mt-2 text-sm line-clamp-2 text-neutral-dark/80">{{ Str::limit($org->description, 120) }}</p>
					@endif
					<div class="mt-4 pt-4 border-t border-neutral-mid/30 flex items-center justify-between">
						<a href="{{ route('orgs.details', $org) }}" class="text-sm hover:text-primary">Ver detalles</a>
						<a href="{{ route('orgs.details', $org) }}#mascotas" class="btn btn-primary text-sm">Ver mascotas</a>
					</div>
				</div>
			@empty
				<div class="col-span-full text-sm text-neutral-dark/70">No se encontraron organizaciones con esos filtros.</div>
			@endforelse
		</section>

		<div class="mt-6">{{ $orgs->links() }}</div>
	</main>
</body>
</html>
