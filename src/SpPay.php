<?php


namespace Mboateng\SpPay;

use Illuminate\Support\Facades\Http;

class SpPay
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function generateAPIToken(
        string $username,
        string $password,
        string $clientId = null,
        string $clientSecret = null
    ): array
    {
        try {
            $response = Http::post($this->config['base_url'] . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $clientId ?? $this->config['client_id'],
                'client_secret' => $clientSecret ?? $this->config['client_secret'],
                'username' => $username,
                'password' => $password,
            ]);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function initiatePayment(
        string $bearerToken,
        string $receiveAccountNumber,
        float  $amount,
        string $payerEmail,
        string $payerAccountCode,
        string $payerAccountNumber,
        string $callbackUrl
    ): array
    {
        try {
            $response = Http::withToken($bearerToken)
                ->post($this->config['base_url'] . '/v1/api/payments/initiate', [
                    'receive_account_no' => $receiveAccountNumber,
                    'amount' => $amount,
                    'payer' => [
                        'email' => $payerEmail,
                        'account' => [
                            'code' => $payerAccountCode,
                            'number' => $payerAccountNumber,
                        ],
                    ],
                    'callback_url' => $callbackUrl,
                ]);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    // Add all other methods following the same pattern...

    protected function formatResponse($response): array
    {
        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json() ?? $response->body(),
        ];
    }

    protected function errorResponse(\Exception $e): array
    {
        return [
            'success' => false,
            'error' => 'API Request Error: ' . $e->getMessage(),
        ];
    }
}
