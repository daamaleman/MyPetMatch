<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MyMailMailable;
use App\Models\AdoptionApplication;
use App\Models\User;

class SendEmailController extends Controller
{
    /**
     * Local-only: send a simple test email to verify SMTP works
     */
    public function test(Request $request)
    {
        if (!app()->environment('local', 'testing')) {
            abort(403, 'Solo disponible en entorno local.');
        }

        $to = trim((string) $request->query('to', ''));
        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return response('Parámetro "to" inválido o faltante. Ejemplo: /test-mail?to=correo@dominio.com', 422);
        }

        try {
            $details = [
                'subject' => 'Prueba SMTP - ' . config('app.name'),
                'title'   => 'Prueba de correo',
                'intro'   => 'Este es un correo de prueba enviado por ' . config('app.name') . '.',
                'items'   => [
                    'Aplicación' => config('app.name'),
                    'Entorno'    => config('app.env'),
                    'Fecha'      => now()->format('Y-m-d H:i:s'),
                ],
            ];
            Mail::to($to)->send(new MyMailMailable($details));
            return response('OK: Correo enviado a ' . $to);
        } catch (\Throwable $e) {
            Log::error('Fallo prueba SMTP', ['to' => $to, 'error' => $e->getMessage()]);
            return response('ERROR: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Send notification to the organization with applicant details
     */
    public function notifyOrganization(AdoptionApplication $application): void
    {
        $org = $application->organization;
        $user = $application->user;
        if (!$org) return;

        // Gather recipients: org email + all users in the org with email
        $recipients = collect([$org->email])
            ->merge(User::where('organization_id', $org->id)->whereNotNull('email')->pluck('email'))
            ->map(fn($e) => trim((string)$e))
            ->filter(fn($e) => !empty($e) && filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();
        if ($recipients->isEmpty()) return;

        $details = [
            'subject' => 'Nueva solicitud de adopción #' . $application->id,
            'title'   => 'Nueva solicitud de adopción',
            'intro'   => 'Has recibido una nueva solicitud para la mascota: ' . ($application->pet->name ?? ('ID ' . $application->pet_id)),
            'items'   => [
                'Solicitante' => $user->name ?? ('Usuario #' . $user->id),
                'Email'       => $user->email ?? 'N/D',
                'Teléfono'    => optional($user->adopterProfile)->phone ?? 'N/D',
                'Mensaje'     => $application->message ?? '—',
                'Estado'      => $application->status,
                'Fecha'       => $application->created_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i'),
            ],
            'cta' => [
                'label' => 'Revisar solicitud',
                'href'  => route('submissions.show', $application->id),
            ],
        ];

        Mail::to($recipients->all())->send(new MyMailMailable($details));
    }

    /**
     * Send notification to the adopter with his application summary
     */
    public function notifyAdopter(AdoptionApplication $application): void
    {
        $user = $application->user;
        if (!$user || empty($user->email)) return;

        $org = $application->organization;
        $details = [
            'subject' => 'Tu solicitud de adopción #' . $application->id,
            'title'   => 'Gracias por tu solicitud',
            'intro'   => 'Hemos recibido tu solicitud de adopción. Te compartimos un resumen:',
            'items'   => [
                'Mascota'      => $application->pet->name ?? ('ID ' . $application->pet_id),
                'Organización' => $org->name ?? ('ID ' . $application->organization_id),
                'Estado'       => $application->status,
                'Fecha'        => $application->created_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i'),
                'Mensaje'      => $application->message ?? '—',
            ],
            'cta' => [
                'label' => 'Ver solicitud',
                'href'  => route('submissions.show', $application->id),
            ],
            'footer' => 'Si no fuiste tú quien envió esta solicitud, por favor ignora este correo.',
        ];

        Mail::to($user->email)->send(new MyMailMailable($details));
    }
}
