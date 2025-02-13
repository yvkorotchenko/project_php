<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use Illuminate\Contracts\Routing\ResponseFactory as ContractsRoutingResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory as RoutingResponseFactory;
use phpDocumentor\Reflection\Types\True_;

class ResponseFactory extends RoutingResponseFactory implements ContractsRoutingResponseFactory
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
        $data = [],
        int $status = 200,
        array $headers = [],
        $options = 0
    ): JsonResponse {
        return $this->json(
            ['data' => $data],
            $status,
            $headers,
            $options,
        );
    }
}