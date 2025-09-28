<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mi Panel — Adoptante</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="color-scheme" content="light dark">
	<style>html{scroll-behavior:smooth}</style>
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
	@include('partials.header')

	<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
		<h1 class="text-2xl font-semibold">Mi Panel</h1>
		<p class="mt-2 text-sm text-neutral-dark/80">Desde aquí podrás gestionar tus solicitudes de adopción y tu perfil.</p>

		<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
			<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
				<p class="text-sm text-neutral-dark/70">Solicitudes activas</p>
				<p class="text-3xl font-semibold mt-1">0</p>
			</div>
			<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
				<p class="text-sm text-neutral-dark/70">Aprobadas</p>
				<p class="text-3xl font-semibold mt-1">0</p>
			</div>
			<div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
				<p class="text-sm text-neutral-dark/70">Rechazadas</p>
				<p class="text-3xl font-semibold mt-1">0</p>
			</div>
		</div>
	</main>
	<script>document.documentElement.classList.add(localStorage.theme||'');</script>
</body>
</html>
