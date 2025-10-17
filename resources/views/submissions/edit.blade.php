<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar solicitud — {{ config('app.name', 'MyPetMatch') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
		@if(session('status'))
		<div class="mb-4 rounded-xl border border-neutral-mid/40 bg-neutral-mid/10 px-3 py-2 text-sm">{{ session('status') }}</div>
		@endif

		<h1 class="text-2xl font-semibold">Editar solicitud para {{ $application->pet->name ?? 'Mascota' }}</h1>
		@php $alreadyEdited = $application->updated_at && $application->created_at && $application->updated_at->ne($application->created_at); @endphp
		@if(!$alreadyEdited)
		<div class="mt-4 rounded-lg p-3 bg-blue-50 border border-blue-200 text-sm">Tienes una única oportunidad para editar esta solicitud. Después de guardar, no podrás modificarla nuevamente.</div>
		@endif
		<div class="mt-4 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-5">
			@if($alreadyEdited)
			<div class="mb-4 rounded-lg p-3 bg-yellow-50 border border-yellow-200 text-sm">Esta solicitud ya fue editada previamente y no puede modificarse de nuevo. Si necesitas cambiar algo, contacta a la organización.</div>
			@endif
			<form method="POST" action="{{ route('submissions.update', $application->id) }}" class="space-y-4">
				@csrf
				@method('put')

				<div>
					<label class="text-sm font-medium">Mensaje para la organización</label>
					<textarea name="message" rows="4" class="mt-1 block w-full rounded-xl border-neutral-mid/40" placeholder="Mensaje..." @if($alreadyEdited) disabled @endif>{{ old('message', $application->message) }}</textarea>
					@error('message')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
				</div>

				@php $answers = old('answers', $application->answers ?? []); @endphp
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label class="text-sm">Tipo de vivienda</label>
						<select name="answers[home_type]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
							<option value="">Selecciona...</option>
							@foreach(["house"=>"Casa","apartment"=>"Apartamento","other"=>"Otro"] as $k=>$lbl)
							<option value="{{ $k }}" @selected(($answers['home_type'] ?? '' )===$k)>{{ $lbl }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="text-sm">¿Tienes otras mascotas?</label>
						<select name="answers[has_pets]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
							<option value="">Selecciona...</option>
							@foreach(["yes"=>"Sí","no"=>"No"] as $k=>$lbl)
							<option value="{{ $k }}" @selected(($answers['has_pets'] ?? '' )===$k)>{{ $lbl }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="text-sm">¿Hay niños en casa?</label>
						<select name="answers[has_children]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
							<option value="">Selecciona...</option>
							@foreach(["yes"=>"Sí","no"=>"No"] as $k=>$lbl)
							<option value="{{ $k }}" @selected(($answers['has_children'] ?? '' )===$k)>{{ $lbl }}</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="text-sm">¿Cuentas con espacio exterior?</label>
						<select name="answers[outdoor_space]" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
							<option value="">Selecciona...</option>
							@foreach(["yes"=>"Sí","no"=>"No"] as $k=>$lbl)
							<option value="{{ $k }}" @selected(($answers['outdoor_space'] ?? '' )===$k)>{{ $lbl }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="flex items-center gap-3 pt-2">
					<button class="btn btn-primary" type="submit" @if($alreadyEdited) disabled @endif>Guardar cambios</button>
					<a class="btn" href="{{ route('submissions.show', $application->id) }}">Cancelar</a>
				</div>
			</form>
		</div>
	</main>

	@include('partials.footer')
</body>

</html>