<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Rapport;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        $missions = Mission::where('agent_id', $request->user()->id)
            ->with('daara')
            ->get();
        return response()->json($missions);
    }

    public function show($id)
    {
        $mission = Mission::with('daara')->findOrFail($id);
        return response()->json($mission);
    }

    public function accepter($id)
    {
        $mission = Mission::findOrFail($id);
        $mission->update(['statut' => 'en_cours']);
        return response()->json(['message' => 'Mission acceptée.']);
    }

    public function cloturer($id)
    {
        $mission = Mission::findOrFail($id);
        $mission->update(['statut' => 'cloturee']);
        return response()->json(['message' => 'Mission clôturée.']);
    }

    public function storeRapport(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'contenu' => 'required|string',
            'type' => 'required|string',
        ]);

        $rapport = Rapport::create([
            'agent_id' => $request->user()->id,
            'daara_id' => $request->daara_id,
            'titre' => $request->titre,
            'type' => $request->type,
            'contenu' => $request->contenu,
            'statut' => $request->statut ?? 'brouillon',
            'date_creation' => now(),
        ]);

        return response()->json($rapport, 201);
    }

    public function getRapports(Request $request)
    {
        $rapports = Rapport::where('agent_id', $request->user()->id)
            ->with('daara')
            ->get();
        return response()->json($rapports);
    }

    public function getNotifications(Request $request)
    {
        $user = $request->user();
        $notifications = \App\Models\Notification::where('destinataire_type', 'agent')
            ->where('destinataire_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications);
    }
}
