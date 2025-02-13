<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\ImportantNoticeEntity;

class NoticeRepository
{
    private const BASE_PATH = '/notifications/important';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function importantNotice(int $page = 1, int $size = 20): \StdClass
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

        return $responseBody->data;
    }

    public function create(ImportantNoticeEntity $notice):void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                $notice
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code ' . $responseCode);
        }
    }

    public function update (ImportantNoticeEntity $notice): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH,
                [],
                $notice
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code ' . $responseCode);
        }
    }

    public function delete(array $ids): void {



        $responseCode = $this->responseParser->statusCode(
            $this->client->delete(
                self::BASE_PATH,
                ['ids' => \implode(',', $ids)],
            ),
        );

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessful response code ' . $responseCode);
        }
    }
}