<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Daara;
use Illuminate\Http\Request;

class DaaraController extends Controller
{
    public function index(Request $request)
    {
        $query = Daara::withCount('talibes')->with('besoins');

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->orderBy('nom')->get()->map(function ($d) {
            $d->nombre_talibes = $d->talibes_count;
            return $d;
        }));
    }

    public function show($id)
    {
        $daara = Daara::with(['talibes', 'besoins', 'rapports'])
            ->withCount('talibes')
            ->findOrFail($id);

        $daara->nombre_talibes = $daara->talibes_count;
        return response()->json($daara);
    }

    public function destroy($id)
    {
        $daara = Daara::findOrFail($id);
        $daara->delete();
        return response()->json(['message' => 'Daara supprimé avec succès.']);
    }

    public function activer($id)
    {
        $daara = Daara::findOrFail($id);
        $daara->update(['statut' => 'actif']);
        return response()->json(['message' => 'Daara activé avec succès.']);
    }

    public function desactiver($id)
    {
        $daara = Daara::findOrFail($id);
        $daara->update(['statut' => 'inactif']);
        return response()->json(['message' => 'Daara désactivé avec succès.']);
    }

    public function valider($id)
    {
        $daara = Daara::findOrFail($id);
        $daara->update(['statut' => 'actif']);
        return response()->json(['message' => 'Daara validé avec succès.']);
    }
}
