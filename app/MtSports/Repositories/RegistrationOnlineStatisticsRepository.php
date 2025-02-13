<?php

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class RegistrationOnlineStatisticsRepository
{
    private const BASE_PATH = '/statistics/registration-data';

    public function __construct(
        private MtSportsResponseParser $responseParser,
        private MtSportsClient $client,
    ) {
    }

    public function list(string $from, string $to): \stdClass
    {
        $from = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $from);
        $to = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $to);

        if ($from === false) {
            throw new \Exception('Invalid format for field `from`');
        }

        if ($to === false) {
            throw new \Exception('Invalid format for field `to`');
        }
        $from = $from->getTimestamp();
        $to = $to->getTimestamp();

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                ['from' => $from, 'to' => $to]
            )
        );
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function latest(): \stdClass
    {
        $from = new \DateTime();
        $from->modify('+ 1 hour');
        $to = new \DateTime();
        $to->modify('- 1 hour');

        return $this->list($from->format('Y-m-d H:00:00'), $to->format('Y-m-d H:00:00'));
    }
}
