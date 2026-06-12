<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insertion;
use Illuminate\Http\Request;

class InsertionController extends Controller
{
    public function index(Request $request)
    {
        $query = Insertion::with(['talibe', 'partenaire', 'formation']);

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $insertion = Insertion::with(['talibe', 'partenaire', 'formation'])->findOrFail($id);
        return response()->json($insertion);
    }

    public function valider($id)
    {
        $insertion = Insertion::findOrFail($id);
        $insertion->update(['statut' => 'valide']);
        return response()->json(['message' => 'Insertion validée avec succès.']);
    }

    public function cloturer($id)
    {
        $insertion = Insertion::findOrFail($id);
        $insertion->update([
            'statut' => 'cloture',
            'date_cloture' => now(),
        ]);
        return response()->json(['message' => 'Insertion clôturée avec succès.']);
    }
}
