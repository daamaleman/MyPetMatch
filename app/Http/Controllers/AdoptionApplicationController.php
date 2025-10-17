<?php

namespace App\Http\Controllers;

use App\Models\AdopterProfile;
use App\Models\AdoptionApplication;
use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionApplicationController extends Controller
{
    /**
     * Show the application form for a given pet
     */
    public function apply(Pet $pet)
    {
        $user = Auth::user();

        // Guard: user must be authenticated and not be an organization creating an application
        if (!$user) {
            return redirect()->route('login');
        }

        // Guard: ensure adopter profile completeness minimal (phone + address + city + state + country)
        $profile = AdopterProfile::firstOrCreate(['user_id' => $user->id]);
        $missing = [];
        $required = [
            'phone' => 'Teléfono',
            'address_line1' => 'Dirección',
            'city' => 'Municipio/ciudad',
            'state' => 'Departamento/estado',
            'country' => 'País',
        ];
        foreach ($required as $key => $label) {
            if (empty($profile->{$key})) {
                $missing[] = $label;
            }
        }

        // Guard: single active application at a time for this user
        $hasActive = AdoptionApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->exists();

        return view('adoptions.apply', [
            'pet' => $pet,
            'adopterIncomplete' => ($missing ? true : false) || $hasActive,
            'adopterMissingLabels' => $missing,
            'hasActiveApplication' => $hasActive,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ?? 'adoptante';

        $q = request('q');
        $status = request('status');

        $query = AdoptionApplication::query()->with(['pet', 'organization', 'user']);

        if (in_array($role, ['organizacion', 'admin'])) {
            if ($role === 'organizacion') {
                $query->where('organization_id', $user->organization_id);
            }
        } else {
            $query->where('user_id', $user->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('pet', fn($p) => $p->where('name', 'like', "%$q%"))
                    ->orWhereHas('organization', fn($o) => $o->where('name', 'like', "%$q%"))
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$q%"));
            });
        }

        // Traemos todo y agrupamos por estado en la vista
        $applications = $query->latest()->get();

        return view('submissions.index', compact('applications', 'role', 'q', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            // Answers is a flexible JSON structure for extra form fields
            'answers' => ['nullable', 'array'],
        ]);

        $pet = Pet::with('organization')->findOrFail($validated['pet_id']);
        $org = $pet->organization;
        if (!$org) {
            return back()->withErrors(['pet_id' => 'La mascota no tiene organización válida asociada.']);
        }

        // Guard: single active application
        $hasActive = AdoptionApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->exists();
        if ($hasActive) {
            return back()->with('status', 'Ya tienes una solicitud en curso. Debes finalizarla antes de crear otra.');
        }

        // Guard: basic adopter profile completeness
        $profile = AdopterProfile::firstOrCreate(['user_id' => $user->id]);
        $requiredKeys = ['phone', 'address_line1', 'city', 'state', 'country'];
        $missing = array_filter($requiredKeys, fn($k) => empty($profile->{$k}));
        if (!empty($missing)) {
            return back()->with('status', 'Tu perfil de adoptante está incompleto. Complétalo antes de enviar la solicitud.');
        }

        $application = AdoptionApplication::create([
            'user_id' => $user->id,
            'organization_id' => $org->id,
            'pet_id' => $pet->id,
            'status' => 'pending',
            'message' => $validated['message'] ?? null,
            'answers' => $validated['answers'] ?? null,
        ]);

        return redirect()->route('pets.details', $pet->id)
            ->with('status', 'Tu solicitud de adopción fue enviada. La organización revisará tu caso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdoptionApplication $application)
    {
        $user = Auth::user();
        $role = $user->role ?? 'adoptante';
        if ($role === 'organizacion') {
            abort_unless($user->organization_id === $application->organization_id, 403);
        } elseif ($role === 'adoptante') {
            abort_unless($user->id === $application->user_id, 403);
        }
        $application->load(['pet', 'organization', 'user.adopterProfile']);
        return view('submissions.details', compact('application', 'role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdoptionApplication $application)
    {
        $user = Auth::user();
        $role = $user->role ?? 'adoptante';
        abort_unless($role === 'adoptante' && $user->id === $application->user_id, 403);
        abort_unless(in_array($application->status, ['pending', 'under_review']), 403);
        // Only allow editing once: if the application was already updated after creation, block further edits
        if ($application->updated_at && $application->created_at && $application->updated_at->ne($application->created_at)) {
            return redirect()->route('submissions.show', $application->id)
                ->with('status', 'Esta solicitud ya fue editada y no puede modificarse nuevamente.');
        }
        $application->load(['pet', 'organization']);
        return view('submissions.edit', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdoptionApplication $application)
    {
        $user = Auth::user();
        $role = $user->role ?? 'adoptante';

        if ($role === 'organizacion') {
            abort_unless($user->organization_id === $application->organization_id, 403);
            $data = $request->validate([
                'status' => ['required', 'in:pending,under_review,approved,rejected']
            ]);
            $application->update(['status' => $data['status']]);
            return back()->with('status', 'Estado de la solicitud actualizado.');
        }

        abort_unless($user->id === $application->user_id, 403);
        abort_unless(in_array($application->status, ['pending', 'under_review']), 403);
        // Prevent multiple edits: only allow update if not previously modified
        if ($application->updated_at && $application->created_at && $application->updated_at->ne($application->created_at)) {
            return redirect()->route('submissions.show', $application->id)
                ->with('status', 'No puedes editar esta solicitud nuevamente.');
        }
        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'answers' => ['nullable', 'array'],
        ]);
        $application->update([
            'message' => $data['message'] ?? $application->message,
            'answers' => $data['answers'] ?? $application->answers,
        ]);
        return redirect()->route('submissions.show', $application->id)
            ->with('status', 'Solicitud actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
