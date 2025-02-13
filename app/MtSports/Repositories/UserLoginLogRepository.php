<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\ActivityConfigurationEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class UserLoginLogRepository
{
    private const BASE_PATH = '/crud/login/info';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function listPagination(int $page = 1, int $size = 20, int $uid = 0): \StdClass
    {
        $arr = [
            'page' => $page,
            'size' => $size,
        ];
        if( $uid > 0 ){
            $arr['uid'] = $uid;
        }
        $responseBody =  $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH.(($uid)?"/".$uid:""),
                $arr    
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }
}