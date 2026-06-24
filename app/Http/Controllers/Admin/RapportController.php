<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $query = Rapport::with(['agent', 'daara']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $rapport = Rapport::with(['agent', 'daara'])->findOrFail($id);
        return response()->json($rapport);
    }

    public function valider($id)
    {
        $rapport = Rapport::findOrFail($id);
        $rapport->update(['statut' => 'valide']);
        return response()->json(['message' => 'Rapport validé avec succès.']);
    }
}
