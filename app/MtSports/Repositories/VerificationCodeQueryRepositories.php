<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\VerificationCodeQuery;

class VerificationCodeQueryRepositories
{
    private const BASE_PATH  = '/users/send_code';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function getVerificationCode($query)
    {
        return $responseBody =  $this->responseParser->body(
            $this->client->post( 
                self::BASE_PATH,
                [],
                new VerificationCodeQuery(
                    $query['phone'],
                    $query['countryCode'],
                    $query['language']
                )
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }
}