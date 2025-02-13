<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\MassMailingListCreateEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class MassMailingListRepository
{
    private const BASE_PATH = '/mail/list/mass';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function create(array $data)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                new MassMailingListCreateEntities(
                    $data["content"],
                    $data["contentLocal"],
                    $data["goldCoin"],
                    $data["loginEndTime"],
                    $data["loginStartTime"],
                    $data["maxRecharge"],
                    $data["minRecharge"],
                    $data["onTop"],
                    $data["operatorId"],
                    $data["operatorName"],
                    $data["title"],
                    $data["titleLocal"],
                    $data["withdrawalStandard"],
                )
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;

    }
}
