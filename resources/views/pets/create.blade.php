<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nueva Mascota — Mi Área</title>
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
			<h1 class="text-2xl font-semibold">Nueva Mascota</h1>
			<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Volver a la lista</a>
		</div>

		<form class="mt-6 space-y-5" method="POST" action="{{ route('orgs.pets.store') }}" enctype="multipart/form-data">
			@csrf
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Nombre</label>
					<input name="name" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('name') }}" required>
					@error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
				</div>
				<div>
					<label class="text-sm">Especie</label>
					<input name="species" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('species') }}">
				</div>
				<div>
					<label class="text-sm">Raza</label>
					<input name="breed" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('breed') }}">
				</div>
				<div>
					<label class="text-sm">Edad</label>
					<input name="age" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('age') }}">
				</div>
				<div>
					<label class="text-sm">Tamaño</label>
					<input name="size" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('size') }}">
				</div>
				<div>
					<label class="text-sm">Sexo</label>
					<select name="sex" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						<option value="">—</option>
						<option value="male" @selected(old('sex')==='male')>Macho</option>
						<option value="female" @selected(old('sex')==='female')>Hembra</option>
						<option value="unknown" @selected(old('sex')==='unknown')>Desconocido</option>
					</select>
				</div>
			</div>

			<div>
				<label class="text-sm">Historia</label>
				<textarea name="story" rows="5" class="mt-1 block w-full rounded-xl border-neutral-mid/40">{{ old('story') }}</textarea>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Estado</label>
					<select name="status" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
						<option value="draft" @selected(old('status')==='draft')>Borrador</option>
						<option value="published" @selected(old('status')==='published')>Publicado</option>
						<option value="archived" @selected(old('status')==='archived')>Archivado</option>
					</select>
				</div>
				<div>
					<label class="text-sm">Imagen de portada</label>
					<input name="cover_image" type="file" accept="image/*" class="mt-1 block w-full rounded-xl border-neutral-mid/40">
					@error('cover_image')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
				</div>
			</div>

			<div class="pt-2 flex items-center gap-3">
				<button class="btn btn-primary" type="submit">Crear</button>
				<a href="{{ route('orgs.pets.index') }}" class="text-sm hover:text-primary">Cancelar</a>
			</div>
		</form>
	</div>
</body>
</html>
