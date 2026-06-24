<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilisateurController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,agent',
            'zone_affectation' => 'nullable|string',
            'telephone' => 'nullable|string',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'zone_affectation' => $request->zone_affectation,
            'matricule' => strtoupper(Str::random(8)),
            'statut' => 'actif',
        ]);

        return response()->json([
            'user' => $user,
            'password_temporaire' => $password,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'             => 'sometimes|required|string|max:100',
            'email'            => 'sometimes|required|email|unique:users,email,' . $id,
            'telephone'        => 'sometimes|nullable|string',
            'zone_affectation' => 'sometimes|nullable|string',
            'role'             => 'sometimes|required|in:admin,agent',
        ]);

        $user->update($request->only(['name', 'email', 'telephone', 'zone_affectation', 'role']));
        return response()->json(['message' => 'Utilisateur mis à jour.', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }

    public function bloquer($id)
    {
        $user = User::findOrFail($id);
        $user->update(['statut' => 'bloque']);
        return response()->json(['message' => 'Utilisateur bloqué avec succès.']);
    }

    public function debloquer($id)
    {
        $user = User::findOrFail($id);
        $user->update(['statut' => 'actif']);
        return response()->json(['message' => 'Utilisateur débloqué avec succès.']);
    }
}
