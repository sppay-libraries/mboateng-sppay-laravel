<?php


namespace Mboateng\SpPayLaravel\Services;

use GuzzleHttp\Client;
use Mboateng\SpPayLaravel\Exceptions\SpPayException;

class PaymentService
{
    protected $client;
    protected $authService;

    public function __construct(Client $client, AuthService $authService)
    {
        $this->client = $client;
        $this->authService = $authService;
    }

    public function validatePayment(array $data)
    {
        return $this->makeRequest('/v1/api/payments/validate', $data);
    }

    public function initiatePayment(array $data)
    {
        return $this->makeRequest('/v1/api/payments/initiate', $data);
    }

    public function submitOtp(string $otp, string $transactionReference)
    {
        return $this->makeRequest('/v1/api/payments/otp/submit', [
            'otp' => $otp,
            'transaction_reference' => $transactionReference
        ]);
    }

    private function makeRequest(string $url, array $data)
    {
        try {
            $token = $this->authService->getAccessToken();

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new SpPayException('API Request failed: ' . $e->getMessage());
        }
    }
}
