<?php


namespace Mboateng\SpPayLaravel\Services;

use GuzzleHttp\Client;
use Mboateng\SpPayLaravel\Exceptions\SpPayException;

class AuthService
{
    protected $client;
    protected $config;

    public function __construct(array $config)
    {
        $this->client = new Client(['base_uri' => $config['base_url']]);
        $this->config = $config;
    }

    public function getAccessToken()
    {
        try {
            $response = $this->client->post('/oauth/token', [
                'json' => [
                    'grant_type' => 'password',
                    'client_id' => $this->config['client_id'],
                    'client_secret' => $this->config['client_secret'],
                    'username' => $this->config['username'],
                    'password' => $this->config['password']
                ]
            ]);

            return json_decode($response->getBody(), true)['access_token'];
        } catch (\Exception $e) {
            throw new SpPayException('Authentication failed: ' . $e->getMessage());
        }
    }
}
