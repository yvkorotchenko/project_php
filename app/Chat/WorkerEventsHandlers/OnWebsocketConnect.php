<?php

declare(strict_types=1);

namespace App\Chat\WorkerEventsHandlers;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Models\CustomerServiceOperator;
use App\Chat\Services\AnonymousChatUserService;
use App\Chat\Services\ClientLanguageRepository;
use App\Chat\Services\ParticipantToModelMap;
use App\MtSports\Services\ClientAuthenticator;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;

/**
 * Event that triggers when client connect to chat
 */
class OnWebsocketConnect
{
    private const CLIENT_TOKEN_PARAM_NAME = 'client_token';
    private const OPERATOR_TOKEN_PARAM_NAME = 'operator_token';

    private JWT $operatorAuthenticator;
    private ClientAuthenticator $clientAuthenticator;
    private ParticipantToModelMap $participantToModelMap;
    private AnonymousChatUserService $anonymousChatUserService;
    private ClientLanguageRepository $clientLanguageRepository;

    public function __construct(
        JWT $auth,
        ClientAuthenticator $clientAuthenticator,
        ParticipantToModelMap $participantToModelMap,
        AnonymousChatUserService $anonymousChatUserService,
        ClientLanguageRepository $clientLanguageRepository
    ) {
        $this->operatorAuthenticator = $auth;
        $this->clientAuthenticator = $clientAuthenticator;
        $this->participantToModelMap = $participantToModelMap;
        $this->anonymousChatUserService = $anonymousChatUserService;
        $this->clientLanguageRepository = $clientLanguageRepository;
    }

    public function __invoke($participantIdent, $data): void
    {
        if (!$this->validateQueryParams($data)) {
            $this->closeClientConnection($participantIdent);
        }
        $queryParams = $data['get'];

        $valid = false;

        if (\array_key_exists(self::CLIENT_TOKEN_PARAM_NAME, $queryParams)) {
            $client = $this->clientAuthenticator->authenticate($queryParams[self::CLIENT_TOKEN_PARAM_NAME]);
            if (null === $client) {
                $anonymousClient = $this->anonymousChatUserService->findClientByToken($queryParams[self::CLIENT_TOKEN_PARAM_NAME]);
                if (null !== $anonymousClient) {
                    $valid = true;
                    $this->participantToModelMap->addAnonymous(new Participant($participantIdent), $anonymousClient);
                }
            } else {
                $valid = true;
                $this->participantToModelMap->addClient(new Participant($participantIdent), $client);
            }
            $this->clientLanguageRepository->add(
                new Participant($participantIdent),
                $this->parseLanguageFromRequest($data)
            );
        } elseif (
            \array_key_exists(self::OPERATOR_TOKEN_PARAM_NAME, $queryParams)
            && $valid = $this->checkOperatorToken($queryParams[self::OPERATOR_TOKEN_PARAM_NAME])
        ) {
            /** @var CustomerServiceOperator $operator */
            $operator = Auth::guard('api-operators-chat')->user();
            $this->participantToModelMap->addOperator(new Participant($participantIdent), $operator);
        }

        if (!$valid) {
            $this->closeClientConnection($participantIdent);
        }
    }

    private function validateQueryParams(array $queryParams): bool
    {
        if (!\array_key_exists('get', $queryParams)) {
            return false;
        }
        if (
            !\array_key_exists('client_token', $queryParams['get'])
            && !\array_key_exists('operator_token', $queryParams['get'])
        ) {
            return false;
        }
        if (
            \array_key_exists('client_token', $queryParams['get'])
            && !\is_string($queryParams['get']['client_token'])
        ) {
            return false;
        }
        if (
            \array_key_exists('operator_token', $queryParams['get'])
            && !\is_string($queryParams['get']['operator_token'])
        ) {
            return false;
        }

        return true;
    }

    private function checkClientToken(string $token): bool
    {
        return $this->clientAuthenticator->check($token);
    }

    private function checkOperatorToken(string $token): bool
    {
        return $this->operatorAuthenticator
            ->setToken($token)
            ->check();
    }

    private function closeClientConnection($clientId): void
    {
        Gateway::closeClient($clientId, 'Unauthorized');
    }

    private function parseLanguageFromRequest(array $data): string
    {
        $lang = '';

        if (\array_key_exists('server', $data)
            && \is_array($data['server'])
            && \array_key_exists('HTTP_ACCEPT_LANGUAGE', $data['server'])
            && \is_string($data['server']['HTTP_ACCEPT_LANGUAGE'])
        ) {
            $lang = \substr($data['server']['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        if (!$lang) $lang = '';

        return $lang;
    }
}
