<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\VipRecharge;

class SensetiveOperationLogRepositories
{
    private const BASE_PATH  = '/crud/sensitive-operation-logs';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function list(int $page, int $size, int $uid)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->get( 
                self::BASE_PATH . (($uid > 0)? "/uid/" . $uid : ""),
                [
                    'page' => $page, 
                    'size' => $size
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }
}