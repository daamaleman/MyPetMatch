<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mascotas — Mi Área</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
	</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">Mascotas</h1>
			<a href="{{ route('orgs.pets.create') }}" class="btn btn-primary">Nueva Mascota</a>
		</div>

		@if (session('status'))
			<div class="mt-4 rounded-xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-3 text-sm">{{ session('status') }}</div>
		@endif

		<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($pets as $pet)
				<a href="{{ route('orgs.pets.show', $pet->id) }}" class="block rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition">
					@if($pet->cover_image)
						<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-44 object-cover rounded-t-2xl">
					@endif
					<div class="p-4">
						<p class="font-semibold">{{ $pet->name }}</p>
						<p class="text-xs text-neutral-dark/70 mt-1">{{ $pet->species }} • {{ $pet->breed }}</p>
						<div class="mt-3 flex items-center gap-3">
							<span class="badge badge-secondary">{{ $pet->status }}</span>
							@if($pet->age) <span class="badge badge-primary">Edad: {{ $pet->age }}</span> @endif
						</div>
					</div>
				</a>
			@empty
				<div class="col-span-full text-sm text-neutral-dark/70">Aún no hay mascotas.</div>
			@endforelse
		</div>

		<div class="mt-6">{{ $pets->links() }}</div>
	</div>
</body>
</html>
