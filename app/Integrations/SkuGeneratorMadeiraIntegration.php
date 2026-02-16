<?php

namespace App\Integrations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SkuGeneratorMadeiraIntegration
{
    private Client $httpClient;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->apiKey     = (string) config('sku_generator_service.apikey');
        $this->baseUrl    = (string) config('sku_generator_service.base_url');
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
                'Content-Type' => 'application/json',
                'apikey'       => $this->apiKey,
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
