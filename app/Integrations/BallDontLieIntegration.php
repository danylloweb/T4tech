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
     * @param int $retryCount
     * @return mixed
     */
    public function send(string $method, string $endpoint, array $data = [], int $retryCount = 0): mixed
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
            if ($exception->getResponse()->getStatusCode() === 429) {
                if ($retryCount < 3) {
                    \Log::info("Rate limit hit (429), retrying after 60 seconds... attempt-> $retryCount", [
                        'endpoint' => $url,
                        'method'   => $method
                    ]);
                    sleep(60);
                    return $this->send($method, $endpoint, $data, $retryCount + 1);
                }
            }

            return [
                'error'     => true,
                'message'   => $exception->getMessage(),
                'errorCode' => $exception->getCode(),
                'url'       => $url,
            ];
        }
    }
}
