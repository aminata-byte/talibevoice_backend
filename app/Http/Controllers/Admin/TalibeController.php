<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Talibe;
use Illuminate\Http\Request;

class TalibeController extends Controller
{
    public function index(Request $request)
    {
        $query = Talibe::with(['daara', 'agent']);

        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('prenom', 'like', '%' . $request->search . '%');
        }

        if ($request->has('daara_id')) {
            $query->where('daara_id', $request->daara_id);
        }

        if ($request->has('est_majeur')) {
            $query->where('est_majeur', $request->est_majeur);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $talibe = Talibe::with(['daara', 'agent', 'insertions'])->findOrFail($id);
        return response()->json($talibe);
    }

    public function update(Request $request, $id)
    {
        $talibe = Talibe::findOrFail($id);
        $talibe->update($request->all());
        return response()->json($talibe);
    }

    public function destroy($id)
    {
        $talibe = Talibe::findOrFail($id);
        $talibe->delete();
        return response()->json(['message' => 'Talibé supprimé avec succès.']);
    }
}
