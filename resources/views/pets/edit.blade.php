<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Mascota — Mi Área</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
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
					<input name="species" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('species', $pet->species) }}">
				</div>
				<div>
					<label class="text-sm">Raza</label>
					<input name="breed" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('breed', $pet->breed) }}">
				</div>
				<div>
					<label class="text-sm">Edad</label>
					<input name="age" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('age', $pet->age) }}">
				</div>
				<div>
					<label class="text-sm">Tamaño</label>
					<input name="size" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('size', $pet->size) }}">
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
				<textarea name="story" rows="5" class="mt-1 block w-full rounded-xl border-neutral-mid/40">{{ old('story', $pet->story) }}</textarea>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Estado</label>
					<select name="status" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						@php $st = old('status', $pet->status); @endphp
						<option value="draft" @selected($st==='draft')>Borrador</option>
						<option value="published" @selected($st==='published')>Publicado</option>
						<option value="archived" @selected($st==='archived')>Archivado</option>
					</select>
				</div>
				<div>
					<label class="text-sm">Imagen de portada</label>
					<input name="cover_image" type="file" accept="image/*" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
					@if($pet->cover_image)
						<img src="{{ asset('storage/'.$pet->cover_image) }}" alt="{{ $pet->name }}" class="mt-2 w-40 h-28 object-cover rounded-xl border border-neutral-mid/40">
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
</body>
</html>
