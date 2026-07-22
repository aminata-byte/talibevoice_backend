<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class OrangeMoneyService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.orange_money.merchant_key'))
            && !empty(config('services.orange_money.client_id'))
            && !empty(config('services.orange_money.client_secret'));
    }

    /**
     * Référence : https://developer.orange.com/apis/om-webpay (OAuth2 client_credentials)
     */
    protected function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(
                config('services.orange_money.client_id'),
                config('services.orange_money.client_secret'),
            )
            ->post(rtrim(config('services.orange_money.base_url'), '/') . '/oauth/v2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Orange Money OAuth failed: ' . $response->body());
        }

        return $response->json('access_token');
    }

    /**
     * Initie un paiement Orange Money Web et retourne l'URL de paiement.
     * Référence : POST /orange-money-webpay/{env}/v1/webpayment
     */
    public function initiatePayment(
        float $amount,
        string $orderId,
        string $returnUrl,
        string $cancelUrl,
        string $notifUrl,
    ): array {
        if (!$this->isConfigured()) {
            return [
                'payment_url' => URL::temporarySignedRoute('paiement.simuler', now()->addMinutes(30), [
                    'provider' => 'orange',
                    'reference' => $orderId,
                    'amount' => $amount,
                    'success_url' => $returnUrl,
                    'error_url' => $cancelUrl,
                ]),
                'pay_token' => 'simulated-' . $orderId,
                'simulated' => true,
            ];
        }

        $token = $this->getAccessToken();
        $env = config('services.orange_money.env');

        $response = Http::withToken($token)
            ->post(rtrim(config('services.orange_money.base_url'), '/') . "/orange-money-webpay/{$env}/v1/webpayment", [
                'merchant_key' => config('services.orange_money.merchant_key'),
                'currency' => 'OUV',
                'order_id' => $orderId,
                'amount' => $amount,
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'notif_url' => $notifUrl,
                'lang' => 'fr',
                'reference' => 'Don TalibeVoice',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Orange Money webpayment failed: ' . $response->body());
        }

        return [
            'payment_url' => $response->json('payment_url'),
            'pay_token' => $response->json('pay_token'),
            'simulated' => false,
        ];
    }
}
