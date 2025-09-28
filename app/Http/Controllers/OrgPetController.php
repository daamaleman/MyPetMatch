<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrgPetController extends Controller
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
        $pets = Pet::where('organization_id', $orgId)->latest()->paginate(15);
        return response()->json($pets);
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
        $orgId = $this->resolveUserOrgId();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:255'],
            'sex' => ['required', Rule::in(['male','female','unknown'])],
            'age_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:12'],
            'size' => ['nullable', Rule::in(['xs','s','m','l','xl'])],
            'color' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'story' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'image_gallery' => ['nullable', 'array'],
            'video_gallery' => ['nullable', 'array'],
            'status' => ['nullable', Rule::in(['available','pending','adopted','inactive'])],
        ]);

        $pet = Pet::create(array_merge($data, ['organization_id' => $orgId]));
        return response()->json($pet, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orgId = $this->resolveUserOrgId();
        $pet = Pet::where('organization_id', $orgId)->findOrFail($id);
        return response()->json($pet);
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
        $pet = Pet::where('organization_id', $orgId)->findOrFail($id);
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'species' => ['sometimes', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:255'],
            'sex' => ['sometimes', Rule::in(['male','female','unknown'])],
            'age_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:12'],
            'size' => ['nullable', Rule::in(['xs','s','m','l','xl'])],
            'color' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'story' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:2048'],
            'image_gallery' => ['nullable', 'array'],
            'video_gallery' => ['nullable', 'array'],
            'status' => ['sometimes', Rule::in(['available','pending','adopted','inactive'])],
        ]);
        $pet->update($data);
        return response()->json($pet->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orgId = $this->resolveUserOrgId();
        $pet = Pet::where('organization_id', $orgId)->findOrFail($id);
        $pet->delete();
        return response()->json(['message' => 'Mascota eliminada']);
    }

    private function resolveUserOrgId(): int
    {
        $user = $this->currentUser();
        if (!$user->isOrganizationUser()) {
            abort(403, 'No autorizado');
        }
        // Por simplicidad, tomamos la primera organización del usuario
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
