<?php

declare(strict_types=1);

namespace App\MtSports\Services;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class MtSportsClient
{
    public function __construct(
        private Client $httpClient,
        private string $baseUri,
        private string $token
    ) {
    }

    public function get(
        string $path,
        array $queryStringParams = []
    ): ResponseInterface {
        return $this->request('GET', $path, $queryStringParams);
    }

    public function post(
        string $path,
        array $queryStringParams = [],
        ?\JsonSerializable $body = null,
        array $formBody = []
    ): ResponseInterface {
        return $this->request('POST', $path, $queryStringParams, $body, $formBody);
    }

    public function put(
        string $path,
        array $queryStringParams = [],
        ?\JsonSerializable $body = null
    ): ResponseInterface {
        return $this->request('PUT', $path, $queryStringParams, $body);
    }

    public function delete(
        string $path,
        array $queryStringParams = [],
        ?\JsonSerializable $body = null
    ): ResponseInterface {
        return $this->request('DELETE', $path, $queryStringParams, $body);
    }

    public function deleteByBodyString(
        string $path,
        array $queryStringParams = [],
        ?string $body = null
    ): ResponseInterface {
        return $this->requestByBodyString('DELETE', $path, $queryStringParams, $body);
    }

    private function request(
        string $method,
        string $path,
        array $queryStringParams = [],
        ?\JsonSerializable $body = null,
        array $formBody = []
    ): ResponseInterface {
        $requestParams = [];
        $contentType = 'application/x-www-form-urlencoded';
        if (null !== $body) {
            $requestParams['json'] = $body;
            $contentType = 'application/json';
        }
        if (\count($formBody) > 0) {
            $requestParams['form_params'] = $formBody;
        }
        if (\count($queryStringParams) > 0) {
            $requestParams['query'] = $queryStringParams;
        }
        $requestParams['headers'] = [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => $contentType,
            'Accept'        => 'application/json',
        ];

        $requestParams['verify'] = false;
        // dd($requestParams);

        return $this->httpClient->request(
            $method,
            $this->baseUri . $path,
            $requestParams
        );
    }

    private function requestByBodyString(
        string $method,
        string $path,
        array $queryStringParams = [],
        ?string $body = null,
        array $formBody = []
    ): ResponseInterface {
        $requestParams = [];
        $contentType = 'application/x-www-form-urlencoded';
        if (null !== $body) {
            $requestParams['json'] = explode(',', $body);
            $contentType = 'application/json';
        }
        if (\count($formBody) > 0) {
            $requestParams['form_params'] = $formBody;
        }
        if (\count($queryStringParams) > 0) {
            $requestParams['query'] = $queryStringParams;
        }
        $requestParams['headers'] = [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => $contentType,
            'Accept'        => 'application/json',
        ];

        $requestParams['verify'] = false;

        return $this->httpClient->request(
            $method,
            $this->baseUri . $path,
            $requestParams
        );
    }
}
