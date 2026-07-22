<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Don;
use App\Models\Donateur;
use App\Services\OrangeMoneyService;
use App\Services\WaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonPublicController extends Controller
{
    public function __construct(
        protected WaveService $wave,
        protected OrangeMoneyService $orangeMoney,
    ) {
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:financier,materiel',
            'anonyme' => 'boolean',
        ]);

        $donateur = Donateur::create([
            'nom' => $request->anonyme ? null : $request->nom,
            'prenom' => $request->anonyme ? null : $request->prenom,
            'email' => $request->anonyme ? null : $request->email,
            'telephone' => $request->anonyme ? null : $request->telephone,
            'est_anonyme' => $request->anonyme ?? false,
        ]);

        $reference = 'DON-' . strtoupper(Str::random(10));

        $don = Don::create([
            'donateur_id' => $donateur->id,
            'reference' => $reference,
            'type' => $request->type,
            'montant' => $request->montant ?? null,
            'mode_paiement' => $request->mode_paiement ?? null,
            'items_materiel' => $request->items ?? null,
            'statut' => 'en_attente',
            'date_don' => now(),
        ]);

        // Don matériel : aucun paiement à initier.
        if ($request->type === 'materiel') {
            return response()->json([
                'message' => 'Don soumis avec succès. Merci pour votre générosité !',
                'don' => $don,
            ], 201);
        }

        // Don financier : initier le paiement (Wave ou Orange Money).
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'mode_paiement' => 'required|in:wave,orange',
        ]);

        $frontend = rtrim(config('services.frontend_url'), '/');
        $successUrl = "{$frontend}/faire-un-don/succes?reference={$reference}";
        $errorUrl = "{$frontend}/faire-un-don/echec?reference={$reference}";
        $notifUrl = route('paiement.orange.notification');

        try {
            if ($request->mode_paiement === 'wave') {
                $result = $this->wave->createCheckoutSession(
                    (float) $request->montant,
                    $reference,
                    $successUrl,
                    $errorUrl,
                );
                $redirectUrl = $result['launch_url'];
            } else {
                $result = $this->orangeMoney->initiatePayment(
                    (float) $request->montant,
                    $reference,
                    $successUrl,
                    $errorUrl,
                    $notifUrl,
                );
                $redirectUrl = $result['payment_url'];
                $don->update(['pay_token' => $result['pay_token']]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Impossible d'initier le paiement pour le moment. Réessayez plus tard.",
            ], 502);
        }

        return response()->json([
            'message' => 'Don créé, redirection vers le paiement.',
            'don' => $don,
            'redirect_url' => $redirectUrl,
            'simulated' => $result['simulated'] ?? false,
        ], 201);
    }

    public function stats()
    {
        return response()->json([
            'total_dons' => Don::where('statut', 'valide')->sum('montant'),
            'nombre_dons' => Don::count(),
        ]);
    }

    public function statut(string $reference)
    {
        $don = Don::where('reference', $reference)->firstOrFail();

        return response()->json([
            'statut' => $don->statut,
            'statut_paiement' => $don->statut_paiement,
            'montant' => $don->montant,
            'mode_paiement' => $don->mode_paiement,
        ]);
    }

    /**
     * Page de paiement simulée (mode démo) — affichée uniquement quand aucune
     * clé API Wave/Orange Money n'est configurée sur le serveur.
     */
    public function simuler(Request $request)
    {
        abort_unless($request->hasValidSignature(), 403);

        return view('paiement-simule', [
            'provider' => $request->query('provider'),
            'reference' => $request->query('reference'),
            'amount' => $request->query('amount'),
            'successUrl' => $request->query('success_url'),
            'errorUrl' => $request->query('error_url'),
        ]);
    }

    /**
     * Confirmation du paiement (simulé ou réel via webhook) : ceci ne fait
     * que constater que l'argent a été reçu. La validation du don (passage
     * de `statut` à "valide") reste une action manuelle de l'administrateur
     * via Admin\DonController::valider().
     */
    public function confirmerSimulation(Request $request)
    {
        $don = Don::where('reference', $request->input('reference'))->first();

        if ($don && $request->input('action') === 'payer') {
            $don->update(['statut_paiement' => 'paye']);
            return redirect()->away($request->input('success_url'));
        }

        if ($don) {
            $don->update(['statut_paiement' => 'echoue']);
        }

        return redirect()->away($request->input('error_url'));
    }

    /**
     * Webhook Wave (production). Référence : https://docs.wave.com/checkout
     * La vérification de signature (en-tête Wave-Signature) doit être ajoutée
     * dès qu'une vraie clé webhook Wave est disponible.
     * Ce webhook ne fait que constater le paiement ; la validation du don
     * reste une action manuelle de l'administrateur.
     */
    public function waveWebhook(Request $request)
    {
        $don = Don::where('reference', $request->input('client_reference'))->first();

        if ($don && $request->input('payment_status') === 'succeeded') {
            $don->update(['statut_paiement' => 'paye']);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Notification Orange Money (production).
     * Référence : https://developer.orange.com/apis/om-webpay
     * Cette notification ne fait que constater le paiement ; la validation
     * du don reste une action manuelle de l'administrateur.
     */
    public function orangeNotification(Request $request)
    {
        $don = Don::where('pay_token', $request->input('pay_token'))->first();

        if ($don && $request->input('status') === 'SUCCESS') {
            $don->update(['statut_paiement' => 'paye']);
        }

        return response()->json(['received' => true]);
    }
}
