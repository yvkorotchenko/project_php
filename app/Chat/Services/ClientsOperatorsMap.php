<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use Illuminate\Contracts\Redis\Connection as RedisConnection;

class ClientsOperatorsMap
{
    private const CLIENT_TO_OPERATOR_MAP_NAME = 'client_opeartor';
    private const OPERATOR_CLIENTS_MAP_NAME = 'operator_clients';
    private const MAP_DELIMITER = ',';

    private RedisConnection $redis;

    public function __construct(RedisConnection $redis)
    {
        $this->redis = $redis;
    }

    public function assignOperatorToClient(
        ParticipantInterface $client,
        ParticipantInterface $operator
    ) :void {
        $this->redis->command('HSET', [
            self::CLIENT_TO_OPERATOR_MAP_NAME,
            $client->identifier(),
            $operator->identifier()
        ]);

        $operatorClientsIds = $this->operatorClientsIds($operator->identifier());
        $operatorClientsIds[] = $client->identifier();
        $this->redis->command('HSET', [
            self::OPERATOR_CLIENTS_MAP_NAME,
            $operator->identifier(),
            \implode(self::MAP_DELIMITER, $operatorClientsIds)
        ]);
    }

    public function unassignClientFromOperator(
        ParticipantInterface $client
    ) :void {
        $clientExists = $this->redis->command('HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $client->identifier()]);
        if ($clientExists) {
            $operatorId = $this->redis->command('HGET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $client->identifier()]);
            $this->redis->command('HDEL', [self::CLIENT_TO_OPERATOR_MAP_NAME, $client->identifier()]);
            $operatorClientsIds = $this->operatorClientsIds($operatorId);
            $clientIndex = \array_search($client->identifier(), $operatorClientsIds, true);
            if (false !== $clientIndex) {
                unset($operatorClientsIds[$clientIndex]);
            }
            if (\count($operatorClientsIds) > 0) {
                $this->setClientsIdsToOperator($operatorId, $operatorClientsIds);
            } else {
                $this->clearOperatorClients($operatorId);
            }
        }
    }

    public function operatorForClient(ParticipantInterface $client): ParticipantInterface
    {
        $clientExists = $this->clientInMap($client);
        if (!$clientExists) {
            throw new \LogicException('Operator for client' . $client->identifier() . ' is not assigned');
        }

        return new Participant(
            $this->redis->command('HGET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $client->identifier()])
        );
    }

    public function clientInMap(ParticipantInterface $client): bool
    {
        return $this->redis->command('HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $client->identifier()]) === 1;
    }

    public function operatorClients(ParticipantInterface $operator): array
    {
        $result = [];

        $clientIds = $this->operatorClientsIds($operator->identifier());
        foreach ($clientIds as $clientId) {
            $result[] = new Participant($clientId);
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function operatorClientsIds(string $operatorId): array
    {
        $result = [];
        if ($this->redis->command('HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId])) {
            $clientIds = $this->redis->command('HGET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId]);
            $clientIds = \explode(self::MAP_DELIMITER, $clientIds);
            if ($clientIds) {
               return $clientIds;
            }
        }

        return $result;
    }

    private function setClientsIdsToOperator(string $operatorId, array $clientIds): void
    {
        $this->redis->command('HSET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId, \implode(self::MAP_DELIMITER, $clientIds)]);
    }

    private function clearOperatorClients(string $operatorId): void
    {
        if ($this->redis->command('HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId])) {
            $this->redis->command('HDEL', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId]);
        }
    }
}
