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

        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'adresse' => 'required|string',
            'nom_responsable' => 'required|string',
        ]);

        $daara = Daara::create($request->all());
        return response()->json($daara, 201);
    }

    public function show($id)
    {
        $daara = Daara::with(['talibes', 'besoins', 'rapports', 'localisation'])
            ->withCount('talibes')
            ->findOrFail($id);
        return response()->json($daara);
    }

    public function update(Request $request, $id)
    {
        $daara = Daara::findOrFail($id);
        $daara->update($request->all());
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
