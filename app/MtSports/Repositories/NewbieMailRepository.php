<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\NewbieMailEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class NewbieMailRepository
{
    private const BASE_PATH = '/crud/message/new';

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
                new NewbieMailEntities(
                    $data['contentEn'],
                    $data['contentLocal'],
                    $data['state'],
                    $data['titleEn'],
                    $data['titleLocal'],
                    $data['operatorId'],
                    $data['operatorName'],
                )
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

    }
}