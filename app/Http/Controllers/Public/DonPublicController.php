<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Don;
use App\Models\Donateur;
use Illuminate\Http\Request;

class DonPublicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:financier,materiel',
            'anonyme' => 'boolean',
        ]);

        // Créer ou trouver le donateur
        $donateur = Donateur::create([
            'nom' => $request->anonyme ? null : $request->nom,
            'prenom' => $request->anonyme ? null : $request->prenom,
            'email' => $request->anonyme ? null : $request->email,
            'telephone' => $request->anonyme ? null : $request->telephone,
            'est_anonyme' => $request->anonyme ?? false,
        ]);

        // Créer le don
        $don = Don::create([
            'donateur_id' => $donateur->id,
            'type' => $request->type,
            'montant' => $request->montant ?? null,
            'mode_paiement' => $request->mode_paiement ?? null,
            'items_materiel' => $request->items ?? null,
            'statut' => 'en_attente',
            'date_don' => now(),
        ]);

        return response()->json([
            'message' => 'Don soumis avec succès. Merci pour votre générosité !',
            'don' => $don,
        ], 201);
    }

    public function stats()
    {
        return response()->json([
            'total_dons' => Don::where('statut', 'valide')->sum('montant'),
            'nombre_dons' => Don::count(),
        ]);
    }
}
