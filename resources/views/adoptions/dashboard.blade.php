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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-poppins antialiased bg-neutral-light text-neutral-dark min-h-screen flex flex-col">
	@include('partials.header')

	<main class="flex-1">
		@php
			use App\Models\AdoptionApplication;
			use App\Models\AdopterProfile;
			use App\Models\Pet;
			$uid = auth()->id();
			$activeCount = AdoptionApplication::where('user_id', $uid)
				->whereIn('status', ['pending','under_review'])->count();
			$approvedCount = AdoptionApplication::where('user_id', $uid)
				->where('status', 'approved')->count();
			$rejectedCount = AdoptionApplication::where('user_id', $uid)
				->where('status', 'rejected')->count();

			// Perfil del adoptante: validar campos mínimos
			$profile = AdopterProfile::where('user_id', $uid)->first();
			$missing = [];
			$required = [
				'phone' => 'Teléfono',
				'address_line1' => 'Dirección',
				'city' => 'Ciudad',
				'state' => 'Estado/Provincia',
				'country' => 'País',
			];
			if (!$profile) {
				$missing = array_values($required);
			} else {
				foreach ($required as $key => $label) {
					$val = trim((string)($profile->$key ?? ''));
					if ($val === '') { $missing[] = $label; }
				}
			}
			$adopterIncomplete = count($missing) > 0;

			// Actividad reciente
			$recentApps = AdoptionApplication::where('user_id', $uid)
				->latest()->limit(5)->get();
			$recentPets = Pet::query()->where('status','published')
				->latest()->limit(6)->get();
		@endphp

		<!-- Guard para datos dinámicos (evita inyectar Blade directo en JS) -->
		<div id="adopter-guard"
		     data-incomplete="{{ $adopterIncomplete ? '1':'0' }}"
		     data-missing="{{ htmlspecialchars(json_encode($missing ?? []), ENT_QUOTES, 'UTF-8') }}"
		     class="hidden"></div>

		<section class="pt-4 pb-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<h1 class="text-3xl font-semibold tracking-tight">Mi Área</h1>
				<p class="mt-2 text-sm text-neutral-dark/80">Gestiona tus solicitudes de adopción y tu perfil.</p>
			</div>
		</section>

		<section class="py-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Solicitudes activas</p>
						<p class="text-3xl font-semibold mt-1">{{ $activeCount }}</p>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Aprobadas</p>
						<p class="text-3xl font-semibold mt-1">{{ $approvedCount }}</p>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Rechazadas</p>
						<p class="text-3xl font-semibold mt-1">{{ $rejectedCount }}</p>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<p class="text-sm text-neutral-dark/70">Totales</p>
						<p class="text-3xl font-semibold mt-1">{{ $activeCount + $approvedCount + $rejectedCount }}</p>
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
					<div class="flex flex-wrap items-center gap-2">
						<a href="{{ route('adoptions.browse') }}" class="btn btn-primary">Ver mascotas disponibles</a>
						<a href="{{ route('submissions.index') }}" class="btn">Mis solicitudes</a>
						<a href="{{ route('profile.edit') }}#adoptante" class="btn">Editar mi perfil</a>
					</div>
				</div>
			</div>
		</section>

		<!-- Actividad reciente: solicitudes y mascotas publicadas -->
		<section class="py-2">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
					<div class="lg:col-span-2 rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<div class="flex items-center justify-between">
							<h3 class="text-lg font-semibold">Mis últimas solicitudes</h3>
							<a href="{{ route('submissions.index') }}" class="text-sm text-primary hover:underline">Ver todas</a>
						</div>
						<ul class="mt-4 space-y-3">
							@php $labels = ['pending' => 'Pendiente', 'under_review' => 'Revisión', 'approved' => 'Aprobada', 'rejected' => 'Rechazada']; @endphp
							@forelse($recentApps as $app)
								<li class="p-3 rounded-xl border border-neutral-mid/30 hover:shadow-card transition">
									<div class="flex items-center justify-between">
										<div>
											<p class="font-medium">Solicitud #{{ $app->id }} — <span class="text-sm text-neutral-dark/70">{{ $labels[$app->status] ?? ucfirst($app->status) }}</span></p>
											<p class="text-xs text-neutral-dark/70">{{ $app->created_at?->diffForHumans() }}</p>
										</div>
										<a href="{{ route('submissions.show', $app->id) }}" class="text-sm text-primary hover:underline">Ver</a>
									</div>
								</li>
							@empty
								<li class="text-sm text-neutral-dark/70">Aún no has enviado solicitudes.</li>
							@endforelse
						</ul>
					</div>
					<div class="rounded-2xl border border-neutral-mid/30 bg-white shadow-card p-6">
						<h3 class="text-lg font-semibold">Mascotas recientes</h3>
						<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
							@forelse($recentPets as $pet)
								<a href="{{ route('pets.details', $pet->id) }}" class="p-3 rounded-xl border border-neutral-mid/30 hover:shadow-card transition block">
									<p class="font-medium">{{ $pet->name ?? 'Mascota #' . $pet->id }}</p>
									<p class="text-xs text-neutral-dark/70">{{ $pet->species ?? 'Mascota' }} • {{ $pet->created_at?->diffForHumans() }}</p>
								</a>
							@empty
								<p class="text-sm text-neutral-dark/70">Cuando haya nuevas mascotas publicadas, aparecerán aquí.</p>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	@include('partials.footer')

	<script>
		// Forzar modo claro en esta vista
		document.documentElement.classList.remove('dark');

		// Mostrar alerta si el perfil del adoptante está incompleto
		window.addEventListener('DOMContentLoaded', function () {
			const guard = document.getElementById('adopter-guard');
			const incomplete = (guard?.dataset.incomplete === '1');
			let missing = [];
			try { missing = JSON.parse(guard?.dataset.missing || '[]'); } catch (e) { missing = []; }
			if (incomplete) {
				let html = '';
				if (missing && missing.length) {
					html += '<ul style="text-align:left">' + missing.map(m => `<li>• ${m}</li>`).join('') + '</ul>';
				} else {
					html = '<p>Completa tu perfil para poder postular a adopciones.</p>';
				}
				Swal.fire({
					title: 'Completa tu perfil de adoptante',
					html,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Ir a mi perfil',
					cancelButtonText: 'Luego',
					confirmButtonColor: '#05706C',
					cancelButtonColor: '#C9D1D9',
				}).then((result) => {
					if (result.isConfirmed) {
						window.location.href = "{{ route('profile.edit') }}" + '#adoptante';
					}
				});
			}
		});
	</script>
</body>

</html>