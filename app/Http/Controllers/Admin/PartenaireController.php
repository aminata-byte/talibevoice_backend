<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
  public function index(Request $request)
  {
    $query = Partenaire::withCount('formations');

    if ($request->filled('statut')) {
      $query->where('statut', $request->statut);
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('nom', 'like', "%{$search}%")
          ->orWhere('domaine', 'like', "%{$search}%");
      });
    }

    return response()->json($query->orderBy('created_at', 'desc')->get()->map(function ($p) {
      $p->nombre_formations = $p->formations_count;
      return $p;
    }));
  }

  public function show($id)
  {
    $partenaire = Partenaire::with('formations')->findOrFail($id);
    return response()->json($partenaire);
  }

  public function valider($id)
  {
    $partenaire = Partenaire::findOrFail($id);
    $partenaire->update(['statut' => 'valide']);
    return response()->json(['message' => 'Partenaire validé avec succès.']);
  }

  public function rejeter($id)
  {
    $partenaire = Partenaire::findOrFail($id);
    $partenaire->update(['statut' => 'rejete']);
    return response()->json(['message' => 'Partenaire rejeté.']);
  }

  public function destroy($id)
  {
    $partenaire = Partenaire::findOrFail($id);
    $partenaire->delete();
    return response()->json(['message' => 'Partenaire supprimé avec succès.']);
  }
}
