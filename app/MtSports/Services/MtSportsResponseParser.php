<?php

declare(strict_types=1);

namespace App\MtSports\Services;

use Psr\Http\Message\ResponseInterface;

class MtSportsResponseParser
{
    public function body(ResponseInterface $response): mixed
    {
        return \json_decode(
            $response->getBody()->getContents(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    public function statusCode(ResponseInterface $response): int
    {
        $body = $this->body($response);

        return (int)$body->code;
    }
}
