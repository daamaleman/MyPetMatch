<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mascotas disponibles — Adopciones</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	@php use App\Models\Pet; @endphp
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between gap-3">
			<div>
				<h1 class="text-2xl font-semibold">Mascotas disponibles</h1>
				<p class="mt-1 text-sm text-neutral-dark/70">Explora mascotas publicadas y listas para adopción.</p>
			</div>
			<a href="{{ route('adoptions.dashboard') }}" class="text-sm hover:text-primary">Mi Área</a>
		</div>

		@php
			$q = request('q');
			$species = request('species');
			$size = request('size');
			$sex = request('sex');
			$petsQuery = Pet::query()->where('status','published');
			if ($q) {
				$petsQuery->where(function($sub) use ($q){
					$sub->where('name','like',"%$q%")
						->orWhere('breed','like',"%$q%")
						->orWhere('species','like',"%$q%")
						->orWhere('story','like',"%$q%");
				});
			}
			if ($species) { $petsQuery->where('species',$species); }
			if ($size) { $petsQuery->where('size',$size); }
			if ($sex) { $petsQuery->where('sex',$sex); }
			$pets = $petsQuery->latest()->paginate(12)->appends(request()->query());
		@endphp

		<form method="GET" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
			<input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre, raza, especie" class="rounded-xl border-neutral-mid/40" />
			<input type="text" name="species" value="{{ $species }}" placeholder="Especie (perro, gato)" class="rounded-xl border-neutral-mid/40" />
			<select name="sex" class="rounded-xl border-neutral-mid/40">
				<option value="">Sexo</option>
				<option value="male" @selected($sex==='male')>Macho</option>
				<option value="female" @selected($sex==='female')>Hembra</option>
				<option value="unknown" @selected($sex==='unknown')>Desconocido</option>
			</select>
			<input type="text" name="size" value="{{ $size }}" placeholder="Tamaño (pequeño, mediano...)" class="rounded-xl border-neutral-mid/40" />
			<div class="md:col-span-4 flex gap-3">
				<button class="btn btn-primary">Filtrar</button>
				<a href="{{ url()->current() }}" class="btn btn-secondary">Limpiar</a>
			</div>
		</form>

		<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($pets as $pet)
				<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark shadow-card overflow-hidden flex flex-col">
					<a href="{{ route('pets.details', $pet->id) }}" class="block">
						@if($pet->cover_image)
							<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-48 object-cover">
						@else
							<div class="w-full h-48 flex items-center justify-center text-sm text-neutral-dark/70">Sin imagen</div>
						@endif
					</a>
					<div class="p-4 flex-1 flex flex-col">
						<h3 class="font-semibold text-lg"><a href="{{ route('pets.details', $pet->id) }}">{{ $pet->name }}</a></h3>
						<p class="mt-1 text-sm text-neutral-dark/70">{{ $pet->species }} • {{ $pet->breed }} • {{ $pet->size }}</p>
						<div class="mt-3 flex items-center gap-2">
							@if($pet->age) <span class="badge badge-primary">Edad: {{ $pet->age }}</span> @endif
							@if($pet->sex) <span class="badge badge-secondary capitalize">{{ $pet->sex }}</span> @endif
						</div>
						<div class="mt-4 pt-4 border-t border-neutral-mid/30 flex items-center justify-between">
							<a href="{{ route('pets.details', $pet->id) }}" class="text-sm hover:text-primary">Ver detalles</a>
							@auth
								@if(auth()->user()->isAdoptante() || auth()->user()->isAdmin())
									<a href="{{ route('pets.details', $pet->id) }}#adoptar" class="btn btn-primary text-sm">Adoptar</a>
								@endif
							@else
								<a href="{{ route('login') }}" class="btn btn-secondary text-sm">Inicia sesión</a>
							@endauth
						</div>
					</div>
				</div>
			@empty
				<div class="col-span-full text-sm text-neutral-dark/70">No hay mascotas disponibles por ahora.</div>
			@endforelse
		</div>

		<div class="mt-6">{{ $pets->links() }}</div>
	</main>

	<script>document.documentElement.classList.add(localStorage.theme||'');</script>
</body>
</html>
