<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Entities\WinWithdrawMessageEntity;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class WinWithdrawMessageRepository
{
    private const BASE_PATH = '/crud/win_withdraw';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function winWithdrawMessage(int $size = 20, int $page = 1): \StdClass
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(self::BASE_PATH),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function createWinWithdrawMessage(array $withdrawMessage): void {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new WinWithdrawMessageEntity($withdrawMessage),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }
    }

    public function deleteWinWithdrawMessage(string $winWithdrawMessageIds): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->delete(
                self::BASE_PATH,
                [
                    'ids' => $winWithdrawMessageIds,
                ],
            ),
        );

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }
}