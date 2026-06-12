<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Daara;
use Illuminate\Http\Request;

class DaaraPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Daara::where('statut', 'actif')
            ->withCount('talibes')
            ->with('besoins');

        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->has('region')) {
            $query->where('region', $request->region);
        }

        $daaras = $query->get();

        return response()->json($daaras);
    }

    public function show($id)
    {
        $daara = Daara::where('statut', 'actif')
            ->with(['besoins', 'localisation'])
            ->withCount('talibes')
            ->findOrFail($id);

        return response()->json($daara);
    }

    public function besoins($id)
    {
        $daara = Daara::findOrFail($id);
        $besoins = $daara->besoins()->orderBy('priorite')->get();
        return response()->json($besoins);
    }

    public function stats()
    {
        return response()->json([
            'total_talibes' => \App\Models\Talibe::count(),
            'total_daaras' => Daara::where('statut', 'actif')->count(),
            'total_dons' => \App\Models\Don::where('statut', 'valide')->sum('montant'),
        ]);
    }
}
