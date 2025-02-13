<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\StopServiceAnnouncementEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class StopServiceAnnouncementRepository
{
    private const BASE_PATH = '/notifications/service/stop/announcement';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function store(array $data)
    {

        $responseBody =  $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                new StopServiceAnnouncementEntities(
                    $data['contentEn'],
                    $data['contentLocal'],
                    $data['from'],
                    $data['to'],
                    $data['titleEn'],
                    $data['titleLocal'],
                )
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

    }
}
