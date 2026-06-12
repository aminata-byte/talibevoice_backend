<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Don;
use Illuminate\Http\Request;

class DonController extends Controller
{
    public function index(Request $request)
    {
        $query = Don::with('donateur');

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $don = Don::with(['donateur', 'redistributions'])->findOrFail($id);
        return response()->json($don);
    }

    public function valider($id)
    {
        $don = Don::findOrFail($id);
        $don->update(['statut' => 'valide']);
        return response()->json(['message' => 'Don validé avec succès.']);
    }

    public function rejeter($id)
    {
        $don = Don::findOrFail($id);
        $don->update(['statut' => 'rejete']);
        return response()->json(['message' => 'Don rejeté.']);
    }

    public function stats()
    {
        return response()->json([
            'total_recu' => Don::where('statut', 'valide')->sum('montant'),
            'en_attente' => Don::where('statut', 'en_attente')->count(),
            'valides' => Don::where('statut', 'valide')->count(),
            'redistribues' => Don::where('statut', 'valide')->whereHas('redistributions')->count(),
        ]);
    }
}
