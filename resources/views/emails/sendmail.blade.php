<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{{ $details['subject'] ?? 'Mensaje' }}</title>
	<style>
		/* Brand-ish palette */
		:root { --brand:#05706C; --ink:#111827; --muted:#6b7280; --border:#e5e7eb; --bg:#f9fafb; }
		body { margin:0; padding:24px 0; background:var(--bg); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif; color:var(--ink); }
		.wrapper { width:100%; }
		.container { max-width: 640px; margin: 0 auto; padding: 0 16px; }
		.card { border:1px solid var(--border); border-radius: 12px; padding: 20px; background:#fff; }
		.header { text-align:center; padding: 8px 0 16px; }
		.logo { font-size: 18px; font-weight:700; color:var(--brand); text-decoration:none; }
		.h1 { font-size: 22px; font-weight: 700; margin: 0 0 8px; color:var(--ink); }
		.p { margin: 0 0 8px; line-height:1.5; }
		.muted { color:var(--muted); font-size: 12px; }
		.btn { display:inline-block; padding: 10px 14px; background:var(--brand); color:#fff !important; border-radius: 8px; text-decoration:none; font-weight:600; }
		.table { width:100%; border-collapse: collapse; }
		.table th, .table td { padding:8px 8px; border-top:1px solid var(--border); text-align:left; font-size: 14px; vertical-align: top; }
		.footer { text-align:center; margin-top: 18px; }
	</style>
</head>
<body>
	<div class="wrapper">
		<div class="container">
			<div class="header">
				<a class="logo" href="{{ config('app.url') }}" target="_blank" rel="noopener">{{ config('app.name', 'MyPetMatch') }}</a>
			</div>
			<div class="card">
				<h1 class="h1">{{ $details['title'] ?? 'Notificación' }}</h1>
				@isset($details['intro'])
					<p class="p">{{ $details['intro'] }}</p>
				@endisset

				@isset($details['summary'])
					<div class="card" style="margin:12px 0">
						{!! $details['summary'] !!}
					</div>
				@endisset

				@isset($details['items'])
					<table class="table" style="margin-top:12px">
						<tbody>
						@foreach($details['items'] as $label => $value)
							<tr>
								<th style="width:40%">{{ $label }}</th>
								<td>{{ $value }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				@endisset

				@isset($details['cta'])
					<p style="margin-top:16px">
						<a href="{{ $details['cta']['href'] ?? '#' }}" class="btn">{{ $details['cta']['label'] ?? 'Ver detalles' }}</a>
					</p>
				@endisset
			</div>

			<div class="footer">
				@isset($details['footer'])
					<p class="muted">{{ $details['footer'] }}</p>
				@else
					<p class="muted">Este es un mensaje automático de {{ config('app.name') }}. Por favor no responda a este correo.</p>
				@endisset
			</div>
		</div>
	</div>
</body>
</html>
