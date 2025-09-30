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
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
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
			<div class="flex items-center gap-3">
				<a href="{{ route('orgs.index') }}" class="text-sm hover:text-primary">Volver a organizaciones</a>
				<a href="{{ route('adoptions.browse') }}" class="btn btn-primary text-sm">Ver mascotas disponibles</a>
			</div>
		</div>

		<section class="mt-6 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
			<h2 class="text-lg font-semibold">Sobre la organización</h2>
			@php $desc = trim((string)($org->description ?? '')); @endphp
			<p class="mt-2 text-sm text-neutral-dark/80 whitespace-pre-line">{{ $desc !== '' ? $desc : 'Aún no hay una descripción disponible.' }}</p>
			<div class="mt-4 text-sm">
				<span class="badge badge-primary">Mascotas registradas: {{ $org->pets_count ?? ($org->pets?->count() ?? 0) }}</span>
			</div>
		</section>

		<section class="mt-6 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
			<h2 class="text-lg font-semibold">Contacto</h2>
			<div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
				<div class="rounded-xl border border-neutral-mid/30 p-4 bg-white/60 dark:bg-neutral-dark/60">
					<p class="text-neutral-dark/70">Email</p>
					@php $email = $org->email ?? null; @endphp
					@if($email)
						<a href="mailto:{{ $email }}" class="mt-1 inline-block text-primary hover:underline">{{ $email }}</a>
					@else
						<p class="mt-1">No especificado</p>
					@endif
				</div>
				<div class="rounded-xl border border-neutral-mid/30 p-4 bg-white/60 dark:bg-neutral-dark/60">
					<p class="text-neutral-dark/70">Teléfono</p>
					@php $phone = $org->phone ?? null; $telHref = $phone ? 'tel:'.preg_replace('/[^+\d]/','', $phone) : null; @endphp
					@if($phone)
						<a href="{{ $telHref }}" class="mt-1 inline-block text-primary hover:underline">{{ $phone }}</a>
					@else
						<p class="mt-1">No especificado</p>
					@endif
				</div>
				<div class="rounded-xl border border-neutral-mid/30 p-4 bg-white/60 dark:bg-neutral-dark/60">
					<p class="text-neutral-dark/70">Ubicación</p>
					<p class="mt-1">{{ $loc ?: 'No especificada' }}</p>
				</div>
			</div>
		</section>

		@if($org->pets && $org->pets->count())
			<section class="mt-8">
				<h2 class="text-lg font-semibold">Mascotas recientes</h2>
				<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
					@foreach($org->pets as $pet)
						@continue(isset($pet->status) && $pet->status !== 'published')
						<a href="{{ route('pets.details', ['pet' => $pet->id]) }}" class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark overflow-hidden hover:shadow-card transition">
							@if($pet->cover_image)
								<img src="{{ \Illuminate\Support\Facades\Storage::url($pet->cover_image) }}" alt="{{ $pet->name }}" class="w-full h-40 object-cover">
							@endif
							<div class="p-4">
								<p class="font-medium">{{ $pet->name }}</p>
								<p class="text-xs text-neutral-dark/70 mt-1">{{ $pet->species }} @if(!empty($pet->breed)) • {{ $pet->breed }} @endif</p>
							</div>
						</a>
					@endforeach
				</div>
			</section>
		@endif
	</main>
	@include('partials.footer')
</body>
</html>
