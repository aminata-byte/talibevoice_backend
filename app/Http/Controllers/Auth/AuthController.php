<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
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
            'user'  => $user,
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
            'ancien_password'  => 'required|string',
            'nouveau_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->ancien_password, $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->nouveau_password)]);

        return response()->json(['message' => 'Mot de passe modifié avec succès.']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'agent')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Aucun agent trouvé avec cet email.',
            ], 404);
        }

        // Supprimer les anciens OTP
        PasswordResetOtp::where('email', $request->email)->delete();

        // Générer OTP 6 chiffres
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email'     => $request->email,
            'otp'       => $otp,
            'utilise'   => false,
            'expire_at' => Carbon::now()->addMinutes(10),
        ]);

        // Retourner l'OTP directement (simulation — en production envoi par email)
        return response()->json([
            'message'      => 'Code OTP généré avec succès.',
            'otp'          => $otp,
            'expire_dans'  => '10 minutes',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('utilise', false)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Code OTP invalide ou expiré.',
            ], 422);
        }

        return response()->json([
            'message' => 'Code OTP valide.',
            'valid'   => true,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'otp'                   => 'required|string|size:6',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $record = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('utilise', false)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Code OTP invalide ou expiré.',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        $record->update(['utilise' => true]);

        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès.',
        ]);
    }
}
