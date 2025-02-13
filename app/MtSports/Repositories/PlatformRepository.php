<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Services\EntityMapper;
use App\Laravue\Entities\Platform;
use App\MtSports\Entities\Platform as MtSportPlatform;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use Psr\Http\Message\ResponseInterface;

class PlatformRepository
{
    private const BASE_PATH = '/platforms';

    public function __construct(
        private EntityMapper $mapper,
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser
    ) {}

    public function add(Platform $platform): Platform
    {
        $body = $this->parseResponse($this->client->post(self::BASE_PATH, [], new MtSportPlatform(
            $platform->id,
            $platform->parentName,
            $platform->name,
            $platform->visible
        )));

        return $platform; // TODO: replace with from body (MtSports should implement)
    }

    public function find(int $id): ?Platform
    {
        $endpoint = self::BASE_PATH . '/' . $id;
        $response = $this->client->get($endpoint);

        $result = null;
        $body = $this->responseParser->body($response);
        if ((int)$body->code === 200) {
            $result = $this->rowToPlatform($body->data);
        }

        return $result;
    }

    public function remove(Platform $platform): bool
    {
        return $this->removeById(($platform->id));
    }

    public function removeById(int $id): bool
    {
        $endpoint = self::BASE_PATH . '/' . $id;
        $response = $this->client->delete($endpoint);

        return $this->responseParser->statusCode($response) === 204;
    }

    public function update(Platform $platform): ?Platform
    {
        $uri = self::BASE_PATH;
        $requestData = $this->mapper->instantiateByMap(
            $platform,
            [
                'id' => 'id',
                'name' => 'name',
                'parentName' =>  'identity',
                'visible' => 'isOpen',
            ],
            MtSportPlatform::class
        );

        $this->client->put($uri, [], $requestData);

        return $this->find($platform->id);
    }

    public function countAll(): int
    {
        $body = $this->parseResponse($this->client->get(self::BASE_PATH, [
            'page' => 1,
            'pageSize' => 1,
        ]));

        return $body->data->total;
    }

    public function findWithOffset(int $page, int $perPage): array
    {
        $result = [];
        $body = $this->parseResponse($this->client->get(self::BASE_PATH, [
            'pageNo' => $page,
            'pageSize' => $perPage,
        ]));

        foreach ($body->data->result as $row) {
            $result[] = $this->rowToPlatform($row);
        }

        return ['data' => $result, 'total' => $body->data->total];
    }

    private function rowToPlatform(object $row): Platform
    {
        return  new Platform(
            $row->id,
            $row->name,
            $row->isOpen,
            $row->identity
        );
    }

    private function parseResponse(ResponseInterface $response): mixed
    {
        return $this->responseParser->body($response);
    }
}
