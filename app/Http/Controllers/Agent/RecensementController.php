<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Talibe;
use App\Models\Daara;
use App\Models\Besoin;
use Illuminate\Http\Request;

class RecensementController extends Controller
{
    public function storeTalibe(Request $request)
    {
        $request->validate([
            'daara_id' => 'required|exists:daaras,id',
            'nom' => 'required|string',
            'prenom' => 'required|string',
        ]);

        $talibe = Talibe::create([
            'daara_id' => $request->daara_id,
            'agent_id' => $request->user()->id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'a_etat_civil' => $request->a_etat_civil ?? false,
            'niveau_etude' => $request->niveau_etude,
            'est_majeur' => $request->est_majeur ?? false,
            'statut' => 'actif',
            'statut_insertion' => 'non_concerne',
        ]);

        return response()->json($talibe, 201);
    }

    public function storeDaara(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'adresse' => 'required|string',
            'nom_responsable' => 'required|string',
        ]);

        $daara = Daara::create([
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'capacite_accueil' => $request->capacite_accueil ?? 0,
            'nombre_talibes' => $request->nombre_talibes ?? 0,
            'nom_responsable' => $request->nom_responsable,
            'telephone_responsable' => $request->telephone_responsable,
            'region' => $request->region,
            'commune' => $request->commune,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'statut' => 'en_attente',
        ]);

        return response()->json($daara, 201);
    }

    public function storeBesoin(Request $request)
    {
        $request->validate([
            'daara_id' => 'required|exists:daaras,id',
            'type' => 'required|string',
            'description' => 'required|string',
            'priorite' => 'required|in:urgent,normal,faible',
        ]);

        $besoin = Besoin::create([
            'daara_id' => $request->daara_id,
            'agent_id' => $request->user()->id,
            'type' => $request->type,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'statut' => 'en_attente',
            'date_signalement' => now(),
        ]);

        return response()->json($besoin, 201);
    }

    public function getTalibes(Request $request)
    {
        $talibes = Talibe::where('agent_id', $request->user()->id)
            ->with('daara')
            ->get();
        return response()->json($talibes);
    }

    public function getDaaras(Request $request)
    {
        $daaras = Daara::all();
        return response()->json($daaras);
    }

    public function updateTalibe(Request $request, $id)
    {
        $talibe = Talibe::where('agent_id', $request->user()->id)
            ->orWhereNull('agent_id')
            ->findOrFail($id);

        $talibe->update($request->only([
            'nom',
            'prenom',
            'date_naissance',
            'lieu_naissance',
            'niveau_etude',
            'est_majeur',
            'a_etat_civil',
        ]));

        return response()->json($talibe);
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $talibe = Talibe::where('agent_id', $request->user()->id)
            ->orWhereNull('agent_id')
            ->findOrFail($id);

        if ($talibe->document_path) {
            \Storage::disk('public')->delete($talibe->document_path);
        }

        $path = $request->file('document')->store('talibes_documents', 'public');

        $talibe->update(['document_path' => $path]);

        return response()->json([
            'message' => 'Document ajouté avec succès.',
            'document_url' => \Storage::url($path),
            'talibe' => $talibe,
        ]);
    }
}
