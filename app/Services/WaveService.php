<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class WaveService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.wave.api_key'));
    }

    /**
     * Crée une session de paiement Wave et retourne l'URL vers laquelle rediriger le donateur.
     * Référence : https://docs.wave.com/checkout (POST /v1/checkout/sessions)
     */
    public function createCheckoutSession(float $amount, string $reference, string $successUrl, string $errorUrl): array
    {
        if (!$this->isConfigured()) {
            return [
                'launch_url' => URL::temporarySignedRoute('paiement.simuler', now()->addMinutes(30), [
                    'provider' => 'wave',
                    'reference' => $reference,
                    'amount' => $amount,
                    'success_url' => $successUrl,
                    'error_url' => $errorUrl,
                ]),
                'simulated' => true,
            ];
        }

        $response = Http::withToken(config('services.wave.api_key'))
            ->post(rtrim(config('services.wave.base_url'), '/') . '/v1/checkout/sessions', [
                'amount' => (string) $amount,
                'currency' => 'XOF',
                'client_reference' => $reference,
                'success_url' => $successUrl,
                'error_url' => $errorUrl,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Wave checkout session failed: ' . $response->body());
        }

        return [
            'launch_url' => $response->json('wave_launch_url'),
            'session_id' => $response->json('id'),
            'simulated' => false,
        ];
    }
}
