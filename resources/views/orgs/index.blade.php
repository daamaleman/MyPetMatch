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

		<!-- Filtros -->
		<form method="GET" action="{{ route('orgs.index') }}" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
			<div>
				<label class="text-xs">Buscar</label>
				<input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nombre de la organización" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
			</div>
			<div>
				<label class="text-xs">Ciudad</label>
				<input type="text" name="city" value="{{ $city ?? '' }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
			</div>
			<div>
				<label class="text-xs">Estado/Provincia</label>
				<input type="text" name="state" value="{{ $state ?? '' }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
			</div>
			<div>
				<label class="text-xs">País</label>
				<input type="text" name="country" value="{{ $country ?? '' }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
			</div>
			<div class="md:col-span-4 pt-1">
				<button class="btn btn-primary text-sm">Aplicar filtros</button>
				<a href="{{ route('orgs.index') }}" class="ms-2 text-sm hover:text-primary">Limpiar</a>
			</div>
		</form>

		<!-- Listado -->
		<section class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($orgs as $org)
				<a href="{{ route('orgs.details', $org) }}" class="block rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition p-4">
					<div class="flex items-center justify-between">
						<p class="font-semibold">{{ $org->name }}</p>
						@if(property_exists($org, 'pets_count') && $org->pets_count)
							<span class="badge badge-secondary">{{ $org->pets_count }} mascotas</span>
						@endif
					</div>
					<p class="mt-1 text-xs text-neutral-dark/70">
						@php
							$loc = collect([
								$org->city ?? null,
								$org->state ?? null,
								$org->country ?? null,
							])->filter()->implode(', ');
						@endphp
						{{ $loc ?: 'Ubicación no especificada' }}
					</p>
				</a>
			@empty
				<div class="col-span-full text-sm text-neutral-dark/70">No se encontraron organizaciones con esos filtros.</div>
			@endforelse
		</section>

		<div class="mt-6">{{ $orgs->links() }}</div>
	</main>
</body>
</html>
