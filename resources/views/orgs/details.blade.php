<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle Mascota — Panel</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
    @include('partials.header')
	<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">{{ $pet->name }}</h1>
			<div class="flex items-center gap-3">
				<a href="{{ route('orgs.pets.edit', $pet->id) }}" class="btn btn-secondary">Editar</a>
				<a href="{{ route('orgs.pets.index') }}" class="text-sm text-primary">Volver</a>
			</div>
		</div>

		<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
			<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-3">
				@if($pet->cover_image)
					<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-80 object-cover rounded-xl">
				@else
					<div class="w-full h-80 rounded-xl bg-neutral-mid/30 flex items-center justify-center text-sm text-neutral-dark/70">Sin imagen</div>
				@endif
			</div>
			<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-6 space-y-2">
				<p><span class="font-medium">Especie:</span> {{ $pet->species ?: '—' }}</p>
				<p><span class="font-medium">Raza:</span> {{ $pet->breed ?: '—' }}</p>
				<p><span class="font-medium">Edad:</span> {{ $pet->age ?: '—' }}</p>
				<p><span class="font-medium">Tamaño:</span> {{ $pet->size ?: '—' }}</p>
				<p><span class="font-medium">Sexo:</span> {{ $pet->sex ?: '—' }}</p>
				<p><span class="font-medium">Estado:</span> <span class="badge badge-secondary">{{ $pet->status }}</span></p>
				<div class="pt-3">
					<p class="font-medium">Historia</p>
					<p class="text-sm text-neutral-dark/80 mt-1 whitespace-pre-line">{{ $pet->story ?: '—' }}</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
