<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\TaskManagement;

class TaskManagementRepository
{
    private const BASE_PATH = '/crud/tasks';
    private const BASE_PATH_DAILY = '/crud/tasks/types';
    private const BASE_PATH_COMPANY = '/platforms';
    private const BASE_PATH_ITEMS = '/crud/tasks/items';
    private const BASE_PATH_RECORD = '/crud/tasks/statistic/record';
    private const BASE_PATH_DETAILS = '/crud/tasks/statistic/details';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function listTestManagement(int $page = 1, int $size = 20)
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                [
                    'page' => $page,
                    'size' => $size,
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function store(array $task)
    {
        $responseBody = $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                new TaskManagement(
                    (int)$task['companyId'],
                    (string)$task['descriptionEn'],
                    (string)$task['descriptionSc'],
                    (int)$task['displayPriority'],
                    (string)$task['endDateTime'],
                    (int)$task['id'],
                    (($task['isOpen'])?true:false),
                    (int)$task['item'],
                    (int)$task['numberOfParticipant'],
                    (string)$task['picUrlEn'],
                    (string)$task['picUrlSc'],
                    (float)$task['requirement'],
                    (int)$task['reward'],
                    (string)$task['showEndDateTime'],
                    (string)$task['startDateTime'],
                    (string)$task['titleEn'],
                    (string)$task['titleSc'],
                    (int)$task['type']
                )
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function update(array $task)
    {
        $taskEdit = new TaskManagement(
            (int)$task['companyId'],
            (string)$task['descriptionEn'],
            (string)$task['descriptionSc'],
            (int)$task['displayPriority'],
            (string)$task['endDateTime'],
            (int)$task['id'],
            (($task['isOpen'])?true:false),
            (int)$task['item'],
            (int)$task['numberOfParticipant'],
            (string)$task['picUrlEn'],
            (string)$task['picUrlSc'],
            (float)$task['requirement'],
            (int)$task['reward'],
            (string)$task['showEndDateTime'],
            (string)$task['startDateTime'],
            (string)$task['titleEn'],
            (string)$task['titleSc'],
            (int)$task['type']
        );
        $responseBody = $this->responseParser->body(
            $this->client->put(
                self::BASE_PATH,
                [],
                $taskEdit
            ),
        );
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function destroy(int $id)
    {
        $responseBody = $this->responseParser->body(
            $this->client->delete(
                self::BASE_PATH,
                [
                    'tid' => $id
                ]
            ),
        );

        if ($responseBody->code !== '204') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function dailyList()
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(self::BASE_PATH_DAILY),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function companyList()
    {
        $size = 500;
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_COMPANY,
                [
                    'page' => 1,
                    'size' => $size
                ]
            ),
        );

        if($responseBody->data->total > $size) {
            $responseBody = $this->responseParser->body(
                $this->client->get(
                    self::BASE_PATH_COMPANY,
                    [
                        'page' => 1,
                        'size' => $responseBody->data->total
                    ]
                ),
            );
        }

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function itemList()
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(self::BASE_PATH_ITEMS),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function getRecord(int $page = 1, int $size = 20, string $from = '', string $to = '')
    {
        $query = [
            'page' => $page,
            'size' => $size,
        ];
        if($from){
            $query['from'] = $from;
        }
        if($to){
            $query['to'] = $to;
        }
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_RECORD,
                $query,
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

    public function getDetails(int $page = 1, int $size = 20, string $from = '', string $to = '', int $uid = 0)
    {
        $query = [
            'page' => $page,
            'size' => $size,
        ];
        if($from){
            $query['from'] = $from;
        }
        if($to){
            $query['to'] = $to;
        }
        if($uid){
            $query['uid'] = $uid;
        }
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH_DETAILS,
                $query,
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

}
