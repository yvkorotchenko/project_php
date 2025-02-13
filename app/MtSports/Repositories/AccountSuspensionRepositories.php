<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class AccountSuspensionRepositories
{
    private const BASE_PATH_USER_DISABLE = '/crud/users/disable/';
    private const BASE_PATH_USER_ENABLE  = '/crud/users/enable/';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function sendPlayerBanned(int $id)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post( self::BASE_PATH_USER_DISABLE . $id ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }

    public function sendPlayerUnBanned(int $id)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post( self::BASE_PATH_USER_ENABLE . $id),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }
}