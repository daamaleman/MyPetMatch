<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nueva Mascota — Panel</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
    @include('partials.header')
	<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">Agregar Mascota</h1>
			<a href="{{ route('orgs.pets.index') }}" class="text-sm text-primary">Volver</a>
		</div>

		<form action="{{ route('orgs.pets.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5 bg-white dark:bg-neutral-dark p-6 rounded-2xl border border-neutral-mid/30">
			@csrf
			<div>
				<label class="text-sm">Nombre</label>
				<input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-xl border-neutral-mid/50" required>
				@error('name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Especie</label>
					<input type="text" name="species" value="{{ old('species') }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Raza</label>
					<input type="text" name="breed" value="{{ old('breed') }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
				<div>
					<label class="text-sm">Edad</label>
					<input type="text" name="age" value="{{ old('age') }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Tamaño</label>
					<input type="text" name="size" value="{{ old('size') }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Sexo</label>
					<select name="sex" class="mt-1 w-full rounded-xl border-neutral-mid/50">
						<option value="">—</option>
						<option value="male">Macho</option>
						<option value="female">Hembra</option>
						<option value="unknown">Desconocido</option>
					</select>
				</div>
			</div>
			<div>
				<label class="text-sm">Historia</label>
				<textarea name="story" rows="5" class="mt-1 w-full rounded-xl border-neutral-mid/50">{{ old('story') }}</textarea>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Estado</label>
					<select name="status" class="mt-1 w-full rounded-xl border-neutral-mid/50">
						<option value="draft">Borrador</option>
						<option value="published">Publicado</option>
						<option value="archived">Archivado</option>
					</select>
				</div>
				<div>
					<label class="text-sm">Portada</label>
					<input type="file" name="cover_image" accept="image/*" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
			</div>

			<div class="pt-2">
				<button class="btn btn-primary">Guardar</button>
			</div>
		</form>
	</div>
</body>
</html>
