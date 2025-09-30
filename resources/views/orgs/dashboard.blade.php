<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Panel de Organización — {{ config('app.name', 'MyPetMatch') }}</title>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2" />
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<style>
			@keyframes fadeUp { from { opacity: 0; transform: translateY(12px);} to { opacity: 1; transform: translateY(0);} }
			.animate-fade-up { animation: fadeUp .55s ease-out both; }
		</style>
	</head>
	<body class="font-poppins antialiased bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
		@include('partials.header')

		<main class="flex-1 py-8">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<!-- Guard para datos dinámicos (evita inyectar Blade directo en JS) -->
				<div id="org-guard"
				     data-incomplete="{{ ($orgProfileIncomplete ?? false) ? '1' : '0' }}"
				     data-missing="{{ htmlspecialchars(json_encode($orgMissingLabels ?? []), ENT_QUOTES, 'UTF-8') }}"
				     class="hidden"></div>
				<!-- Heading -->
				<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
					<div>
						<h1 class="text-2xl md:text-3xl font-semibold">Panel de la Organización</h1>
						<p class="text-sm text-neutral-dark/70 dark:text-neutral-300">Bienvenido{{ Auth::user() ? ', ' . Auth::user()->name : '' }}. Aquí verás un resumen de tus mascotas y solicitudes.</p>
					</div>
					<div class="flex flex-wrap items-center gap-2">
						<a href="{{ route('orgs.pets.create') }}" class="btn btn-primary text-sm">+ Nueva Mascota</a>
						<a href="{{ route('submissions.index') }}" class="btn btn-secondary text-sm">Ver Solicitudes</a>
					</div>
				</div>

				<!-- Stats Cards -->
				<section class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Total Mascotas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['pets_total'] ?? 0 }}</p>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.05s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Publicadas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['pets_published'] ?? '—' }}</p>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.1s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Solicitudes Activas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['applications_active'] ?? '—' }}</p>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.15s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Solicitudes Totales</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['applications_total'] ?? 0 }}</p>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.2s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Aprobadas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['applications_approved'] ?? '—' }}</p>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.25s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Rechazadas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $stats['applications_rejected'] ?? '—' }}</p>
					</div>
				</section>

				<!-- Breakdown + Chart placeholder -->
				<section class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
					<div class="lg:col-span-2 bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
						<div class="flex items-center justify-between">
							<h2 class="text-lg font-semibold">Actividad Reciente</h2>
							<div class="flex items-center gap-2">
								<a href="{{ route('submissions.index') }}" class="text-sm text-primary hover:underline">Ver todas</a>
							</div>
						</div>

						<!-- Filtros rápidos por estado -->
						<div class="mt-3 flex flex-wrap gap-2">
							@php $chips = [
								['key'=>'pending','label'=>'Pendientes'],
								['key'=>'under_review','label'=>'En revisión'],
								['key'=>'approved','label'=>'Aprobadas'],
								['key'=>'rejected','label'=>'Rechazadas'],
							]; @endphp
							@foreach($chips as $c)
								<a href="{{ route('submissions.index', ['status' => $c['key']]) }}"
								   class="badge {{ in_array($c['key'], ['approved']) ? 'badge-primary' : (in_array($c['key'], ['rejected']) ? 'badge-danger' : 'bg-neutral-mid/20 text-neutral-dark') }}">
									{{ $c['label'] }}
								</a>
							@endforeach
						</div>
						<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
							<div>
								<h3 class="text-sm font-medium text-neutral-dark/80 dark:text-neutral-200">Últimas Mascotas</h3>
								<ul class="mt-3 space-y-3">
									@forelse($recentPets as $pet)
										<li class="p-3 rounded-xl border border-neutral-mid/30 hover:shadow-card transition">
											<div class="flex items-center justify-between">
												<div>
													<p class="font-medium">{{ $pet->name ?? 'Mascota #' . $pet->id }}</p>
													<p class="text-xs text-neutral-dark/70">{{ $pet->created_at?->diffForHumans() }}</p>
												</div>
												<a href="{{ route('orgs.pets.edit', $pet->id) }}" class="text-sm text-primary hover:underline">Editar</a>
											</div>
										</li>
									@empty
										<li class="text-sm text-neutral-dark/70">Aún no hay mascotas.</li>
									@endforelse
								</ul>
							</div>
							<div>
								<h3 class="text-sm font-medium text-neutral-dark/80 dark:text-neutral-200">Últimas Solicitudes</h3>
								<ul class="mt-3 space-y-3">
									@forelse($recentApps as $app)
										<li class="p-3 rounded-xl border border-neutral-mid/30 hover:shadow-card transition">
											<div class="flex items-center justify-between gap-4">
												<div>
													<p class="font-medium">Solicitud #{{ $app->id }}</p>
													<p class="text-xs text-neutral-dark/70">{{ $app->created_at?->diffForHumans() }}</p>
													<p class="text-xs text-neutral-dark/70 mt-1">Mascota: <span class="font-medium">{{ $app->pet->name ?? 'N/D' }}</span> • Adoptante: <span class="font-medium">{{ $app->user->name ?? 'N/D' }}</span></p>
												</div>
												<a href="{{ route('submissions.show', $app->id) }}" class="text-sm text-primary hover:underline">Ver</a>
											</div>
										</li>
									@empty
										<li class="text-sm text-neutral-dark/70">Aún no hay solicitudes.</li>
									@endforelse
								</ul>
							</div>
						</div>
					</div>
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
						<h2 class="text-lg font-semibold">Estado de Solicitudes</h2>
						<ul class="mt-3 space-y-2 text-sm">
							@php $labels = ['pending' => 'Pendiente', 'under_review' => 'Revisión', 'approved' => 'Aprobada', 'rejected' => 'Rechazada']; @endphp
							@forelse($statusBreakdown as $status => $total)
								<li class="flex items-center justify-between">
									<span class="capitalize">{{ $labels[$status] ?? ucfirst($status) }}</span>
									<span class="font-medium">{{ $total }}</span>
								</li>
							@empty
								<li class="text-neutral-dark/70">Sin datos aún</li>
							@endforelse
						</ul>
						<div class="mt-4 text-xs text-neutral-dark/70">Consejo: atiende primero las pendientes.</div>
					</div>
				</section>

				<!-- Quick Actions -->
				<section class="mt-8">
					<h2 class="text-lg font-semibold">Acciones rápidas</h2>
					<div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
						<a href="{{ route('orgs.pets.create') }}" class="p-5 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition block">
							<p class="font-medium">Agregar Mascota</p>
							<p class="text-xs text-neutral-dark/70 mt-1">Crea un nuevo perfil para adopción.</p>
						</a>
						<a href="{{ route('orgs.pets.index') }}" class="p-5 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition block">
							<p class="font-medium">Gestionar Mascotas</p>
							<p class="text-xs text-neutral-dark/70 mt-1">Edita, publica o archiva mascotas.</p>
						</a>
						<a href="{{ route('submissions.index') }}" class="p-5 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition block">
							<p class="font-medium">Revisar Solicitudes</p>
							<p class="text-xs text-neutral-dark/70 mt-1">Procesa y responde a adoptantes.</p>
						</a>
						<a href="{{ route('features') }}" class="p-5 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark hover:shadow-card transition block">
							<p class="font-medium">Ver Cómo Funciona</p>
							<p class="text-xs text-neutral-dark/70 mt-1">Conoce todas las capacidades.</p>
						</a>
					</div>
				</section>
			</div>
		</main>

		<script>
			// Mostrar alerta si el perfil de la organización está incompleto
			window.addEventListener('DOMContentLoaded', function () {
				const guard = document.getElementById('org-guard');
				const incomplete = (guard?.dataset.incomplete === '1');
				let missing = [];
				try { missing = JSON.parse(guard?.dataset.missing || '[]'); } catch (e) { missing = []; }
				if (incomplete) {
					let html = '';
					if (missing && missing.length) {
						html += '<ul style="text-align:left">' + missing.map(m => `<li>• ${m}</li>`).join('') + '</ul>';
					} else {
						html = '<p>Completa tu perfil para que los adoptantes puedan contactarte.</p>';
					}
					Swal.fire({
						title: 'Completa tu perfil de organización',
						html,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ir a mi perfil',
						cancelButtonText: 'Luego',
						confirmButtonColor: '#05706C',
						cancelButtonColor: '#C9D1D9',
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = "{{ route('profile.edit') }}" + '#organizacion';
						}
					});
				}
			});
		</script>
		@include('partials.footer')
	</body>
	</html>
