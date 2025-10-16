<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

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
        $required = ['email', 'phone', 'city', 'state', 'country'];
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
                if (blank($v)) {
                    $missing[] = $f;
                }
            }
        }
        $orgProfileIncomplete = (!$org || !$org->id) || count($missing) > 0;
        $orgMissingLabels = array_map(fn($f) => $labels[$f] ?? ucfirst($f), $missing);

        // Opciones normalizadas de especie (sugerencias) y tamaño
        $speciesOptions = [
            'Perro',
            'Gato',
            'Conejo',
            'Hámster',
            'Cobaya',
            'Chinchilla',
            'Pez',
            'Canario',
            'Periquito',
            'Ninfa',
            'Cacatúa',
            'Tortuga',
            'Iguana',
            'Erizo',
            'Hurón',
            'Gallina',
            'Pavo',
            'Caballo',
            'Otro'
        ];
        $sizeOptions = ['Pequeño', 'Mediano', 'Grande', 'Extra grande', 'Desconocido'];

        return view('pets.create', compact('orgProfileIncomplete', 'orgMissingLabels', 'speciesOptions', 'sizeOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guardar solo si el perfil de la organización está completo
        $user = Auth::user();
        $org = optional($user->organization);
        $required = ['email', 'phone', 'city', 'state', 'country'];
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
                if (blank($v)) {
                    $missing[] = $f;
                }
            }
        }
        if ((!$org || !$org->id) || count($missing) > 0) {
            $orgMissingLabels = array_map(fn($f) => $labels[$f] ?? ucfirst($f), $missing);
            return redirect()
                ->route('orgs.pets.create')
                ->with('org_incomplete', true)
                ->with('org_missing_labels', $orgMissingLabels);
        }

        $sizeAllowed = ['Pequeño', 'Mediano', 'Grande', 'Extra grande', 'Desconocido'];
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Permitir escribir especie libremente, con autocompletado en la UI
            'species' => ['nullable', 'string', 'max:50'],
            'breed' => ['nullable', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'size' => ['nullable', 'in:' . implode(',', $sizeAllowed)],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.9'],
            'height_cm' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'sex' => ['nullable', 'in:male,female,unknown'],
            'story' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            // Allow common web image types explicitly (png/jpg/jpeg)
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ]);

        // Asignar a la organización del usuario (ya validada como existente y completa)
        $data['organization_id'] = $org->id;

        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $data['cover_image'] = $request->file('cover_image')->store('pets', 'public');
            $this->syncPublicStorageCopy($data['cover_image']);
        } else {
            unset($data['cover_image']); // Si no hay imagen válida, no guardar el campo
        }

        // Normalizar edad a cadena (años)
        if (array_key_exists('age', $data) && $data['age'] !== null && $data['age'] !== '') {
            $data['age'] = (string) intval($data['age']);
        }
        // Fusionar metadata (peso/altura) en la historia como primeras líneas
        $story = $data['story'] ?? '';
        $weight = $data['weight_kg'] ?? null;
        $height = $data['height_cm'] ?? null;
        $data['story'] = $this->mergeMetaIntoStory($story, $weight, $height);

        // Si existen columnas reales, guardarlas; si no, limpiar del payload
        if (Schema::hasColumn('pets', 'weight_kg')) {
            // Mantener el valor tal cual para persistir en columna
        } else {
            unset($data['weight_kg']);
        }
        if (Schema::hasColumn('pets', 'height_cm')) {
            // Mantener
        } else {
            unset($data['height_cm']);
        }
        if (Schema::hasColumn('pets', 'age_years')) {
            $data['age_years'] = isset($request['age']) && $request['age'] !== null && $request['age'] !== '' ? intval($request['age']) : null;
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
        // Opciones normalizadas y extracción de peso/altura desde la historia
        $speciesOptions = [
            'Perro',
            'Gato',
            'Conejo',
            'Hámster',
            'Cobaya',
            'Chinchilla',
            'Pez',
            'Canario',
            'Periquito',
            'Ninfa',
            'Cacatúa',
            'Tortuga',
            'Iguana',
            'Erizo',
            'Hurón',
            'Gallina',
            'Pavo',
            'Caballo',
            'Otro'
        ];
        $sizeOptions = ['Pequeño', 'Mediano', 'Grande', 'Extra grande', 'Desconocido'];
        [$weightKg, $heightCm, $storyNoMeta] = $this->extractMetaAndCleanStory($pet->story);
        return view('pets.edit', compact('pet', 'speciesOptions', 'sizeOptions', 'weightKg', 'heightCm', 'storyNoMeta'));
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
        $sizeAllowed = ['Pequeño', 'Mediano', 'Grande', 'Extra grande', 'Desconocido'];
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species' => ['nullable', 'string', 'max:50'],
            'breed' => ['nullable', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'size' => ['nullable', 'in:' . implode(',', $sizeAllowed)],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.9'],
            'height_cm' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'sex' => ['nullable', 'in:male,female,unknown'],
            'story' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($pet->cover_image && is_string($pet->cover_image)) {
                $prevRel = trim($pet->cover_image, '/');
                if ($prevRel !== '') {
                    Storage::disk('public')->delete($prevRel);
                    $publicOld = public_path('storage/' . $prevRel);
                    if (File::isFile($publicOld)) {
                        @File::delete($publicOld);
                    }
                }
            }
            $data['cover_image'] = $request->file('cover_image')->store('pets', 'public');
            $this->syncPublicStorageCopy($data['cover_image']);
        } else {
            unset($data['cover_image']); // Si no hay imagen válida, no guardar el campo
        }

        // Normalizar edad (cadena) y fusionar metadata (peso/altura) en historia
        if (array_key_exists('age', $data) && $data['age'] !== null && $data['age'] !== '') {
            $data['age'] = (string) intval($data['age']);
        }
        $story = $data['story'] ?? '';
        $weight = $data['weight_kg'] ?? null;
        $height = $data['height_cm'] ?? null;
        $data['story'] = $this->mergeMetaIntoStory($story, $weight, $height);

        if (Schema::hasColumn('pets', 'weight_kg')) { /* keep */
        } else {
            unset($data['weight_kg']);
        }
        if (Schema::hasColumn('pets', 'height_cm')) { /* keep */
        } else {
            unset($data['height_cm']);
        }
        if (Schema::hasColumn('pets', 'age_years')) {
            $data['age_years'] = isset($request['age']) && $request['age'] !== null && $request['age'] !== '' ? intval($request['age']) : null;
        }

        $pet->update($data);

        return back()->with('status', 'Mascota actualizada');
    }

    private function mergeMetaIntoStory(?string $story, $weightKg, $heightCm): string
    {
        $story = (string)($story ?? '');
        // Remove existing Peso:/Altura: lines
        $clean = preg_replace('/^\s*(Peso|Altura):\s*[^\n]*\n?/mi', '', $story);
        $clean = ltrim($clean, "\n");
        $lines = [];
        if ($weightKg !== null && $weightKg !== '') {
            $w = number_format((float)$weightKg, (fmod((float)$weightKg, 1.0) === 0.0 ? 0 : 1), '.', '');
            $lines[] = "Peso: {$w} kg";
        }
        if ($heightCm !== null && $heightCm !== '') {
            $h = (int) round((float)$heightCm);
            $lines[] = "Altura: {$h} cm";
        }
        $prefix = $lines ? (implode("\n", $lines) . "\n") : '';
        return $prefix . $clean;
    }

    private function extractMetaAndCleanStory(?string $story): array
    {
        $story = (string)($story ?? '');
        $weight = null;
        $height = null;
        if (preg_match('/^\s*Peso:\s*([0-9]+(?:\.[0-9])?)\s*kg/im', $story, $m)) {
            $weight = $m[1];
        }
        if (preg_match('/^\s*Altura:\s*([0-9]+)\s*cm/im', $story, $m2)) {
            $height = $m2[1];
        }
        $clean = preg_replace('/^\s*(Peso|Altura):\s*[^\n]*\n?/mi', '', $story);
        $clean = ltrim($clean, "\n");
        return [$weight, $height, $clean];
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
        if ($pet->cover_image && is_string($pet->cover_image)) {
            $prevRel = trim($pet->cover_image, '/');
            if ($prevRel !== '') {
                Storage::disk('public')->delete($prevRel);
                $publicOld = public_path('storage/' . $prevRel);
                if (File::isFile($publicOld)) {
                    @File::delete($publicOld);
                }
            }
        }
        $pet->delete();
        return redirect()->route('orgs.pets.index')->with('status', 'Mascota eliminada');
    }

    /**
     * Si no existe el symlink public/storage, intenta copiar el archivo subido
     * desde storage/app/public hacia public/storage para servirlo.
     */
    private function syncPublicStorageCopy(string $relativePath): void
    {
        $relativePath = ltrim($relativePath ?? '', '/');
        if ($relativePath === '') {
            return;
        }
        $symlinkPath = public_path('storage');
        // Si existe directorio/symlink, no hacemos nada (Laravel servirá el archivo correctamente)
        if (is_link($symlinkPath)) {
            return;
        }
        $source = storage_path('app/public/' . $relativePath);
        $destDir = public_path('storage/' . dirname($relativePath));
        $dest = public_path('storage/' . $relativePath);
        try {
            File::ensureDirectoryExists($destDir);
            if ($relativePath !== '' && File::exists($source)) {
                // Copiar si no existe o si el origen es más reciente
                if (!File::exists($dest) || filemtime($source) > @filemtime($dest)) {
                    @File::copy($source, $dest);
                }
            }
        } catch (\Throwable $e) {
            // Silenciar fallos de copia en entornos restringidos; la app seguirá sirviendo desde disk('public') si hay symlink
        }
    }
}
