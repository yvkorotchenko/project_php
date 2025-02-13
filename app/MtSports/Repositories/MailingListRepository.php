<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\MailingListDeleteEntities;
use App\Laravue\Entities\MailingListCreateEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class MailingListRepository
{
    private const BASE_PATH = '/mail/list';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function list(array $data)
    {
        $tempQuery = [
            'page' => !empty($data['page'])?$data['page']:1,
            'size' => !empty($data['size'])?$data['size']:15
        ];
        
        if(!empty($data['type'])) { $tempQuery['type'] = $data['type']; }
        if(!empty($data['operatorId'])) { $tempQuery['operatorId'] = $data['operatorId']; }
        if(!empty($data['operatorName'])) { $tempQuery['operatorName'] = $data['operatorName']; }
        if(!empty($data['from'])) { $tempQuery['from'] = $data['from']; }
        if(!empty($data['to'])) { $tempQuery['to'] = $data['to']; }
        if(!empty($data['status'])) { $tempQuery['status'] = $data['status']; }
        if(!empty($data['receivedStatus'])) { $tempQuery['receivedStatus'] = $data['receivedStatus']; }
        if(!empty($data['uid'])) { $tempQuery['uid'] = $data['uid']; }

        $responseBody =  $this->responseParser->body(
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

    public function destroy(array $data)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->delete(
                self::BASE_PATH,
                [],
                new MailingListDeleteEntities(
                    $data
                )
            ),
        );
        
        if ($responseBody->code !== '204') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;

    }

    public function create(array $data)
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                new MailingListCreateEntities(
                    $data['content'],
                    $data['contentLocal'],
                    $data['goldCoin'],
                    $data['increaseCoef'],
                    $data['increaseValue'],
                    $data['increaseWithdrawStandart'],
                    $data['onTop'],
                    $data['operatorId'],
                    $data['operatorName'],
                    $data['title'],
                    $data['titleLocal'],
                    $data['uids']
                )
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;

    }
}