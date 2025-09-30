<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle de solicitud — {{ config('app.name', 'MyPetMatch') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
		@if(session('status'))
			<div class="mb-4 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 px-3 py-2 text-sm">{{ session('status') }}</div>
		@endif

		<h1 class="text-2xl font-semibold">Solicitud para {{ $application->pet->name ?? 'Mascota' }}</h1>
		<p class="mt-1 text-sm text-neutral-dark/70">Enviada por {{ $application->user->name ?? '—' }} a {{ $application->organization->name ?? '—' }}</p>

		@php
			$statusLabels = [
				'pending' => 'Pendiente',
				'under_review' => 'En revisión',
				'approved' => 'Aprobada',
				'rejected' => 'Rechazada',
			];
			$answerLabels = [
				'home_type' => 'Tipo de vivienda',
				'has_pets' => '¿Tienes otras mascotas?',
				'has_children' => '¿Hay niños en casa?',
				'outdoor_space' => '¿Cuentas con espacio exterior?',
			];
		@endphp

		<div class="mt-5 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5 space-y-4">
			<div class="flex items-center justify-between">
				<div class="text-sm">
					<p><span class="text-neutral-dark/60">Estado:</span> <span class="font-medium">{{ $statusLabels[$application->status] ?? ucfirst(str_replace('_',' ',$application->status)) }}</span></p>
					<p><span class="text-neutral-dark/60">Fecha:</span> {{ $application->created_at->format('d/m/Y H:i') }}</p>
				</div>
				@if($role==='organizacion')
				<form method="POST" action="{{ route('submissions.update', $application->id) }}" class="flex items-center gap-2">
					@csrf
					@method('put')
					<select name="status" class="h-9 rounded-xl border-neutral-mid/40">
						@foreach(['pending'=>'Pendiente','under_review'=>'En revisión','approved'=>'Aprobada','rejected'=>'Rechazada'] as $k=>$label)
							<option value="{{ $k }}" @selected($application->status===$k)>{{ $label }}</option>
						@endforeach
					</select>
					<button class="btn btn-primary">Actualizar</button>
				</form>
				@endif
			</div>

			@php $profile = $application->user->adopterProfile ?? null; @endphp
			<div class="rounded-xl border border-neutral-mid/30 bg-neutral-mid/10 p-4">
				<h2 class="text-lg font-semibold">Datos del adoptante</h2>
				<dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
					<div>
						<dt class="text-neutral-dark/60">Nombre</dt>
						<dd class="font-medium">{{ $application->user->name ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Correo electrónico</dt>
						<dd class="font-medium">{{ $application->user->email ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Teléfono</dt>
						<dd class="font-medium">{{ $profile->phone ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Dirección</dt>
						<dd class="font-medium">{{ $profile?->address_line1 }}@if(!empty($profile?->address_line2)), {{ $profile?->address_line2 }}@endif</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Municipio / Ciudad</dt>
						<dd class="font-medium">{{ $profile->city ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Departamento / Estado</dt>
						<dd class="font-medium">{{ $profile->state ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">País</dt>
						<dd class="font-medium">{{ $profile->country ?? '—' }}</dd>
					</div>
					<div>
						<dt class="text-neutral-dark/60">Código postal</dt>
						<dd class="font-medium">{{ $profile->zip ?? '—' }}</dd>
					</div>
				</dl>
				@if($role==='adoptante')
					<p class="mt-2 text-xs text-neutral-dark/70">Nota: Esta información se comparte con la organización para evaluar tu solicitud.</p>
				@endif
			</div>

			@if($application->message)
				<div>
					<h2 class="text-lg font-semibold">Mensaje del adoptante</h2>
					<p class="mt-1 text-sm text-neutral-dark/80 whitespace-pre-line">{{ $application->message }}</p>
				</div>
			@endif

			@php $answers = $application->answers ?? []; @endphp
			@if(!empty($answers))
				<div>
					<h2 class="text-lg font-semibold">Respuestas</h2>
					<dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
						@foreach($answers as $k=>$v)
							<div>
								@php
									$label = $answerLabels[$k] ?? Str::of($k)->replace('_',' ')->title();
									$value = $v;
									if (is_string($value)) {
										$valueLower = strtolower($value);
										if ($valueLower === 'yes') $value = 'Sí';
										elseif ($valueLower === 'no') $value = 'No';
									}
								@endphp
								<dt class="text-neutral-dark/60">{{ $label }}</dt>
								<dd class="font-medium">{{ is_bool($value) ? ($value ? 'Sí' : 'No') : (is_array($value) ? implode(', ', $value) : $value) }}</dd>
							</div>
						@endforeach
					</dl>
				</div>
			@endif

			<div class="pt-2">
				<a class="btn" href="{{ route('submissions.index') }}">Volver a la lista</a>
				@if($role==='adoptante' && in_array($application->status,['pending','under_review']))
					<a class="btn btn-primary" href="{{ route('submissions.edit', $application->id) }}">Editar solicitud</a>
				@endif
			</div>
		</div>
	</main>

	@include('partials.footer')
</body>
</html>
