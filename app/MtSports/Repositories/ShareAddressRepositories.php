<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\ShareAddress;

class ShareAddressRepositories
{
    private const BASE_PATH  = '/crud/share';
    private const BASE_PATH_GET  = '/share';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function get(int $id)
    {

        return $responseBody =  $this->responseParser->body(
            $this->client->get( 
                self::BASE_PATH_GET,
                [],
                $id
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }

    public function update($query)
    {
        return $responseBody =  $this->responseParser->body(
            $this->client->put( 
                self::BASE_PATH,
                [],
                new ShareAddress(
                    $query['id'],
                    $query['landPageUrl'],
                    $query['qrCodeUrl'],
                    $query['fbContent'],
                    $query['shareUrl'],
                    $query['shareQrCodeUrl'],
                )
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }
}