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

        if ($request->has('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        return response()->json($query->orderBy('priorite')->get());
    }

    public function show($id)
    {
        $besoin = Besoin::with(['daara', 'agent'])->findOrFail($id);
        return response()->json($besoin);
    }

    public function update(Request $request, $id)
    {
        $besoin = Besoin::findOrFail($id);
        $besoin->update($request->all());
        return response()->json($besoin);
    }

    public function destroy($id)
    {
        $besoin = Besoin::findOrFail($id);
        $besoin->delete();
        return response()->json(['message' => 'Besoin supprimé avec succès.']);
    }
}
