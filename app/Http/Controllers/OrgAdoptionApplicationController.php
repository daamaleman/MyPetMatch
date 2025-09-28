<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrgAdoptionApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orgId = $this->resolveUserOrgId();
        $apps = AdoptionApplication::with(['pet', 'user'])
            ->forOrg($orgId)
            ->latest()
            ->paginate(20);
        return response()->json($apps);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orgId = $this->resolveUserOrgId();
        $app = AdoptionApplication::with(['pet', 'user'])->forOrg($orgId)->findOrFail($id);
        return response()->json($app);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orgId = $this->resolveUserOrgId();
        $app = AdoptionApplication::forOrg($orgId)->findOrFail($id);
        $data = $request->validate([
            'status' => ['sometimes', Rule::in(['under_review','approved','rejected','cancelled'])],
            'scheduled_interview_at' => ['nullable', 'date'],
            'internal_notes' => ['nullable', 'string'],
        ]);

        if (isset($data['status'])) {
            // Simple transition rules example
            $allowed = [
                'submitted' => ['under_review','cancelled'],
                'under_review' => ['approved','rejected','cancelled'],
            ];
            $current = $app->status;
            if (!isset($allowed[$current]) || !in_array($data['status'], $allowed[$current])) {
                return response()->json(['message' => 'Transición de estado no permitida'], 422);
            }
            if (in_array($data['status'], ['approved','rejected'])) {
                $data['reviewed_at'] = now();
            }
        }

        $app->update($data);
        return response()->json($app->fresh()->load(['pet','user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orgId = $this->resolveUserOrgId();
        $app = AdoptionApplication::forOrg($orgId)->findOrFail($id);
        $app->delete();
        return response()->json(['message' => 'Solicitud eliminada']);
    }

    private function resolveUserOrgId(): int
    {
        $user = $this->currentUser();
        if (!$user->isOrganizationUser()) {
            abort(403, 'No autorizado');
        }
        $org = $user->organizations()->first();
        if (!$org) abort(403, 'No estás asociado a una organización');
        return (int) $org->id;
    }

    private function currentUser(): User
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(401, 'No autenticado');
        }
        return $user;
    }
}
