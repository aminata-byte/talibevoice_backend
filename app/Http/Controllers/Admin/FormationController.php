<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Insertion;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index(Request $request)
    {
        $query = Formation::with('partenaire');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('domaine')) {
            $query->where('domaine', $request->domaine);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $formation = Formation::with(['partenaire', 'insertions.talibe'])->findOrFail($id);
        return response()->json($formation);
    }

    public function valider($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update(['statut' => 'valide']);
        return response()->json(['message' => 'Formation validée avec succès.']);
    }

    public function activer($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update(['statut' => 'actif']);
        return response()->json(['message' => 'Formation activée avec succès.']);
    }

    public function desactiver($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update(['statut' => 'inactif']);
        return response()->json(['message' => 'Formation désactivée avec succès.']);
    }

    public function inscrireTalibe(Request $request, $id)
    {
        $request->validate([
            'talibe_id' => 'required|exists:talibes,id',
        ]);

        $formation = Formation::findOrFail($id);

        // Vérifier si le talibé est déjà inscrit dans cette formation
        $dejaInscrit = Insertion::where('talibe_id', $request->talibe_id)
            ->where('formation_id', $formation->id)
            ->exists();

        if ($dejaInscrit) {
            return response()->json([
                'message' => 'Ce talibé est déjà inscrit dans cette formation.'
            ], 422);
        }

        Insertion::create([
            'talibe_id'      => $request->talibe_id,
            'partenaire_id'  => $formation->partenaire_id,
            'formation_id'   => $formation->id,
            'type'           => 'formation',
            'statut'         => 'en_attente',
            'date_insertion' => now(),
        ]);

        return response()->json(['message' => 'Talibé inscrit avec succès.']);
    }

    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();
        return response()->json(['message' => 'Formation supprimée avec succès.']);
    }
}
