<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class AllPlayersQueryRepositories
{
    private const BASE_PATH  = '/crud/users/info/player';
    private const BASE_PATH_CHANNEL  = '/crud/users/channel';
    private const BASE_PATH_VIPLEVEL  = '/crud/users/vip-level';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function list(array $query)
    {
        $tempQuery = [
            'page' => !empty($query['page'])?$query['page']:1,
            'size' => !empty($query['size'])?$query['size']:15
        ];
        
        if(!empty($query['fieldName']) && !empty($query['fieldValue'])) { $tempQuery['fieldName'] = $query['fieldName']; }
        if(!empty($query['fieldValue'])) { $tempQuery['fieldValue'] = $query['fieldValue']; }

        if(!empty($query['total']) && (!empty($query['lowerLimit']) || !empty($query['upperLimit']))) { $tempQuery['total'] = $query['total']; }
        if(!empty($query['lowerLimit'])) { $tempQuery['lowerLimit'] = $query['lowerLimit']; }
        if(!empty($query['upperLimit'])) { $tempQuery['upperLimit'] = $query['upperLimit']; }

        if(!empty($query['registerEnd'])) { $tempQuery['registerEnd'] = $query['registerEnd']; }
        if(!empty($query['registerStart'])) { $tempQuery['registerStart'] = $query['registerStart']; }

        if(!empty($query['loginStart'])) { $tempQuery['loginStart'] = $query['loginStart']; }
        if(!empty($query['loginEnd'])) { $tempQuery['loginEnd'] = $query['loginEnd']; }

        if(!empty($query['channels'])) { $tempQuery['channels'] = implode(',', $query['channels']); }
        
        if(!empty($query['vipLevels'])) { $tempQuery['vipLevels'] = implode(',', $query['vipLevels']); }

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                $tempQuery
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function channel()
    {

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_CHANNEL
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function vipLevel()
    {

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_VIPLEVEL
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

}