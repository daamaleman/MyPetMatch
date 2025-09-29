<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle Mascota — Mi Área</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">{{ $pet->name }}</h1>
			<div class="flex items-center gap-3">
				<a href="{{ route('orgs.pets.edit', $pet->id) }}" class="btn btn-primary">Editar</a>
				<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Volver</a>
			</div>
		</div>

		<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
			<div>
				@if($pet->cover_image)
					<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-72 object-cover rounded-2xl border border-neutral-mid/40">
				@else
					<div class="w-full h-72 rounded-2xl border border-neutral-mid/40 flex items-center justify-center text-sm text-neutral-dark/70">Sin imagen</div>
				@endif
			</div>
			<div class="space-y-3">
				<div class="flex items-center gap-3">
					<span class="badge badge-secondary">{{ $pet->status }}</span>
					@if($pet->age) <span class="badge badge-primary">Edad: {{ $pet->age }}</span> @endif
				</div>
				<p class="text-sm text-neutral-dark/70">{{ $pet->species }} • {{ $pet->breed }} • {{ $pet->size }} • {{ $pet->sex }}</p>
				@if($pet->story)
					<div class="mt-4">
						<h2 class="font-semibold">Historia</h2>
						<p class="mt-2 text-sm leading-6 text-neutral-dark/80">{{ $pet->story }}</p>
					</div>
				@endif
			</div>
		</div>
	</div>
</body>
</html>
