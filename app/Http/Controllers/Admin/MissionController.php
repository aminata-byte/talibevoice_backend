<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Notification;
use Illuminate\Http\Request;

class MissionController extends Controller
{
  public function index()
  {
    $missions = Mission::with(['daara', 'agent'])
      ->orderBy('created_at', 'desc')
      ->get();
    return response()->json($missions);
  }

  public function show($id)
  {
    $mission = Mission::with(['daara', 'agent'])->findOrFail($id);
    return response()->json($mission);
  }

  public function store(Request $request)
  {
    $request->validate([
      'agent_id'    => 'required|exists:users,id',
      'daara_id'    => 'required|exists:daaras,id',
      'titre'       => 'required|string',
      'description' => 'nullable|string',
      'type'        => 'required|string',
      'date_debut'  => 'nullable|date|after_or_equal:today',
      'date_fin'    => 'nullable|date|after_or_equal:date_debut',
    ]);

    $mission = Mission::create([
      'agent_id'    => $request->agent_id,
      'daara_id'    => $request->daara_id,
      'titre'       => $request->titre,
      'description' => $request->description,
      'type'        => $request->type,
      'date_debut'  => $request->date_debut,
      'date_fin'    => $request->date_fin,
      'statut'      => 'en_attente',
    ]);

    $this->notifierAgent($request, $mission, "Une nouvelle mission vous a été assignée : {$mission->titre}");

    return response()->json($mission->load(['daara', 'agent']), 201);
  }

  public function assignerAgent(Request $request, $id)
  {
    $request->validate([
      'agent_id' => 'required|exists:users,id',
    ]);

    $mission = Mission::findOrFail($id);
    $mission->update(['agent_id' => $request->agent_id]);

    $this->notifierAgent($request, $mission, "Une mission vous a été réassignée : {$mission->titre}");

    return response()->json([
      'message' => 'Agent assigné avec succès.',
      'mission' => $mission->load(['daara', 'agent']),
    ]);
  }

  private function notifierAgent(Request $request, Mission $mission, string $message)
  {
    Notification::create([
      'admin_id'          => $request->user()->id,
      'mission_id'        => $mission->id,
      'message'           => $message,
      'type'              => 'mission_assignee',
      'destinataire_type' => 'agent',
      'destinataire_id'   => $mission->agent_id,
      'est_lue'           => false,
      'date_envoi'        => now(),
    ]);
  }
}
