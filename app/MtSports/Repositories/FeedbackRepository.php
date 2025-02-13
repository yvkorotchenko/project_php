<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\FeedbacksReplyManagement;

class FeedbackRepository
{
    private const BASE_PATH = '/feedbacks';
    private const BASE_PATH_REPLY = '/feedbacks/%d/reply';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function listFeedback(int $page = 1, int $size = 20)
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

    public function update(array $reply)
    {
        $data = new FeedbacksReplyManagement(
            (string)$reply['created'],
            (int)$reply['id'],
            (string)$reply['message'],
            (int)$reply['operatorId'],
            (string)$reply['operatorName'],
        );
        
        $responseBody = $this->responseParser->body(
            $this->client->post(
                \sprintf(self::BASE_PATH_REPLY, $reply['id']),
                [],
                $data
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
                self::BASE_PATH . '/' . $id,
                [
                    'id' => $id,
                ]
            ),
        );

        if ($responseBody->code !== '204') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }

}
