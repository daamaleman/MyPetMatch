<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mi Área — Adoptante</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light">
	<style>
		html {
			scroll-behavior: smooth
		}
	</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body class="font-poppins antialiased bg-neutral-light text-neutral-dark min-h-screen flex flex-col">
	@include('partials.header')

	<main class="flex-1">
		<section class="pt-4 pb-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<h1 class="text-3xl font-semibold tracking-tight">Mi Área</h1>
				<p class="mt-2 text-sm text-neutral-dark/80">Gestiona tus solicitudes de adopción y tu perfil.</p>
			</div>
		</section>

		<section class="py-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Solicitudes activas</p>
						<p class="text-3xl font-semibold mt-1">0</p>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Aprobadas</p>
						<p class="text-3xl font-semibold mt-1">0</p>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Rechazadas</p>
						<p class="text-3xl font-semibold mt-1">0</p>
					</div>
				</div>
			</div>
		</section>

		<section class="py-4">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
					<div>
						<h2 class="text-lg font-semibold">¿Listo para adoptar?</h2>
						<p class="text-sm text-neutral-dark/70 mt-1">Explora las mascotas disponibles y envía tu solicitud.</p>
					</div>
					<a href="{{ route('adoptions.browse') }}" class="btn btn-primary">Ver mascotas disponibles</a>
				</div>
			</div>
		</section>
	</main>

	<script>
		// Forzar modo claro en esta vista
		document.documentElement.classList.remove('dark');
	</script>
</body>

</html>