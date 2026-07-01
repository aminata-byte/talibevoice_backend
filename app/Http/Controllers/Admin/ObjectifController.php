<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Objectif;
use Illuminate\Http\Request;

class ObjectifController extends Controller
{
  public function index()
  {
    $objectifs = Objectif::with('agent')
      ->orderBy('created_at', 'desc')
      ->get();

    return $objectifs->map(function ($objectif) {
      $agentId = $objectif->agent_id;
      $debut = $objectif->date_debut;
      $fin = $objectif->date_fin;

      if ($objectif->type === 'talibes') {
        $valeurReelle = \App\Models\Talibe::where('agent_id', $agentId)
          ->whereBetween('created_at', [$debut, $fin])
          ->count();
      } elseif ($objectif->type === 'daaras') {
        // Daara n'a pas agent_id — on compte via les talibés distincts par daara
        $valeurReelle = \App\Models\Talibe::where('agent_id', $agentId)
          ->whereBetween('created_at', [$debut, $fin])
          ->distinct('daara_id')
          ->count('daara_id');
      } elseif ($objectif->type === 'rapports') {
        $valeurReelle = \App\Models\Rapport::where('agent_id', $agentId)
          ->whereIn('statut', ['soumis', 'valide'])
          ->whereBetween('created_at', [$debut, $fin])
          ->count();
      } else {
        $valeurReelle = 0;
      }

      $objectif->valeur_reelle = $valeurReelle;
      $objectif->atteint = $valeurReelle >= $objectif->valeur_cible;
      return $objectif;
    });
  }

  public function store(Request $request)
  {
    $request->validate([
      'agent_id'     => 'required|exists:users,id',
      'type'         => 'required|in:talibes,daaras,rapports',
      'valeur_cible' => 'required|integer|min:1',
      'date_debut'   => 'required|date|after_or_equal:today',
      'date_fin'     => 'required|date|after_or_equal:date_debut',
    ]);

    $objectif = Objectif::create($request->only([
      'agent_id',
      'type',
      'valeur_cible',
      'date_debut',
      'date_fin',
    ]));

    return response()->json($objectif->load('agent'), 201);
  }

  public function update(Request $request, $id)
  {
    $objectif = Objectif::findOrFail($id);

    $request->validate([
      'valeur_cible' => 'sometimes|integer|min:1',
      'date_debut'   => 'sometimes|date',
      'date_fin'     => 'sometimes|date|after_or_equal:date_debut',
    ]);

    $objectif->update($request->only(['valeur_cible', 'date_debut', 'date_fin']));
    return response()->json($objectif->load('agent'));
  }

  public function destroy($id)
  {
    Objectif::findOrFail($id)->delete();
    return response()->json(['message' => 'Objectif supprimé.']);
  }

  public function parAgent($agentId)
  {
    $objectifs = Objectif::where('agent_id', $agentId)
      ->orderBy('date_fin', 'desc')
      ->get();
    return response()->json($objectifs);
  }
}
