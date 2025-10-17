<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Reportes — {{ config('app.name', 'MyPetMatch') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white min-h-screen flex flex-col">
	@include('partials.header')

	<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
		<h1 class="text-2xl font-semibold">Reportes de organización</h1>

		<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
			<!-- Filtros para gráficos -->
			<section class="lg:col-span-1 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-4">
				<h2 class="font-semibold">Filtros de gráficos</h2>
				<form method="GET" action="{{ route('orgs.reports') }}" class="space-y-3">
					<div class="grid grid-cols-2 gap-3">
						<div>
							<label class="text-sm">Desde</label>
							<input type="date" name="c_from" value="{{ request('c_from', $filtersCharts['from'] ?? '') }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
						</div>
						<div>
							<label class="text-sm">Hasta</label>
							<input type="date" name="c_to" value="{{ request('c_to', $filtersCharts['to'] ?? '') }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
						</div>
					</div>
					<div>
						<label class="text-sm">Intervalo</label>
						<select name="c_interval" class="mt-1 w-full rounded-xl border-neutral-mid/40">
							@php $ival = request('c_interval', $filtersCharts['interval'] ?? 'week'); @endphp
							<option value="week" @selected($ival==='week')>Semanas</option>
							<option value="month" @selected($ival==='month')>Meses</option>
						</select>
					</div>
					<div>
						<label class="text-sm">Estados</label>
						<select name="c_status[]" multiple class="mt-1 w-full rounded-xl border-neutral-mid/40">
							@php $cStates = ['submitted'=>'Enviada','pending'=>'Pendiente','under_review'=>'En revisión','approved'=>'Aprobada','rejected'=>'Rechazada','adopted'=>'Adoptada']; @endphp
							@foreach($cStates as $k=>$lbl)
								<option value="{{ $k }}" @selected(in_array($k, request('c_status', $filtersCharts['status'] ?? [])))>{{ $lbl }}</option>
							@endforeach
						</select>
						<p class="text-xs text-neutral-dark/60 mt-1">Mantén presionado Ctrl/Cmd para seleccionar varios.</p>
					</div>
					<button class="btn btn-primary">Aplicar a gráficos</button>
				</form>
			</section>

			<!-- Gráficos -->
			<section class="lg:col-span-2 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-4">
				<h2 class="font-semibold">Gráficos</h2>
				<div class="grid grid-cols-1 gap-6 mt-3">
					<div>
						@php $ival = request('c_interval', $filtersCharts['interval'] ?? 'week'); @endphp
						<h3 class="text-sm text-neutral-dark/70">Tendencia de solicitudes por {{ $ival==='month' ? 'mes' : 'semana' }}</h3>
						<canvas id="chartTrend" height="120"></canvas>
					</div>
					<div>
						<h3 class="text-sm text-neutral-dark/70">Distribución por estado</h3>
						<canvas id="chartStatus" height="120"></canvas>
					</div>
				</div>
			</section>
		</div>

		<!-- Filtros de tabla y tabla -->
		<section class="mt-6 rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-4">
			<h2 class="font-semibold">Solicitudes (tabla)</h2>
			<form method="GET" action="{{ route('orgs.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-2">
				<div>
					<label class="text-sm">Desde</label>
					<input type="date" name="t_from" value="{{ request('t_from', $filtersTables['from'] ?? '') }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
				</div>
				<div>
					<label class="text-sm">Hasta</label>
					<input type="date" name="t_to" value="{{ request('t_to', $filtersTables['to'] ?? '') }}" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
				</div>
				<div>
					<label class="text-sm">Estado</label>
					<select name="t_status" class="mt-1 w-full rounded-xl border-neutral-mid/40">
						<option value="">Todos</option>
						@foreach(['submitted'=>'Enviada','pending'=>'Pendiente','under_review'=>'En revisión','approved'=>'Aprobada','rejected'=>'Rechazada','adopted'=>'Adoptada'] as $k=>$lbl)
							<option value="{{ $k }}" @selected(request('t_status', $filtersTables['status'] ?? '')===$k)>{{ $lbl }}</option>
						@endforeach
					</select>
				</div>
				<div>
					<label class="text-sm">Buscar</label>
					<input type="text" name="t_q" value="{{ request('t_q', $filtersTables['q'] ?? '') }}" placeholder="Mascota o adoptante" class="mt-1 w-full rounded-xl border-neutral-mid/40" />
				</div>
				<div class="md:col-span-4">
					<button class="btn btn-primary">Aplicar a tabla</button>
				</div>
			</form>

			<div class="mt-4 overflow-x-auto">
				<table class="min-w-full text-sm">
					<thead class="text-left text-neutral-dark/70">
						<tr>
							<th class="py-2">Fecha</th>
							<th class="py-2">Mascota</th>
							<th class="py-2">Adoptante</th>
							<th class="py-2">Estado</th>
						</tr>
					</thead>
					<tbody>
					@forelse(($tables['rows'] ?? []) as $row)
						<tr class="border-t border-neutral-mid/20">
							<td class="py-2">{{ $row->created_at->format('Y-m-d') }}</td>
							<td class="py-2">{{ $row->pet->name ?? '—' }}</td>
							<td class="py-2">{{ $row->user->name ?? '—' }}</td>
							<td class="py-2">{{ ucfirst(str_replace('_',' ',$row->status)) }}</td>
						</tr>
					@empty
						<tr><td colspan="4" class="py-4 text-center text-neutral-dark/60">Sin resultados</td></tr>
					@endforelse
					</tbody>
				</table>
				@if(($tables['rows'] ?? null) && method_exists($tables['rows'], 'links'))
					<div class="mt-3">{{ $tables['rows']->links() }}</div>
				@endif
			</div>
		</section>
	</main>

	<!-- Embed chart data as JSON to avoid Blade-in-JS parsing issues -->
	<script type="application/json" id="trend-data-json">{!! json_encode(array_values($charts['trend'] ?? [])) !!}</script>
	<script type="application/json" id="trend-labels-json">{!! json_encode(array_keys($charts['trend'] ?? [])) !!}</script>
	<script type="application/json" id="status-data-json">{!! json_encode(array_values($charts['status'] ?? [])) !!}</script>
	<script type="application/json" id="status-labels-json">{!! json_encode(array_keys($charts['status'] ?? [])) !!}</script>

	<script>
	(function(){
		function readJson(id){
			const el = document.getElementById(id);
			if(!el) return [];
			try { return JSON.parse(el.textContent || '[]'); } catch(e){ return []; }
		}

		const trendData = readJson('trend-data-json');
		const trendLabels = readJson('trend-labels-json');
		const statusData = readJson('status-data-json');
		const statusLabels = readJson('status-labels-json');
		const brand = '#05706C';

		const trendCanvas = document.getElementById('chartTrend');
		if (trendCanvas && typeof Chart !== 'undefined') {
			new Chart(trendCanvas, {
				type: 'line',
				data: {
					labels: trendLabels,
					datasets: [{
						label: 'Solicitudes', data: trendData,
						borderColor: brand, backgroundColor: brand+'33', fill: true, tension: .3
					}]
				},
				options: { plugins: { legend: { display:false } }, scales: { x: { grid: { display:false } }, y: { beginAtZero:true } } }
			});
		}

		const statusCanvas = document.getElementById('chartStatus');
		if (statusCanvas && typeof Chart !== 'undefined') {
			new Chart(statusCanvas, {
				type: 'doughnut',
				data: {
					labels: statusLabels,
					datasets: [{ data: statusData, backgroundColor: ['#05706C','#0ea5a3','#f59e0b','#10b981','#ef4444','#6366f1'] }]
				},
				options: { plugins: { legend: { position: 'bottom' } } }
			});
		}
	})();
	</script>
	@include('partials.footer')
</body>
</html>
