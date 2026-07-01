<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Objectif;
use Illuminate\Http\Request;

class ObjectifController extends Controller
{
  public function mesObjectifs(Request $request)
  {
    $today = now()->toDateString();

    $objectifs = Objectif::where('agent_id', $request->user()->id)
      ->where('date_debut', '<=', $today)
      ->where('date_fin', '>=', $today)
      ->get();

    return response()->json($objectifs);
  }
}
