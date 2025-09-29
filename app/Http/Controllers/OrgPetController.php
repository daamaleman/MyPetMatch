<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrgPetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orgId = optional($user->organization)->id;

        $query = Pet::query();
        if ($orgId) {
            $query->where('organization_id', $orgId);
        }

        $pets = $query->latest()->paginate(12);

    return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'species' => ['nullable','string','max:120'],
            'breed' => ['nullable','string','max:120'],
            'age' => ['nullable','string','max:60'],
            'size' => ['nullable','string','max:60'],
            'sex' => ['nullable','in:male,female,unknown'],
            'story' => ['nullable','string'],
            'status' => ['nullable','in:draft,published,archived'],
            'cover_image' => ['nullable','image','max:4096'],
        ]);

        // Asignar a la organización del usuario; crear placeholder si no existe
        $orgId = optional(Auth::user()->organization)->id;
        if (!$orgId) {
            $org = Organization::firstOrCreate(['name' => 'Mi Organización']);
            $orgId = $org->id;
            // Opcional: vincular al usuario si está desasociado
            $user = Auth::user();
            if ($user && !$user->organization_id) {
                \App\Models\User::whereKey($user->id)->update(['organization_id' => $orgId]);
            }
        }
        $data['organization_id'] = $orgId;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('pets', 'public');
        }

        $pet = Pet::create($data);

        return redirect()->route('orgs.pets.edit', $pet->id)->with('status', 'Mascota creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pet = Pet::findOrFail($id);
        // Seguridad: asegurar que pertenece a la org del usuario
        $orgId = optional(Auth::user()->organization)->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
    return view('pets.details', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pet = Pet::findOrFail($id);
        $orgId = optional(Auth::user()->organization)->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
    return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pet = Pet::findOrFail($id);
        $orgId = optional(Auth::user()->organization)->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'species' => ['nullable','string','max:120'],
            'breed' => ['nullable','string','max:120'],
            'age' => ['nullable','string','max:60'],
            'size' => ['nullable','string','max:60'],
            'sex' => ['nullable','in:male,female,unknown'],
            'story' => ['nullable','string'],
            'status' => ['nullable','in:draft,published,archived'],
            'cover_image' => ['nullable','image','max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($pet->cover_image) {
                Storage::disk('public')->delete($pet->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('pets', 'public');
        }

        $pet->update($data);

        return back()->with('status', 'Mascota actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pet = Pet::findOrFail($id);
        $orgId = optional(Auth::user()->organization)->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        if ($pet->cover_image) {
            Storage::disk('public')->delete($pet->cover_image);
        }
        $pet->delete();
        return redirect()->route('orgs.pets.index')->with('status', 'Mascota eliminada');
    }
}
