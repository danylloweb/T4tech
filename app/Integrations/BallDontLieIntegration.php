<?php

namespace App\Integrations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BallDontLieIntegration
{
    private Client $httpClient;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->apiKey     = (string) config('balldontlie.apikey');
        $this->baseUrl    = (string) config('balldontlie.url');
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    public function send(string $method, string $endpoint, array $data = []): mixed
    {
        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->apiKey,
            ];
            $url     = $this->baseUrl . $endpoint;
            $options = ['headers' => $headers];
            if ($method !== 'GET') { $options['json'] = $data; }
            $response = $this->httpClient->request($method, $url, $options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return [
                'error'     => true,
                'message'   => $exception->getMessage(),
                'errorCode' => $exception->getCode(),
                'url'       => $url,
            ];
        }
    }
}
