<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redistribution;
use Illuminate\Http\Request;

class RedistributionController extends Controller
{
    public function index()
    {
        $redistributions = Redistribution::with(['don', 'daara'])->get();
        return response()->json($redistributions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'don_id' => 'required|exists:dons,id',
            'daara_id' => 'required|exists:daaras,id',
            'montant' => 'required|numeric',
            'motif' => 'nullable|string',
        ]);

        $redistribution = Redistribution::create([
            'don_id' => $request->don_id,
            'daara_id' => $request->daara_id,
            'montant' => $request->montant,
            'motif' => $request->motif,
            'date_redistribution' => now(),
            'statut' => 'planifie',
        ]);

        return response()->json($redistribution, 201);
    }

    public function valider($id)
    {
        $redistribution = Redistribution::findOrFail($id);
        $redistribution->update(['statut' => 'valide']);
        return response()->json(['message' => 'Redistribution validée avec succès.']);
    }
}
