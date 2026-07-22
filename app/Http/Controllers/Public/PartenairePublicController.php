<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PartenairePublicController extends Controller
{
    public function candidature(Request $request)
    {
        $request->validate([
            'nom'                => 'required|string',
            'domaine'            => 'required|string',
            'nom_contact'        => 'required|string',
            'email'              => 'required|email',
            'telephone'          => 'nullable|string',
            'message_motivation' => 'nullable|string',
        ]);

        $partenaire = Partenaire::create([
            'nom'                => $request->nom,
            'domaine'            => $request->domaine,
            'nom_contact'        => $request->nom_contact,
            'email'              => $request->email,
            'telephone'          => $request->telephone,
            'message_motivation' => $request->message_motivation,
            'statut'             => 'en_attente',
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'admin_id'          => $admin->id,
                'message'           => "Nouvelle candidature partenaire : {$partenaire->nom} ({$partenaire->domaine})",
                'type'              => 'offre_validee',
                'destinataire_type' => 'agent',
                'destinataire_id'   => $admin->id,
                'est_lue'           => false,
                'date_envoi'        => now(),
            ]);
        }

        return response()->json([
            'message' => 'Candidature soumise avec succès. Nous vous contacterons sous 48h.',
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
            return response()->json([
                'message' => 'Code partenaire invalide ou compte non validé.',
            ], 401);
        }

        $token = Str::random(60);
        $partenaire->update(['token' => Hash::make($token)]);

        return response()->json([
            'token'      => $token,
            'partenaire' => $partenaire,
        ]);
    }

    public function profil(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        return response()->json($partenaire);
    }

    public function updateProfil(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        $request->validate([
            'nom'       => 'sometimes|required|string',
            'telephone' => 'sometimes|nullable|string',
            'site_web'  => 'sometimes|nullable|string',
        ]);

        $partenaire->update($request->only(['nom', 'telephone', 'site_web']));

        return response()->json([
            'message'    => 'Profil mis à jour.',
            'partenaire' => $partenaire,
        ]);
    }

    public function offres(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        $offres = $partenaire->formations()->orderBy('created_at', 'desc')->get();
        return response()->json($offres);
    }

    public function submitOffre(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        $request->validate([
            'titre'       => 'required|string',
            'domaine'     => 'required|string',
            'capacite'    => 'required|integer|min:1',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'lieu'        => 'nullable|string',
            'description' => 'nullable|string',
            'prerequis'   => 'nullable|string',
        ]);

        $formation = $partenaire->formations()->create([
            'titre'       => $request->titre,
            'domaine'     => $request->domaine,
            'capacite'    => $request->capacite,
            'date_debut'  => $request->date_debut,
            'date_fin'    => $request->date_fin,
            'lieu'        => $request->lieu,
            'description' => $request->description,
            'prerequis'   => $request->prerequis,
            'statut'      => 'en_attente',
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'admin_id'          => $admin->id,
                'message'           => "Nouvelle offre de formation soumise par {$partenaire->nom} : {$formation->titre}",
                'type'              => 'offre_validee',
                'destinataire_type' => 'agent',
                'destinataire_id'   => $admin->id,
                'est_lue'           => false,
                'date_envoi'        => now(),
            ]);
        }

        return response()->json([
            'message'   => 'Offre soumise avec succès. En attente de validation.',
            'formation' => $formation,
        ], 201);
    }

    public function talibesInscrits(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        $insertions = $partenaire->insertions()
            ->with(['talibe', 'formation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($insertions);
    }

    public function impact(Request $request)
    {
        $token = $request->bearerToken();
        $partenaire = $this->getPartenaireFromToken($token);

        if (!$partenaire) {
            return response()->json(['message' => 'Non autorisé.'], 401);
        }

        $insertions = $partenaire->insertions;
        $formations = $partenaire->formations;

        return response()->json([
            'total_formes'     => $insertions->count(),
            'total_inseres'    => $insertions->where('statut', 'valide')->count(),
            'total_emplois'    => $insertions->where('type', 'emploi')->count(),
            'total_stages'     => $insertions->where('type', 'stage')->count(),
            'total_formations' => $formations->count(),
            'formations'       => $formations,
        ]);
    }

    public function recupererCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $partenaire = Partenaire::where('email', $request->email)
            ->where('statut', 'valide')
            ->first();

        if (!$partenaire) {
            return response()->json([
                'message' => 'Aucun partenaire validé trouvé avec cet email.',
            ], 404);
        }

        return response()->json([
            'message'         => 'Code récupéré avec succès.',
            'code_partenaire' => $partenaire->code_partenaire,
            'nom'             => $partenaire->nom,
        ]);
    }

    private function getPartenaireFromToken($token)
    {
        if (!$token) return null;

        $partenaires = Partenaire::whereNotNull('token')->get();
        foreach ($partenaires as $p) {
            if (Hash::check($token, $p->token)) {
                return $p;
            }
        }
        return null;
    }
}
