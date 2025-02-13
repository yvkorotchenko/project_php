<?php

declare(strict_types=1);

namespace App\MtSports\Services;

use GuzzleHttp\Client;
use App\MtSports\Models\Client as MTSportsClient;

class ClientAuthenticator
{
    private Client $httpClient;
    private string $authUri;
    private string $serviceToken;

    public function __construct(string $authUri, string $serviceToken, Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->authUri = $authUri;
        $this->serviceToken = $serviceToken;
    }

    public function check(string $token): bool
    {
        $uri = $this->authUri . '/' . $this->serviceToken;

        try {
            $response = $this->httpClient->get($uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'verify' => false,
            ]);
        } catch (\Throwable $e) {
            return false;
        }

        return $response->getStatusCode() === 200;
    }

    public function authenticate(string $token): ?MTSportsClient
    {
        $uri = $this->authUri . '/' . $token;

        try {
            $response = $this->httpClient->get($uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->serviceToken,
                ],
                'verify' => false,
            ]);
            $response = \json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            if (
                \array_key_exists('data', $response)
                && \is_array($response['data'])
                && \array_key_exists('id', $response['data'])
            ) {
                $clientId = $response['data']['id'];
                if (!\is_numeric($clientId)) {
                    return null;
                }
                if (\is_string($clientId)) {
                    $clientId = (int) $clientId;
                }
                return MTSportsClient::find($clientId);
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            dump($e->getMessage()); // TODO: log exception
            return null;
        }
    }
}
