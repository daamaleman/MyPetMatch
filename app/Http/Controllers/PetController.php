<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Pet::query()->with('organization')
            ->where('status', 'available');

        // Basic filters
        $filters = request()->only(['species', 'city', 'size']);
        if (!empty($filters['species'])) $query->where('species', $filters['species']);
        if (!empty($filters['city'])) $query->whereHas('organization', fn($q) => $q->where('city', $filters['city']));
        if (!empty($filters['size'])) $query->where('size', $filters['size']);

        $pets = $query->latest()->paginate(12);

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
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pet = Pet::with('organization')->findOrFail($id);
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
        return response()->json(['message' => 'Not implemented'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Not implemented'], 405);
    }
}
