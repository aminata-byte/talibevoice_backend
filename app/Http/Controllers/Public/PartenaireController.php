<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use App\Models\Formation;
use App\Models\Insertion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartenaireController extends Controller
{
    public function candidature(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'domaine' => 'required|string',
            'nom_contact' => 'required|string',
            'email' => 'required|email|unique:partenaires,email',
            'telephone' => 'nullable|string',
            'message_motivation' => 'nullable|string',
        ]);

        $partenaire = Partenaire::create([
            'nom' => $request->nom,
            'domaine' => $request->domaine,
            'nom_contact' => $request->nom_contact,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'message_motivation' => $request->message_motivation,
            'code_partenaire' => strtoupper(Str::random(8)),
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'message' => 'Candidature soumise avec succès. Vous recevrez votre code partenaire sous 48h.',
            'partenaire' => $partenaire,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'code_partenaire' => 'required|string',
        ]);

        $partenaire = Partenaire::where('code_partenaire', $request->code_partenaire)
            ->where('statut', 'valide')
            ->first();

        if (!$partenaire) {
            return response()->json(['message' => 'Code partenaire invalide ou compte non validé.'], 401);
        }

        return response()->json([
            'token' => 'partenaire_' . $partenaire->code_partenaire,
            'partenaire' => $partenaire,
        ]);
    }

    public function profil(Request $request)
    {
        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();
        return response()->json($partenaire);
    }

    public function updateProfil(Request $request)
    {
        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();

        $partenaire->update($request->only([
            'nom',
            'domaine',
            'nom_contact',
            'telephone',
            'site_web'
        ]));

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'partenaire' => $partenaire,
        ]);
    }

    public function offres(Request $request)
    {
        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();

        $offres = Formation::where('partenaire_id', $partenaire->id)->get();
        return response()->json($offres);
    }

    public function submitOffre(Request $request)
    {
        $request->validate([
            'type' => 'required|in:formation,stage,emploi',
            'titre' => 'required|string',
        ]);

        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();

        $formation = Formation::create([
            'partenaire_id' => $partenaire->id,
            'titre' => $request->titre,
            'domaine' => $request->domaine ?? '',
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'capacite' => $request->places ?? 0,
            'lieu' => $request->lieu,
            'prerequis' => $request->prerequis,
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'message' => 'Offre soumise avec succès. Elle sera validée par l\'administrateur.',
            'offre' => $formation,
        ], 201);
    }

    public function talibesInscrits(Request $request)
    {
        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();

        $insertions = Insertion::where('partenaire_id', $partenaire->id)
            ->with('talibe')
            ->get();

        return response()->json($insertions);
    }

    public function impact(Request $request)
    {
        $code = str_replace('partenaire_', '', $request->bearerToken());
        $partenaire = Partenaire::where('code_partenaire', $code)->firstOrFail();

        return response()->json([
            'offres_soumises' => Formation::where('partenaire_id', $partenaire->id)->count(),
            'talibes_inscrits' => Insertion::where('partenaire_id', $partenaire->id)->count(),
            'offres_validees' => Formation::where('partenaire_id', $partenaire->id)->where('statut', 'valide')->count(),
        ]);
    }
}
