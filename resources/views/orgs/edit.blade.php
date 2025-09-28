<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Mascota — Panel</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
    @include('partials.header')
	<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-semibold">Editar Mascota</h1>
			<a href="{{ route('orgs.pets.index') }}" class="text-sm text-primary">Volver</a>
		</div>

		<form action="{{ route('orgs.pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5 bg-white dark:bg-neutral-dark p-6 rounded-2xl border border-neutral-mid/30">
			@csrf
			@method('PUT')
			<div>
				<label class="text-sm">Nombre</label>
				<input type="text" name="name" value="{{ old('name', $pet->name) }}" class="mt-1 w-full rounded-xl border-neutral-mid/50" required>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Especie</label>
					<input type="text" name="species" value="{{ old('species', $pet->species) }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Raza</label>
					<input type="text" name="breed" value="{{ old('breed', $pet->breed) }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
				<div>
					<label class="text-sm">Edad</label>
					<input type="text" name="age" value="{{ old('age', $pet->age) }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Tamaño</label>
					<input type="text" name="size" value="{{ old('size', $pet->size) }}" class="mt-1 w-full rounded-xl border-neutral-mid/50">
				</div>
				<div>
					<label class="text-sm">Sexo</label>
					<select name="sex" class="mt-1 w-full rounded-xl border-neutral-mid/50">
						<option value="" {{ old('sex', $pet->sex)==='' ? 'selected' : '' }}>—</option>
						<option value="male" {{ old('sex', $pet->sex)==='male' ? 'selected' : '' }}>Macho</option>
						<option value="female" {{ old('sex', $pet->sex)==='female' ? 'selected' : '' }}>Hembra</option>
						<option value="unknown" {{ old('sex', $pet->sex)==='unknown' ? 'selected' : '' }}>Desconocido</option>
					</select>
				</div>
			</div>
			<div>
				<label class="text-sm">Historia</label>
				<textarea name="story" rows="5" class="mt-1 w-full rounded-xl border-neutral-mid/50">{{ old('story', $pet->story) }}</textarea>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Estado</label>
					<select name="status" class="mt-1 w-full rounded-xl border-neutral-mid/50">
						@foreach(['draft'=>'Borrador','published'=>'Publicado','archived'=>'Archivado'] as $v=>$label)
						<option value="{{ $v }}" {{ old('status', $pet->status)===$v ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="text-sm">Portada</label>
					<input type="file" name="cover_image" accept="image/*" class="mt-1 w-full rounded-xl border-neutral-mid/50">
					@if($pet->cover_image)
						<img src="{{ asset('storage/'.$pet->cover_image) }}" class="mt-2 w-40 h-28 object-cover rounded-xl" alt="portada">
					@endif
				</div>
			</div>

			<div class="pt-2 flex items-center justify-between">
				<button class="btn btn-primary">Guardar</button>
				<form action="{{ route('orgs.pets.destroy', $pet->id) }}" method="POST" onsubmit="return confirm('¿Eliminar mascota?')">
					@csrf
					@method('DELETE')
					<button class="btn btn-danger">Eliminar</button>
				</form>
			</div>
		</form>
	</div>
</body>
</html>
