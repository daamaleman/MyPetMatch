<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Solicitudes de adopción — {{ config('app.name', 'MyPetMatch') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="flex-1">
		<section class="pt-6 pb-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex items-center justify-between">
					<div>
						<h1 class="text-2xl md:text-3xl font-semibold">Solicitudes de adopción</h1>
						<p class="mt-1 text-sm text-neutral-dark/70">@if($role==='organizacion') Recibes solicitudes dirigidas a tu organización. @else Tus solicitudes enviadas. @endif</p>
					</div>
				</div>
			</div>
		</section>

		<section id="filtros" class="pt-2 pb-0">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-3">
					<form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-2">
						<input type="text" name="q" value="{{ $q }}" placeholder="Buscar por mascota/org/usuario" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/40" />
						<select name="status" class="h-9 px-3 py-2 text-sm rounded-xl border-neutral-mid/40 bg-neutral-light dark:bg-neutral-dark/40">
							<option value="">Todos los estados</option>
							@foreach(['pending'=>'Pendiente','under_review'=>'En revisión','approved'=>'Aprobada','rejected'=>'Rechazada'] as $k=>$label)
								<option value="{{ $k }}" @selected(($status ?? null)===$k)>{{ $label }}</option>
							@endforeach
						</select>
						<div class="md:col-span-2 flex gap-2">
							<button class="btn btn-primary">Filtrar</button>
							<a class="btn btn-secondary" href="{{ route('submissions.index') }}">Limpiar</a>
						</div>
					</form>
				</div>
			</div>
		</section>

		@php
			$groups = [
				'pending' => 'Pendientes',
				'under_review' => 'En revisión',
				'approved' => 'Aprobadas',
				'rejected' => 'Rechazadas',
			];
			$byStatus = collect($applications)->groupBy('status');
		@endphp

		@foreach($groups as $key=>$title)
		<section class="py-4">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<h2 class="text-lg font-semibold mb-3">{{ $title }}</h2>
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
					@forelse(($byStatus[$key] ?? collect()) as $app)
						<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-4 shadow-card flex flex-col gap-3">
							<div class="flex items-start justify-between gap-3">
								<div>
									<p class="text-xs text-neutral-dark/60">Mascota</p>
									<h3 class="text-base font-semibold">{{ $app->pet->name ?? 'Mascota' }}</h3>
								</div>
								<span class="badge {{ $app->status==='approved' ? 'badge-primary' : ($app->status==='rejected' ? 'badge-danger' : ($app->status==='under_review' ? 'badge-warning' : 'badge-secondary')) }}">{{ ucfirst(str_replace('_',' ',$app->status)) }}</span>
							</div>
							<p class="text-sm text-neutral-dark/70">Org: {{ $app->organization->name ?? '—' }}</p>
							<p class="text-sm text-neutral-dark/70">Adoptante: {{ $app->user->name ?? '—' }}</p>
							@if($app->message)
								<p class="text-sm text-neutral-dark/80 line-clamp-2">“{{ Str::limit($app->message, 120) }}”</p>
							@endif
							<div class="mt-2 flex items-center gap-2">
								<a class="btn btn-primary text-sm" href="{{ route('submissions.show', $app->id) }}">Ver detalles</a>
								@if($role==='adoptante' && in_array($app->status,['pending','under_review']))
									<a class="btn text-sm" href="{{ route('submissions.edit', $app->id) }}">Editar</a>
								@endif
							</div>
						</div>
					@empty
						<p class="col-span-full text-sm text-neutral-dark/60">No hay solicitudes en este estado.</p>
					@endforelse
				</div>
			</div>
		</section>
		@endforeach
	</main>

	@include('partials.footer')
</body>
</html>
