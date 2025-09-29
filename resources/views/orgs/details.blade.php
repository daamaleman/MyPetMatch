<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $org->name }} — Organización</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<div>
				<h1 class="text-2xl md:text-3xl font-semibold">{{ $org->name }}</h1>
				<p class="text-sm text-neutral-dark/70 mt-1">
					@php
						$loc = collect([$org->city ?? null, $org->state ?? null, $org->country ?? null])->filter()->implode(', ');
					@endphp
					{{ $loc ?: 'Ubicación no especificada' }}
				</p>
			</div>
			<a href="{{ route('orgs.index') }}" class="text-sm hover:text-primary">Volver a organizaciones</a>
		</div>

		<section class="mt-6 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
			<h2 class="text-lg font-semibold">Sobre la organización</h2>
			<p class="mt-2 text-sm text-neutral-dark/80">
				Información de ejemplo. Puedes extender el modelo con descripción, contacto, redes, etc.
			</p>
			<div class="mt-4 text-sm">
				<span class="badge badge-primary">Mascotas publicadas: {{ $org->pets_count ?? ($org->pets?->count() ?? 0) }}</span>
			</div>
		</section>

		@if($org->pets && $org->pets->count())
			<section class="mt-8">
				<h2 class="text-lg font-semibold">Mascotas recientes</h2>
				<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
					@foreach($org->pets as $pet)
						<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark overflow-hidden">
							@if($pet->cover_image)
								<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-40 object-cover">
							@endif
							<div class="p-4">
								<p class="font-medium">{{ $pet->name }}</p>
								<p class="text-xs text-neutral-dark/70 mt-1">{{ $pet->species }} • {{ $pet->breed }}</p>
							</div>
						</div>
					@endforeach
				</div>
			</section>
		@endif
	</main>
</body>
</html>
