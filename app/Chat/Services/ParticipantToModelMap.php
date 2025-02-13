<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Models\ChatAnonymousClient;
use App\Chat\Models\CustomerServiceOperator;
use App\Chat\Repositories\CustomerServiceOperatorRepository;
use App\MtSports\Models\Client;
use App\MtSports\Repositories\ClientsRepository;
use Illuminate\Contracts\Redis\Connection as RedisConnection;

class ParticipantToModelMap
{
    private const OPERATOR_IDENT_HASH = 'operators_idents';
    private const CLIENT_IDENT_HASH = 'clients_idents';
    private const CLIENT_ID_TO_PARTICIPANT_ID_HASH = 'client_to_participant';

    private const ANONYMOUS_CLIENT_IDENT_HASH = 'anonymous_clients_idents';

    private ClientsRepository $clientsRepository;
    private CustomerServiceOperatorRepository $operatorRepository;
    private RedisConnection $redis;
    private AnonymousChatUserService $anonymousChatUserService;

    public function __construct(
        ClientsRepository $clientsRepository,
        CustomerServiceOperatorRepository $operatorRepository,
        RedisConnection $redis,
        AnonymousChatUserService $anonymousChatUserService
    ) {
        $this->clientsRepository = $clientsRepository;
        $this->operatorRepository = $operatorRepository;
        $this->redis = $redis;
        $this->anonymousChatUserService = $anonymousChatUserService;
    }

    public function client(ParticipantInterface $clientParticipant): ?Client
    {
        $isExists = $this->clientCommand('HEXISTS', $clientParticipant->identifier());
        if ($isExists !== 1) {
            return null;
        }
        $clientId = (int)$this->clientCommand('HGET', $clientParticipant->identifier());

        return $this->clientsRepository->clientById($clientId);
    }

    public function clientParticipantId(Client $client): ?ParticipantInterface
    {
        if (!$this->redis->command('HEXISTS', [self::CLIENT_ID_TO_PARTICIPANT_ID_HASH, $client->id])) {
            return null;
        }

        return new Participant(
            $this->redis->command('HGET', [self::CLIENT_ID_TO_PARTICIPANT_ID_HASH, $client->id])
        );
    }

    public function anonymous(ParticipantInterface $participant): ?ChatAnonymousClient
    {
        $exists = $this->redis->command(
            'HEXISTS',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );

        if (!$exists) {
            return null;
        }

        $id = (int) $this->redis->command(
            'HGET',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );

        return ChatAnonymousClient::find($id);
    }

    public function operator(ParticipantInterface $operatorParticipant): ?CustomerServiceOperator
    {
        if ($this->operatorCommand('HEXISTS', $operatorParticipant->identifier()) !== 1) {
            return null;
        }
        $operatodId = (int)$this->operatorCommand('HGET', $operatorParticipant->identifier());

        return $this->operatorRepository->operatorById($operatodId);
    }

    public function addClient(ParticipantInterface $clientParticipant, Client $client): void
    {
        if ($this->clientCommand('HEXISTS', $clientParticipant->identifier()) === 0) {
            $this->clientCommand('HSET', $clientParticipant->identifier(), $client->id);
        }

        if ($this->redis->command('HEXISTS', [self::CLIENT_ID_TO_PARTICIPANT_ID_HASH, $client->id]) === 0) {
            $this->redis->command('HSET', [
                self::CLIENT_ID_TO_PARTICIPANT_ID_HASH,
                $client->id,
                $clientParticipant->identifier(),
            ]);
        }
    }

    public function removeClient(ParticipantInterface $clientParticipant): void
    {
        if ($this->clientCommand('HEXISTS', $clientParticipant->identifier()) === 1) {

            $clientId = $this->clientCommand('HGET', $clientParticipant->identifier());
            $this->clientCommand('HDEL', $clientParticipant->identifier());

            if ($this->redis->command('HEXISTS', [self::CLIENT_ID_TO_PARTICIPANT_ID_HASH, $clientId]) === 1) {
                $this->redis->command('HDEL', [
                    self::CLIENT_ID_TO_PARTICIPANT_ID_HASH,
                    $clientId
                ]);
            }
        }
    }

    public function addOperator(ParticipantInterface $operatorParticipant, CustomerServiceOperator $operator): void
    {
        if ($this->operatorCommand('HEXISTS', $operatorParticipant->identifier()) === 1) {
            return;
        }
        $this->operatorCommand('HSET', $operatorParticipant->identifier(), $operator->id);
    }

    public function removeOperator(ParticipantInterface $operatorParticipant): void
    {
        if ($this->operatorCommand('HEXISTS', $operatorParticipant->identifier()) !== 1) {
            return;
        }
        $this->operatorCommand('HDEL', $operatorParticipant->identifier());
    }

    public function addAnonymous(ParticipantInterface $participant, ChatAnonymousClient $anonymousClient): void
    {
        $alreadyExists = $this->redis->command(
            'HEXISTS',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );
        if ($alreadyExists) return;

        $this->redis->command(
            'HSET',
            [
                self::ANONYMOUS_CLIENT_IDENT_HASH,
                $participant->identifier(),
                $anonymousClient->id
            ]
        );
    }

    public function removeAnonymous(ParticipantInterface $participant): void
    {
        $exists = $this->redis->command(
            'HEXISTS',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );
        if ($exists) {
            $this->redis->command(
                'HDEL',
                [
                    self::ANONYMOUS_CLIENT_IDENT_HASH,
                    $participant->identifier(),
                ]
            );
        }
    }

    public function isAnonymousClient(ParticipantInterface $participant): bool
    {
        $exists = $this->redis->command(
            'HEXISTS',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );

        if (!$exists) {
            return false;
        }

        $id = (int) $this->redis->command(
            'HGET',
            [self::ANONYMOUS_CLIENT_IDENT_HASH, $participant->identifier()]
        );

        return $this->anonymousChatUserService->exists($id);
    }

    private function operatorCommand(string $command, ...$args)
    {
        return $this->redis->command($command, \array_merge([self::OPERATOR_IDENT_HASH], $args));
    }

    private function clientCommand(string $command, ...$args)
    {
        return $this->redis->command($command, \array_merge([self::CLIENT_IDENT_HASH], $args));
    }
}
