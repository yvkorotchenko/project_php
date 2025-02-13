<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\VipRecharge;

class VipRechargeRepositories
{
    private const BASE_PATH  = '/recharge/vip';
    private const BASE_PATH_LIST  = '/recharge/list/vip';
    private const BASE_PATH_STATUS  = '/recharge/statuses';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function createRecharge($query)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post( 
                self::BASE_PATH,
                [],
                new VipRecharge(
                    $query['operatorId'],
                    $query['amount'],
                    $query['uid'],
                )
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function listRechargeHistory(array $query)
    {
        $queryRequers = [];
        $queryRequers['page'] = (int) $query['page'] ?? 1;
        $queryRequers['size'] = (int) $query['size'] ?? 20;
        if(!empty($query['amountMax'])) $queryRequers['amountMax'] = $query['amountMax'];
        if(!empty($query['amountMin'])) $queryRequers['amountMin'] = $query['amountMin'];
        if(!empty($query['customerId'])) $queryRequers['customerId'] = $query['customerId'];
        if(!empty($query['dateEnd'])) $queryRequers['dateEnd'] = $query['dateEnd'];
        if(!empty($query['dateStart'])) $queryRequers['dateStart'] = $query['dateStart'];
        if(!empty($query['id'])) $queryRequers['id'] = $query['id'];
        if(!empty($query['operatorId'])) $queryRequers['operatorId'] = $query['operatorId'];
        if(!empty($query['orderDirection'])) $queryRequers['orderDirection'] = $query['orderDirection'];
        if(!empty($query['orderField'])) $queryRequers['orderField'] = $query['orderField'];
        if(!empty($query['status'])) $queryRequers['status'] = $query['status'];

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_LIST,
                $queryRequers
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function listRechargeStatuses()
    {
        $responseBody =  $this->responseParser->body(
            $this->client->get( self::BASE_PATH_STATUS ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }
}