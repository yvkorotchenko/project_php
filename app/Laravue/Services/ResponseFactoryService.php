<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use Illuminate\Contracts\Routing\ResponseFactory as ContractsRoutingResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory as RoutingResponseFactory;

class ResponseFactoryService extends RoutingResponseFactory implements ContractsRoutingResponseFactory
{
    public function paginatedJson(
        $data = [],
        int $total = 0,
        int $status = 200,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json([
            'data' => $data,
            'meta' => [
                'total' => $total,
            ],
        ], $status, $headers, $options);
    }

    public function error(string $message, int $statusCode = 400): JsonResponse
    {
        return $this->json(['error' => $message], $statusCode);
    }

    public function formattedJson(
        array $data = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return $this->json(
            ['data' => $data],
            $status,
            $headers,
            $options,
        );
    }

    public function text(
        string $data = '',
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return $this->json(
            ['data' => $data],
            $status,
            $headers,
            $options,
        );
    }

    public function formattedObject(
        object $data,
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return $this->json(
            ['data' => $data],
            $status,
            $headers,
            $options,
        );
    }

    public function messageSuccessJson(int $status = 200, string $message = 'Success'): JsonResponse
    {
        return $this->json(
            [
                'data' => ['message' => $message],
            ],
            $status,
        );
    }

    public function formattedSTDClassJson(
        \stdClass $data = null,
        int $status = 200,
        array $headers = [],
        int $options = 0,
    ): JsonResponse {
        return $this->json(
            ['data' => $data],
            $status,
            $headers,
            $options,
        );
    }
}

