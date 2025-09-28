<?php

namespace App\Http\Controllers;

use App\Models\AdoptionApplication;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdoptionApplicationController extends Controller
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
        $apps = AdoptionApplication::with(['pet', 'organization'])
            ->forUser(Auth::id())
            ->latest()
            ->paginate(12);
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
        $data = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'answers' => ['nullable', 'array'],
            'adults_count' => ['nullable', 'integer', 'min:0', 'max:20'],
            'children_count' => ['nullable', 'integer', 'min:0', 'max:20'],
            'has_other_pets' => ['nullable', 'boolean'],
            'other_pets_details' => ['nullable', 'string', 'max:2000'],
            'housing_type' => ['nullable', Rule::in(['apartment', 'house', 'other'])],
            'has_fenced_yard' => ['nullable', 'boolean'],
            'has_landlord_permission' => ['nullable', 'boolean'],
            'terms_accepted' => ['required', 'boolean', 'accepted'],
            'preferred_contact' => ['nullable', Rule::in(['email', 'phone'])],
        ]);

        $pet = Pet::with('organization')->findOrFail($data['pet_id']);

        // Avoid duplicate active applications for same pet and user
        $exists = AdoptionApplication::where('user_id', Auth::id())
            ->where('pet_id', $pet->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->exists();
        if ($exists) {
            return response()->json(['message' => 'Ya tienes una solicitud activa para esta mascota.'], 422);
        }

        $app = AdoptionApplication::create([
            'user_id' => Auth::id(),
            'organization_id' => $pet->organization_id,
            'pet_id' => $pet->id,
            'status' => 'submitted',
            'message' => $data['message'] ?? null,
            'answers' => $data['answers'] ?? null,
            'adults_count' => $data['adults_count'] ?? null,
            'children_count' => $data['children_count'] ?? null,
            'has_other_pets' => $data['has_other_pets'] ?? false,
            'other_pets_details' => $data['other_pets_details'] ?? null,
            'housing_type' => $data['housing_type'] ?? null,
            'has_fenced_yard' => $data['has_fenced_yard'] ?? null,
            'has_landlord_permission' => $data['has_landlord_permission'] ?? null,
            'terms_accepted' => $data['terms_accepted'] ?? false,
            'preferred_contact' => $data['preferred_contact'] ?? null,
        ]);

        return response()->json($app->load(['pet', 'organization']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $app = AdoptionApplication::with(['pet', 'organization'])
            ->forUser(Auth::id())
            ->findOrFail($id);
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
        $app = AdoptionApplication::forUser(Auth::id())->findOrFail($id);

        // Allow updating message/answers while under review or submitted
        if (!in_array($app->status, ['submitted', 'under_review'])) {
            return response()->json(['message' => 'No se puede editar en este estado.'], 422);
        }

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'answers' => ['nullable', 'array'],
            'preferred_contact' => ['nullable', Rule::in(['email', 'phone'])],
        ]);

        $app->update($data);
        return response()->json($app->fresh()->load(['pet', 'organization']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $app = AdoptionApplication::forUser(Auth::id())->findOrFail($id);
        if (!in_array($app->status, ['submitted', 'under_review'])) {
            return response()->json(['message' => 'No se puede cancelar en este estado.'], 422);
        }
        $app->update(['status' => 'withdrawn']);
        return response()->json(['message' => 'Solicitud retirada']);
    }
}
