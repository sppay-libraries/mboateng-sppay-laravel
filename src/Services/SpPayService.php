<?php

namespace Mboateng\SpPayLaravel\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;
use Mboateng\SpPayLaravel\Exceptions\SpPayException;

class SpPayService
{
    protected $client;
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['base_url'],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function getAuthHeaders()
    {
        $this->ensureAuthenticated();
        return [
            'Authorization' => 'Bearer ' . Cache::get($this->config['cache_token_key']),
        ];
    }

    protected function ensureAuthenticated()
    {
        if (!Cache::has($this->config['cache_token_key'])) {
            $this->authenticate();
        }
    }

    protected function authenticate()
    {
        try {
            $response = $this->client->post('/oauth/token', [
                'json' => [
                    'grant_type' => 'password',
                    'client_id' => $this->config['client_id'],
                    'client_secret' => $this->config['client_secret'],
                    'username' => $this->config['username'],
                    'password' => $this->config['password'],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $expiresIn = $data['expires_in'] ?? 3600;

            Cache::put(
                $this->config['cache_token_key'],
                $data['access_token'],
                $expiresIn - 60
            );
        } catch (RequestException $e) {
            throw new SpPayException('Authentication failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function validatePayment(array $payload)
    {
        try {
            $response = $this->client->post('/v1/api/payments/validate', [
                'headers' => $this->getAuthHeaders(),
                'json' => $payload,
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new SpPayException('Payment validation failed', $e->getCode(), $e);
        }
    }

    // Add other API methods similarly...
}
