<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Besoin;
use Illuminate\Http\Request;

class BesoinController extends Controller
{
    public function index(Request $request)
    {
        $query = Besoin::with(['daara', 'agent']);

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $besoin = Besoin::with(['daara', 'agent'])->findOrFail($id);
        return response()->json($besoin);
    }



    public function destroy($id)
    {
        $besoin = Besoin::findOrFail($id);
        $besoin->delete();
        return response()->json(['message' => 'Besoin supprimé avec succès.']);
    }

    public function resoudre($id)
    {
        $besoin = Besoin::findOrFail($id);
        $besoin->update(['statut' => 'resolu']);
        return response()->json(['message' => 'Besoin marqué comme résolu.']);
    }
}
