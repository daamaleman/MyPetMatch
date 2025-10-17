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

				<!-- KPI Cards -->
				<section class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
					<!-- KPI: Mascotas publicadas -->
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Mascotas visibles (publicadas)</p>
						<p class="mt-2 text-3xl font-semibold">{{ $kpis['pets_published'] ?? 0 }}</p>
						@php $pb = $publishedBreakdown ?? []; $sumPB = array_sum($pb); @endphp
						<div class="mt-3 h-2 w-full rounded-full bg-neutral-mid/20 overflow-hidden flex">
							@foreach($pb as $status => $cnt)
								@php $pct = ($sumPB>0? max(3, round(($cnt/$sumPB)*100)) : 0); $color = $status==='available' ? 'bg-emerald-500' : 'bg-primary'; @endphp
								<div class="h-2 {{ $color }}" data-bar-width="{{ $pct }}"></div>
							@endforeach
						</div>
						<div class="mt-2 text-[11px] text-neutral-dark/60">{{ ($publishedBreakdown['available'] ?? 0) }} disponibles • {{ ($publishedBreakdown['published'] ?? 0) }} publicados</div>
					</div>

					<!-- KPI: Solicitudes nuevas 7 días -->
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.05s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Ingresaron últimos 7 días</p>
						<p class="mt-2 text-3xl font-semibold">{{ $kpis['new_apps_7d'] ?? 0 }}</p>
						@php $max7 = (!empty($seriesDailyApps7d) ? max($seriesDailyApps7d) : 1); @endphp
						<div class="mt-3 flex items-end gap-1" style="min-height:32px">
							@foreach($seriesDailyApps7d ?? [] as $d => $v)
								@php $h = $max7>0 ? max(3, round(($v/$max7)*100)) : 0; @endphp
								<div class="w-2 rounded bg-primary" data-bar-height="{{ $h }}" title="{{ $d }}: {{ $v }}"></div>
							@endforeach
						</div>
					</div>

					<!-- KPI: Pendientes -->
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.1s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">En cola de revisión</p>
						<p class="mt-2 text-3xl font-semibold">{{ $kpis['pending_apps'] ?? 0 }}</p>
						@php $pb2 = $pendingBreakdown ?? []; $sumP = array_sum($pb2); @endphp
						<div class="mt-3 h-2 w-full rounded-full bg-neutral-mid/20 overflow-hidden flex">
							@foreach(['submitted'=>'bg-blue-400','pending'=>'bg-amber-400','under_review'=>'bg-purple-400'] as $k=>$color)
								@php $cnt = (int)($pb2[$k] ?? 0); $pct = ($sumP>0? max(3, round(($cnt/$sumP)*100)) : 0); @endphp
								<div class="h-2 {{ $color }}" data-bar-width="{{ $pct }}"></div>
							@endforeach
						</div>
						<div class="mt-2 text-[11px] text-neutral-dark/60">Subm.: {{ $pb2['submitted'] ?? 0 }} • Pend.: {{ $pb2['pending'] ?? 0 }} • Rev.: {{ $pb2['under_review'] ?? 0 }}</div>
					</div>

					<!-- KPI: Confirmadas 30 días -->
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.15s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Cerradas (30 días)</p>
						<p class="mt-2 text-3xl font-semibold">{{ $kpis['confirmed_30d'] ?? 0 }}</p>
						@php $max30 = (!empty($seriesConfirmed30d) ? max($seriesConfirmed30d) : 1); @endphp
						<div class="mt-3 flex items-end gap-1" style="min-height:32px">
							@foreach(array_slice($seriesConfirmed30d ?? [], -14, 14, true) as $d => $v)
								@php $h = $max30>0 ? max(3, round(($v/$max30)*100)) : 0; @endphp
								<div class="w-2 rounded bg-emerald-500" data-bar-height="{{ $h }}" title="{{ $d }}: {{ $v }}"></div>
							@endforeach
						</div>
					</div>

					<!-- KPI: Publicadas +30 días -->
					<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card animate-fade-up" style="animation-delay:.2s">
						<p class="text-xs text-neutral-dark/70 dark:text-neutral-300">Antigüedad de publicadas</p>
						<p class="mt-2 text-3xl font-semibold">{{ $kpis['pets_published_30d'] ?? 0 }}</p>
						@php $pctOld = $publishedAging['percentOld'] ?? 0; @endphp
						<div class="mt-3 h-2 w-full rounded-full bg-neutral-mid/20 overflow-hidden">
							<div class="h-2 bg-amber-500" data-bar-width="{{ max(3, $pctOld) }}"></div>
						</div>
						<div class="mt-2 text-[11px] text-neutral-dark/60">{{ $pctOld }}% con más de 30 días</div>
					</div>
				</section>

				<!-- Filtros de fecha para analíticas -->
				<form method="GET" class="mt-6 bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-4 flex flex-wrap items-end gap-3">
					<div>
						<label for="from" class="text-xs text-neutral-dark/70 dark:text-neutral-300">Desde</label>
						<input id="from" name="from" type="date" class="mt-1 rounded-xl border-neutral-mid/40" value="{{ optional($rangeStart ?? null)->toDateString() }}">
					</div>
					<div>
						<label for="to" class="text-xs text-neutral-dark/70 dark:text-neutral-300">Hasta</label>
						<input id="to" name="to" type="date" class="mt-1 rounded-xl border-neutral-mid/40" value="{{ optional($rangeEnd ?? null)->toDateString() }}">
					</div>
					<div>
						<button class="btn btn-primary">Aplicar</button>
					</div>
				</form>

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
						<h2 class="text-lg font-semibold">Distribución de estados</h2>
						<ul class="mt-3 space-y-2 text-sm">
							@php $labels = ['submitted' => 'Enviada', 'pending' => 'Pendiente', 'under_review' => 'Revisión', 'interview' => 'Entrevista', 'approved' => 'Aprobada', 'adopted' => 'Adoptada', 'rejected' => 'Rechazada']; @endphp
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

			<!-- Analíticas: Embudo, Tendencia, Inventario -->
			<section class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
				<!-- Embudo de adopción -->
				<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card lg:col-span-2">
					<h2 class="text-lg font-semibold">Embudo del proceso de adopción</h2>
					@php $maxFunnel = (!empty($funnel) ? max($funnel) : 1); @endphp
					<div class="mt-4 space-y-2">
						@foreach(['published'=>'Publicadas','con_solicitudes'=>'Con solicitudes','under_review'=>'En revisión','interview'=>'Entrevista','approved'=>'Aprobadas','adopted'=>'Adoptadas'] as $key=>$label)
							@php $val = (int)($funnel[$key] ?? 0); $pct = $maxFunnel > 0 ? max(5, round($val/$maxFunnel*100)) : 0; @endphp
							<div>
								<div class="flex items-center justify-between text-xs">
									<span class="text-neutral-dark/70">{{ $label }}</span>
									<span class="font-medium">{{ $val }}</span>
								</div>
								<div class="mt-1 h-2 rounded-full bg-neutral-mid/20">
									<div class="h-2 rounded-full bg-primary" data-bar-width="{{ $pct }}"></div>
								</div>
							</div>
						@endforeach
					</div>
				</div>

				<!-- Tendencia de solicitudes -->
				<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
					<h2 class="text-lg font-semibold">Tendencia de solicitudes (mensual)</h2>
					@php $maxTrend = (!empty($trend) ? max($trend) : 1); @endphp
					<div class="mt-4 flex items-end gap-1" style="min-height:120px">
						@foreach($trend as $month => $total)
							@php $h = $maxTrend>0 ? max(4, round(($total/$maxTrend)*100)) : 0; @endphp
							<div class="flex flex-col items-center gap-1">
								<div class="w-3 rounded bg-primary" data-bar-height="{{ $h }}"></div>
								<div class="text-[10px] text-neutral-dark/70">{{ \Carbon\Carbon::parse($month)->format('M') }}</div>
							</div>
						@endforeach
					</div>
				</div>
			</section>

			<!-- Inventario por estado de mascota -->
			<section class="mt-8 bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
				<h2 class="text-lg font-semibold">Inventario por estado de mascota</h2>
				@php $maxInv = (!empty($inventory) ? max($inventory) : 1); @endphp
				<div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
					@forelse($inventory as $status => $total)
						@php $pct = $maxInv>0 ? max(5, round(($total/$maxInv)*100)) : 0; @endphp
						<div>
							<div class="flex items-center justify-between text-sm">
								<span class="capitalize">{{ str_replace('_',' ', $status) }}</span>
								<span class="font-medium">{{ $total }}</span>
							</div>
							<div class="mt-1 h-2 rounded-full bg-neutral-mid/20">
								<div class="h-2 rounded-full bg-emerald-500" data-bar-width="{{ $pct }}"></div>
							</div>
						</div>
					@empty
						<p class="text-sm text-neutral-dark/70">Sin datos de inventario.</p>
					@endforelse
				</div>
			</section>

			<!-- Tablas accionables -->
			<section class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
				<!-- Mascotas sin solicitudes -->
				<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
					<h2 class="text-lg font-semibold">Mascotas sin solicitudes</h2>
					<div class="mt-3 overflow-x-auto">
						<table class="min-w-full text-sm">
							<thead>
								<tr class="text-left text-neutral-dark/70">
									<th class="py-2 pe-3">Mascota</th>
									<th class="py-2 pe-3">Fecha publicación</th>
									<th class="py-2 pe-3">Días publicada</th>
									<th class="py-2">Acciones</th>
								</tr>
							</thead>
							<tbody>
								@forelse($petsWithoutApps as $p)
									<tr class="border-t border-neutral-mid/20">
										<td class="py-2 pe-3 font-medium">{{ $p->name ?? ('Mascota #' . $p->id) }}</td>
										<td class="py-2 pe-3">{{ \Carbon\Carbon::parse($p->created_at)->format('Y-m-d') }}</td>
										<td class="py-2 pe-3">{{ $p->days_published }}</td>
										<td class="py-2">
											<a href="{{ route('orgs.pets.edit', $p->id) }}" class="text-primary hover:underline">Editar</a>
										</td>
									</tr>
								@empty
									<tr><td colspan="4" class="py-3 text-neutral-dark/70">No hay mascotas sin solicitudes.</td></tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>

				<!-- Solicitudes estancadas -->
				<div class="bg-white dark:bg-neutral-dark rounded-2xl border border-neutral-mid/30 p-5 shadow-card">
					<h2 class="text-lg font-semibold">Solicitudes estancadas (>7 días)</h2>
					<div class="mt-3 overflow-x-auto">
						<table class="min-w-full text-sm">
							<thead>
								<tr class="text-left text-neutral-dark/70">
									<th class="py-2 pe-3">Mascota</th>
									<th class="py-2 pe-3">Solicitante</th>
									<th class="py-2 pe-3">Estado</th>
									<th class="py-2 pe-3">Última actualización</th>
									<th class="py-2">Acciones</th>
								</tr>
							</thead>
							<tbody>
								@forelse($stuckApps as $a)
									<tr class="border-t border-neutral-mid/20">
										<td class="py-2 pe-3">{{ $a->pet->name ?? ('Mascota #' . $a->pet_id) }}</td>
										<td class="py-2 pe-3">{{ $a->user->name ?? 'N/D' }}</td>
										<td class="py-2 pe-3 capitalize">{{ $a->status }}</td>
										<td class="py-2 pe-3">{{ \Carbon\Carbon::parse($a->updated_at)->diffForHumans() }}</td>
										<td class="py-2">
											<a href="{{ route('submissions.show', $a->id) }}" class="text-primary hover:underline">Ver</a>
										</td>
									</tr>
								@empty
									<tr><td colspan="5" class="py-3 text-neutral-dark/70">No hay solicitudes estancadas.</td></tr>
								@endforelse
							</tbody>
						</table>
					</div>
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

				// Apply bar widths/heights for simple charts
				document.querySelectorAll('[data-bar-width]').forEach(function (el) {
					var w = parseInt(el.getAttribute('data-bar-width') || '0', 10);
					w = Math.max(0, Math.min(100, w));
					el.style.width = w + '%';
				});
				document.querySelectorAll('[data-bar-height]').forEach(function (el) {
					var h = parseInt(el.getAttribute('data-bar-height') || '0', 10);
					h = Math.max(0, Math.min(100, h));
					el.style.height = h + '%';
				});
			});
		</script>
		@include('partials.footer')
	</body>
	</html>
