<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Talibe;
use Illuminate\Http\Request;

class TalibeController extends Controller
{
    public function index(Request $request)
    {
        $query = Talibe::with(['daara', 'agent']);

        // Fix : grouper le search pour ne pas casser les autres filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        if ($request->filled('daara_id')) {
            $query->where('daara_id', $request->daara_id);
        }

        // Filtre par région via la relation daara
        if ($request->filled('region')) {
            $query->whereHas('daara', function ($q) use ($request) {
                $q->where('region', $request->region);
            });
        }

        if ($request->filled('est_majeur')) {
            $query->where('est_majeur', filter_var($request->est_majeur, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('statut_insertion')) {
            $query->where('statut_insertion', $request->statut_insertion);
        }

        return response()->json($query->orderBy('nom')->get());
    }

    public function show($id)
    {
        $talibe = Talibe::with(['daara', 'agent', 'insertions'])->findOrFail($id);
        return response()->json($talibe);
    }

    public function destroy($id)
    {
        $talibe = Talibe::findOrFail($id);
        $talibe->delete();
        return response()->json(['message' => 'Talibé supprimé avec succès.']);
    }
}
