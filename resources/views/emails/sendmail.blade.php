<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<title>{{ $details['subject'] ?? 'Mensaje' }}</title>
	<style>
		/* Mejores resets para emails */
		table { border-collapse: collapse; }
		img { border: 0; line-height: 100%; vertical-align: middle; }
		a { text-decoration: none; }
		@media only screen and (max-width: 600px) {
			.container { width: 100% !important; }
			.px { padding-left: 16px !important; padding-right: 16px !important; }
		}
	</style>
</head>
@php
	$appName = config('app.name', 'MyPetMatch');
	$logoUrl = $details['logo_url'] ?? null; // usar URL absoluta si se provee
	$preheader = $details['preheader'] ?? ($details['intro'] ?? '');
@endphp
<body style="margin:0; padding:0; background:#f9fafb;">
	<!-- Preheader (oculto) -->
	<div style="display:none; max-height:0; overflow:hidden; opacity:0;">
		{{ Str::limit(strip_tags($preheader), 140) }}
	</div>

	<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;">
		<tr>
			<td align="center" style="padding: 24px 0;">
				<table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px;">
					<!-- Header -->
					<tr>
						<td align="center" class="px" style="padding: 4px 24px 16px 24px;">
							@if($logoUrl)
								<a href="{{ config('app.url') }}" target="_blank" rel="noopener">
									<img src="{{ $logoUrl }}" alt="{{ $appName }}" width="160" style="display:block; max-width:160px; height:auto;" />
								</a>
							@else
								<a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif; font-size:20px; font-weight:700; color:#05706C;">
									{{ $appName }}
								</a>
							@endif
						</td>
					</tr>

					<!-- Card -->
					<tr>
						<td class="px" style="padding: 0 24px;">
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff; border:1px solid #e5e7eb; border-radius:12px;">
								<tr>
									<td style="padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif; color:#111827;">
										@isset($details['hero_url'])
										<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 12px 0;">
											<tr>
												<td>
													<img src="{{ $details['hero_url'] }}" alt="" width="100%" style="display:block; width:100%; height:auto; border-radius:10px;" />
												</td>
											</tr>
										</table>
										@endisset

										<h1 style="margin:0 0 8px 0; font-size:22px; line-height:1.3; font-weight:700;">{{ $details['title'] ?? 'Notificación' }}</h1>
										@isset($details['intro'])
											<p style="margin:0 0 8px 0; line-height:1.6;">{{ $details['intro'] }}</p>
										@endisset

										@isset($details['summary'])
											<div style="margin:12px 0; padding:12px; border:1px solid #e5e7eb; border-radius:10px;">
												{!! $details['summary'] !!}
											</div>
										@endisset

										@isset($details['items'])
											<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px;">
												<tbody>
												@foreach($details['items'] as $label => $value)
													<tr>
														<th align="left" style="padding:8px 8px 8px 0; border-top:1px solid #e5e7eb; width:40%; font-size:14px; color:#6b7280; font-weight:600; vertical-align:top;">{{ $label }}</th>
														<td style="padding:8px 0 8px 0; border-top:1px solid #e5e7eb; font-size:14px; color:#111827;">{{ $value }}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										@endisset

										@isset($details['cta'])
											<table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:16px;">
												<tr>
													<td>
														<!--[if mso]>
														<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $details['cta']['href'] ?? '#' }}" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="12%" stroke="f" fillcolor="{{ $brand }}">
															<w:anchorlock/>
															<center style="color:#ffffff;font-family:Arial, sans-serif;font-size:14px;font-weight:bold;">{{ $details['cta']['label'] ?? 'Ver detalles' }}</center>
														</v:roundrect>
														<![endif]-->
														<a href="{{ $details['cta']['href'] ?? '#' }}" style="background:#05706C; border-radius:8px; color:#ffffff; display:inline-block; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif; font-weight:700; line-height:40px; text-align:center; text-decoration:none; width:200px; -webkit-text-size-adjust:none;">
															{{ $details['cta']['label'] ?? 'Ver detalles' }}
														</a>
													</td>
												</tr>
											</table>
										@endisset
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<!-- Footer -->
					<tr>
						<td class="px" align="center" style="padding: 16px 24px;">
							<p style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif; font-size:12px; color:#6b7280;">
								@isset($details['footer'])
									{{ $details['footer'] }}
								@else
									Este es un mensaje automático de {{ $appName }}. Por favor no responda a este correo.
								@endisset
							</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
