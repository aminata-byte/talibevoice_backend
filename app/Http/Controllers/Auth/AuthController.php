<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        if ($user->statut === 'bloque') {
            return response()->json(['message' => 'Compte bloqué. Contactez l\'administrateur.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateMe(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'             => 'sometimes|required|string|max:100',
            'email'            => 'sometimes|required|email|unique:users,email,' . $user->id,
            'telephone'        => 'sometimes|nullable|string',
            'zone_affectation' => 'sometimes|nullable|string',
        ]);

        $user->update($request->only(['name', 'email', 'telephone', 'zone_affectation']));

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user'    => $user,
        ]);
    }


    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'ancien_password'   => 'required|string',
            'nouveau_password'  => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->ancien_password, $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->nouveau_password)]);

        return response()->json(['message' => 'Mot de passe modifié avec succès.']);
    }
}
