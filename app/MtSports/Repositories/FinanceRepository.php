<?php

namespace App\MtSports\Repositories;

use App\MtSports\Entities\ManualWithdraw;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use Psr\Http\Message\ResponseInterface;

class FinanceRepository
{
    public const FINANCE_PROVIDER_CRYPTO = 'CRYPTO';
    public const FINANCE_PROVIDER_BANK = 'bank';

    private const BASE_PATH = '/finance';
    private const WITHDRAW_BASE_PATH = '/withdraws';
    private const AVAILABLE_STATES_ACTION_URIS = [
        'PROCESS' => '/manual/%d/start-processing',
        'SUBMIT'  => '/manual/%d/confirm',
        'CANCEL'  => '/manual/%d/cancel',
    ];

    public function __construct(
        private MtSportsClient         $client,
        private MtSportsResponseParser $responseParser
    ) {
    }

    public function list(string $financeProviderType): array
    {
        if ($financeProviderType !== self::FINANCE_PROVIDER_BANK && $financeProviderType !== self::FINANCE_PROVIDER_CRYPTO) {
            throw new \InvalidArgumentException('Invalid finance provider type');
        }
        /** @var ResponseInterface $response */
        $requestUri = $financeProviderType === self::FINANCE_PROVIDER_CRYPTO
            ? self::BASE_PATH . '/' . 'crypto-currency'
            : self::BASE_PATH . '/' . 'card';
        $response = $this->client->get($requestUri);
        $body = $this->responseParser->body($response);
        $this->validateBody($body);
        if (!\is_array($body->data)) {
            throw new \Exception('Response body.data must be array');
        }

        return $body->data;
    }

    public function listAll(): array
    {
        $response = $this->client->get(self::BASE_PATH . '/currencies');
        $body = $this->responseParser->body($response);
        $this->validateBody($body);
        if (!\is_array($body->data)) {
            throw new \Exception('Response body.data must be array');
        }

        return $body->data;
    }

    public function merchantWithdraw(int $id): \StdClass
    {
        $response = $this->client->get(
            self::BASE_PATH . \sprintf('/merchant/%d/withdraw', $id)
        );
        $body = $this->responseParser->body($response);
        $this->validateBody($body);

        return $body->data;
    }

    public function createWithdraw(ManualWithdraw $withdrawData): \StdClass
    {
        $response = $this->client->post(self::WITHDRAW_BASE_PATH, [], $withdrawData);
        $body = $this->responseParser->body($response);
        if (\property_exists($body, 'code') && $body->code !== '200') {
            $message = 'Error during creation withdraw';
            if (\property_exists($body, 'message')) {
                $message = $body->message;
            }
            throw new \Exception($message);
        }
        $this->validateBody($body);
        $this->validateBodyType($body);
        $this->checkResponseStatusCode($body);

        return $body->data;
    }

    public function withdrawList(int $page, int $size, array $filterData): \StdClass
    {
        $response = $this->client->get(
            self::WITHDRAW_BASE_PATH,
            \array_merge(
                [
                    'page' => $page,
                    'size' => $size,
                ],
                $filterData
            )
        );
        $body = $this->responseParser->body($response);
        $this->validateBody($body);
        $this->validateBodyType($body);
        $this->checkResponseStatusCode($body);

        return $body->data;
    }

    public function changeWithdrawStatus(int $withdrawId, string $stateAction): \StdClass
    {
        if (!\in_array($stateAction, \array_keys(self::AVAILABLE_STATES_ACTION_URIS))) {
            throw new \Exception('Invalid state-action');
        }
        $response = $this->client->put(
            \sprintf(self::WITHDRAW_BASE_PATH . self::AVAILABLE_STATES_ACTION_URIS[$stateAction], $withdrawId)
        );
        $body = $this->responseParser->body($response);
        $this->validateBody($body);
        $this->validateBodyType($body);
        $this->checkResponseStatusCode($body);

        return $body->data;
    }

    public function withdrawStatesList(): array
    {
        $response = $this->client->get(self::WITHDRAW_BASE_PATH . '/states');
        $body = $this->responseParser->body($response);
        $this->validateBody($body);
        if (!\is_array($body->data)) {
            throw new \Exception('Invalid response data. Data should be array');
        }
        $this->checkResponseStatusCode($body);

        return $body->data;
    }

    private function validateBody($body): void
    {
        if (!\is_object($body)) {
            throw new \Exception('Invalid body from API');
        }
        if (!\property_exists($body, 'data')) {
            throw new \Exception('Response body has not body property');
        }
    }

    private function validateBodyType(object $body): void
    {
        if (!\is_object($body->data)) {
            throw new \Exception('Response body invalid');
        }
    }

    private function checkResponseStatusCode(object $body): void
    {
        if (!\property_exists($body, 'code')) {
            throw new \Exception('Undefined response code');
        }

        if ($body->code !== '200') {
            throw new \Exception(
                \property_exists($body, 'message') && !empty($body->message) && \is_string($body->message)
                    ? $body->message
                    : 'Response body invalid'
            );
        }
    }
}
