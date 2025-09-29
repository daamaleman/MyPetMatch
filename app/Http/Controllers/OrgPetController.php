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
    public function index(Request $request)
    {
    $user = Auth::user();
    $orgId = optional($user->organization)->id;

        // Filtros entrantes (replicando los de adoptions/index)
        $q = $request->string('q')->toString();
        $species = $request->string('species')->toString();
        $size = $request->string('size')->toString();
        $sex = $request->string('sex')->toString();

        $query = Pet::query();
        if ($orgId) {
            $query->where('organization_id', $orgId);
        } else {
            // Sin organización asignada: no mostrar mascotas
            $query->whereRaw('1=0');
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('breed', 'like', "%{$q}%")
                    ->orWhere('species', 'like', "%{$q}%")
                    ->orWhere('story', 'like', "%{$q}%");
            });
        }
        if ($species !== '') {
            $query->where('species', $species);
        }
        if ($size !== '') {
            $query->where('size', $size);
        }
        if ($sex !== '') {
            $query->where('sex', $sex);
        }

        // Opciones dinámicas limitadas a la organización del usuario
        $speciesOptions = collect();
        $sizeOptions = collect();
        if ($orgId) {
            $baseOptions = Pet::query()->where('organization_id', $orgId);
            $speciesOptions = (clone $baseOptions)
                ->whereNotNull('species')
                ->where('species', '!=', '')
                ->select('species')->distinct()->orderBy('species')->pluck('species');
            $sizeOptions = (clone $baseOptions)
                ->whereNotNull('size')
                ->where('size', '!=', '')
                ->select('size')->distinct()->orderBy('size')->pluck('size');
        }

        $pets = $query->latest()->paginate(12);
        $pets->appends($request->query());

        return view('pets.index', compact('pets', 'q', 'species', 'size', 'sex', 'speciesOptions', 'sizeOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $org = optional($user->organization);
        $required = ['email','phone','city','state','country'];
        $labels = [
            'email' => 'Email',
            'phone' => 'Teléfono',
            'city' => 'Municipio',
            'state' => 'Departamento',
            'country' => 'País',
        ];
        $missing = [];
        if (!$org || !$org->id) {
            $missing = $required; // sin organización también cuenta como incompleto
        } else {
            foreach ($required as $f) {
                $v = $org->{$f} ?? null;
                if (blank($v)) { $missing[] = $f; }
            }
        }
        $orgProfileIncomplete = (!$org || !$org->id) || count($missing) > 0;
        $orgMissingLabels = array_map(fn($f) => $labels[$f] ?? ucfirst($f), $missing);

        return view('pets.create', compact('orgProfileIncomplete','orgMissingLabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guardar solo si el perfil de la organización está completo
        $user = Auth::user();
        $org = optional($user->organization);
        $required = ['email','phone','city','state','country'];
        $labels = [
            'email' => 'Email',
            'phone' => 'Teléfono',
            'city' => 'Municipio',
            'state' => 'Departamento',
            'country' => 'País',
        ];
        $missing = [];
        if (!$org || !$org->id) {
            $missing = $required;
        } else {
            foreach ($required as $f) {
                $v = $org->{$f} ?? null;
                if (blank($v)) { $missing[] = $f; }
            }
        }
        if ((!$org || !$org->id) || count($missing) > 0) {
            $orgMissingLabels = array_map(fn($f) => $labels[$f] ?? ucfirst($f), $missing);
            return redirect()
                ->route('orgs.pets.create')
                ->with('org_incomplete', true)
                ->with('org_missing_labels', $orgMissingLabels);
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

        // Asignar a la organización del usuario (ya validada como existente y completa)
        $data['organization_id'] = $org->id;

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
