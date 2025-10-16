<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Mascota — Mi Área</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>
		html {
			scroll-behavior: smooth
		}
	</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">Editar Mascota</h1>
			<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Volver a la lista</a>
		</div>

		@if (session('status'))
		<div class="mt-4 rounded-xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-3 text-sm">{{ session('status') }}</div>
		@endif

		<form class="mt-6 space-y-5" method="POST" action="{{ route('orgs.pets.update', $pet->id) }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Nombre</label>
					<input name="name" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('name', $pet->name) }}" required>
					@error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
				</div>
				<div>
					<label class="text-sm">Especie</label>
					@php $speciesOpts = $speciesOptions ?? ['Perro','Gato','Conejo','Hámster','Cobaya','Chinchilla','Pez','Canario','Periquito','Ninfa','Cacatúa','Tortuga','Iguana','Erizo','Hurón','Gallina','Pavo','Caballo','Otro']; @endphp
					<input name="species" list="species-list" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('species', $pet->species) }}" placeholder="Ej: Perro" />
					<datalist id="species-list">
						@foreach($speciesOpts as $opt)
						<option value="{{ $opt }}"></option>
						@endforeach
					</datalist>
				</div>
				<div>
					<label class="text-sm">Raza</label>
					<input name="breed" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('breed', $pet->breed) }}">
				</div>
				<div>
					<label class="text-sm">Edad (años)</label>
					<input name="age" type="number" min="0" max="100" step="1" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('age', is_numeric($pet->age ?? null) ? $pet->age : null) }}">
					<p class="text-xs text-neutral-dark/60 mt-1">Ingresa un número entero en años.</p>
				</div>
				<div>
					<label class="text-sm">Tamaño</label>
					<select name="size" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						<option value="">—</option>
						@php $sizeOpts = $sizeOptions ?? ['Pequeño','Mediano','Grande','Extra grande','Desconocido']; @endphp
						@foreach($sizeOpts as $opt)
						@php $sel = old('size', $pet->size) === $opt; @endphp
						<option value="{{ $opt }}" @selected($sel)>{{ $opt }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="text-sm">Peso (kg)</label>
					@php $wPrefill = old('weight_kg', $weightKg ?? null); @endphp
					<input name="weight_kg" type="number" min="0" max="999.9" step="0.1" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ $wPrefill }}">
					<p class="text-xs text-neutral-dark/60 mt-1">Ejemplo: 12.5</p>
				</div>
				<div>
					<label class="text-sm">Altura (cm)</label>
					@php $hPrefill = old('height_cm', $heightCm ?? null); @endphp
					<input name="height_cm" type="number" min="0" max="300" step="1" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ $hPrefill }}">
					<p class="text-xs text-neutral-dark/60 mt-1">Ejemplo: 45</p>
				</div>
				<div>
					<label class="text-sm">Sexo</label>
					<select name="sex" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						<option value="">—</option>
						<option value="male" @selected(old('sex', $pet->sex)==='male')>Macho</option>
						<option value="female" @selected(old('sex', $pet->sex)==='female')>Hembra</option>
						<option value="unknown" @selected(old('sex', $pet->sex)==='unknown')>Desconocido</option>
					</select>
				</div>
			</div>

			<div>
				<label class="text-sm">Historia</label>
				<textarea name="story" rows="5" class="mt-1 block w-full rounded-xl border-neutral-mid/40">{{ old('story', isset($storyNoMeta) ? $storyNoMeta : $pet->story) }}</textarea>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Estado</label>
					<select name="status" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						@php $st = old('status', $pet->status); @endphp
						<option value="draft" @selected($st==='draft' )>Borrador</option>
						<option value="published" @selected($st==='published' )>Publicado</option>
						<option value="archived" @selected($st==='archived' )>Archivado</option>
					</select>
				</div>
				<div>
					<label class="text-sm">Imagen de portada (opcional)</label>
					<input id="cover_image_input" name="cover_image" type="file" accept="image/png,image/jpeg,image/jpg" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
					@if($pet->cover_image)
					<img id="existing_cover_preview" src="{{ \Illuminate\Support\Facades\Storage::url($pet->cover_image) }}" alt="Portada {{ $pet->name }}" class="mt-2 w-40 h-28 object-cover rounded-xl border border-neutral-mid/40">
					@endif
					@error('cover_image')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
				</div>
			</div>

			<div class="pt-2 flex items-center gap-3">
				<button class="btn btn-primary" type="submit">Guardar cambios</button>
				<a href="{{ route('orgs.pets.show', $pet->id) }}" class="text-sm hover:text-primary">Ver</a>
				<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Cancelar</a>
			</div>
		</form>

		<form action="{{ route('orgs.pets.destroy', $pet->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta mascota?')" class="mt-3">
			@csrf
			@method('DELETE')
			<button class="btn btn-danger" type="submit">Eliminar</button>
		</form>
	</div>
	@include('partials.footer')
</body>

</html>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const input = document.getElementById('cover_image_input');
		const existing = document.getElementById('existing_cover_preview');
		if (!input) return;
		input.addEventListener('change', function() {
			const f = this.files && this.files[0];
			if (!f) return;
			if (!f.type.startsWith('image/')) return;
			const url = URL.createObjectURL(f);
			if (existing) {
				existing.src = url;
			} else {
				const img = document.createElement('img');
				img.id = 'existing_cover_preview';
				img.src = url;
				img.alt = 'Previsualización';
				img.className = 'mt-2 w-40 h-28 object-cover rounded-xl border border-neutral-mid/40';
				input.insertAdjacentElement('afterend', img);
			}
		});
	});
</script>