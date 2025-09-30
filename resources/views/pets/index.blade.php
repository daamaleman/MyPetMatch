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
<body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="flex-1">
		<section class="pt-6 pb-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex items-center justify-between">
					<div>
						<h1 class="text-2xl font-semibold tracking-tight">Mascotas de la organización</h1>
						<p class="mt-1 text-sm text-neutral-dark/70">Gestiona y visualiza tus mascotas. Usa los filtros para encontrar rápidamente.</p>
					</div>
					<a href="{{ route('orgs.pets.create') }}" class="btn btn-primary">Nueva Mascota</a>
				</div>

				@if (session('status'))
					<div class="mt-4 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-3 text-sm">{{ session('status') }}</div>
				@endif
			</div>
		</section>


		<!-- FILTERS (replicados de adoptions/index sin afectar la lógica de adopciones) -->
		<section id="filtros" class="pt-2 pb-0">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="rounded-2xl border border-neutral-mid/30 bg-white p-2 md:p-3">
					<form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-2">
						<label class="sr-only" for="q">Buscar</label>
						<input id="q" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por nombre, raza, especie" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light" />
						<label class="sr-only" for="species">Especie</label>
						<select id="species" name="species" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light">
							<option value="">Especie</option>
							@isset($speciesOptions)
								@foreach($speciesOptions as $opt)
									<option value="{{ $opt }}" @selected(($species ?? '')===$opt)>{{ Str::title($opt) }}</option>
								@endforeach
							@endisset
						</select>
						<label class="sr-only" for="sex">Sexo</label>
						<select id="sex" name="sex" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light">
							<option value="">Sexo</option>
							<option value="male" @selected(($sex ?? '')==='male')>Macho</option>
							<option value="female" @selected(($sex ?? '')==='female')>Hembra</option>
							<option value="unknown" @selected(($sex ?? '')==='unknown')>Desconocido</option>
						</select>
						<label class="sr-only" for="size">Tamaño</label>
						<select id="size" name="size" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light">
							<option value="">Tamaño</option>
							@isset($sizeOptions)
								@foreach($sizeOptions as $opt)
									<option value="{{ $opt }}" @selected(($size ?? '')===$opt)>{{ Str::title($opt) }}</option>
								@endforeach
							@endisset
						</select>
						<div class="md:col-span-4 flex gap-2">
							<button class="btn btn-primary">Filtrar</button>
							<a href="{{ url()->current() }}" class="btn btn-secondary">Limpiar</a>
						</div>
					</form>

					@php $params = request()->query(); @endphp
					@if(($q ?? null) || ($species ?? null) || ($size ?? null) || ($sex ?? null))
						<div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
							<span class="text-neutral-dark/70">Filtros activos:</span>
							@if(!empty($q))
								@php $urlNoQ = url()->current() . ( $params ? ('?'. http_build_query(collect($params)->except(['q'])->toArray())) : '' ); @endphp
								<a href="{{ $urlNoQ }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
									<span>Buscar: "{{ Str::limit($q, 24) }}"</span>
									<span aria-hidden="true">✕</span>
								</a>
							@endif
							@if(!empty($species))
								@php $urlNoSpecies = url()->current() . ( $params ? ('?'. http_build_query(collect($params)->except(['species'])->toArray())) : '' ); @endphp
								<a href="{{ $urlNoSpecies }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
									<span>Especie: {{ $species }}</span>
									<span aria-hidden="true">✕</span>
								</a>
							@endif
							@if(!empty($sex))
								@php $urlNoSex = url()->current() . ( $params ? ('?'. http_build_query(collect($params)->except(['sex'])->toArray())) : '' ); @endphp
								<a href="{{ $urlNoSex }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
									<span>Sexo: {{ $sex }}</span>
									<span aria-hidden="true">✕</span>
								</a>
							@endif
							@if(!empty($size))
								@php $urlNoSize = url()->current() . ( $params ? ('?'. http_build_query(collect($params)->except(['size'])->toArray())) : '' ); @endphp
								<a href="{{ $urlNoSize }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 hover:bg-neutral-mid/20">
									<span>Tamaño: {{ $size }}</span>
									<span aria-hidden="true">✕</span>
								</a>
							@endif

							<a href="{{ url()->current() }}" class="ms-2 text-primary hover:underline">Limpiar todo</a>
						</div>
					@endif
				</div>
			</div>
		</section>

		<!-- RESULTS GRID (replica de estilos de adoptions/index sin su lógica de filtros) -->
		<section class="py-4">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				@php 
					$from = method_exists($pets, 'firstItem') ? ($pets->firstItem() ?? 0) : ($pets->count() ? 1 : 0);
					$to = method_exists($pets, 'lastItem') ? ($pets->lastItem() ?? $pets->count()) : $pets->count();
					$total = method_exists($pets, 'total') ? $pets->total() : $pets->count();
				@endphp
				<div class="flex items-center justify-between gap-3 text-sm text-neutral-dark/70 mb-3">
					<div>
						@if($total > 0)
							Mostrando {{ $from }}–{{ $to }} de {{ $total }} resultados
						@else
							Sin resultados
						@endif
					</div>
				</div>

				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
					@forelse($pets as $pet)
						<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark shadow-card overflow-hidden flex flex-col transition hover:-translate-y-0.5 hover:shadow-lg">
							<a href="{{ route('orgs.pets.show', $pet->id) }}" class="block">
								@if($pet->cover_image)
									<img src="{{ \Illuminate\Support\Facades\Storage::url($pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-48 object-cover">
								@else
									<div class="w-full h-48 flex items-center justify-center text-sm text-neutral-dark/70">Sin imagen</div>
								@endif
							</a>
							<div class="p-4 flex-1 flex flex-col">
								<h3 class="font-semibold text-lg tracking-tight">
									<a href="{{ route('orgs.pets.show', $pet->id) }}">{{ $pet->name }}</a>
								</h3>
								<p class="mt-1 text-sm text-neutral-dark/70">{{ $pet->species }} • {{ $pet->breed }} @if(!empty($pet->size)) • {{ $pet->size }} @endif</p>
								<div class="mt-3 flex flex-wrap items-center gap-2">
									@if(!empty($pet->status))
										@php
											$statusClass = 'badge-secondary';
											if ($pet->status === 'published') $statusClass = 'badge-primary';
											elseif ($pet->status === 'draft') $statusClass = 'badge-warning';
											elseif ($pet->status === 'adopted') $statusClass = 'badge-danger';
										@endphp
										<span class="badge {{ $statusClass }} capitalize">{{ $pet->status }}</span>
									@endif
									@if(!empty($pet->age))
										<span class="badge badge-primary">Edad: {{ is_numeric($pet->age ?? null) ? ($pet->age.' años') : $pet->age }}</span>
									@endif
									@php
										$w=null;$h=null;$story=(string)($pet->story??'');
										if(preg_match('/^\s*Peso:\s*([0-9]+(?:\.[0-9])?)\s*kg/im',$story,$m)){ $w=$m[1]; }
										if(preg_match('/^\s*Altura:\s*([0-9]+)\s*cm/im',$story,$m2)){ $h=$m2[1]; }
									@endphp
									@if($w !== null)
										<span class="badge">{{ $w }} kg</span>
									@endif
									@if($h !== null)
										<span class="badge">{{ $h }} cm</span>
									@endif
									@if(!empty($pet->sex))
										<span class="badge badge-secondary capitalize">{{ $pet->sex }}</span>
									@endif
								</div>
								<div class="mt-4 pt-4 border-t border-neutral-mid/30 flex items-center justify-between">
									<a href="{{ route('orgs.pets.show', $pet->id) }}" class="text-sm hover:text-primary">Ver</a>
									<div class="flex items-center gap-2">
										<a href="{{ route('orgs.pets.edit', $pet->id) }}" class="btn btn-secondary text-sm">Editar</a>
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="col-span-full text-sm text-neutral-dark/70">Aún no hay mascotas.</div>
					@endforelse
				</div>

				<div class="mt-6">{{ $pets->links() }}</div>
			</div>
		</section>
	</main>

	@include('partials.footer')

	<script>
		// Mantener el comportamiento suave de scroll y estilos similares a adoptions/index
		document.documentElement.classList.add('supports-[backdrop-filter]');
	</script>
</body>
</html>
