<?php

namespace Tests\Unit\Chat\Services;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Services\ClientsOperatorsMap;
use Illuminate\Contracts\Redis\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClientsOperatorsMapTest extends TestCase
{
    private const CLIENT_TO_OPERATOR_MAP_NAME = 'client_opeartor';
    private const OPERATOR_CLIENTS_MAP_NAME = 'operator_clients';
    private const MAP_DELIMITER = ',';

    private ClientsOperatorsMap $clientsOperatorsMap;
    private MockObject $redis;

    public function setUp(): void
    {
        $this->redis = $this->createMock(Connection::class);
        $this->clientsOperatorsMap = new ClientsOperatorsMap($this->redis);
    }

    public function tearDown(): void
    {
        unset($this->redis, $this->clientsOperatorsMap);
    }

    public function assignOperatorToClientDataProvider(): \Traversable
    {
        $clientParticipantId = 'clientId';
        $clientParticipant = new Participant($clientParticipantId);

        $operatorParticipantId = 'operatorId';
        $operatorParticipant = new Participant($operatorParticipantId);
        $operatorClientsIds = 'clientId1,clientId2,clientId3';

        yield 'default' => [
            'input' => [$clientParticipant, $operatorParticipant],
            'redisData' => [
                'count' => 4,
                'input' => [
                    ['HSET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId, $operatorParticipantId]],
                    ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HGET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HSET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId, $operatorClientsIds . self::MAP_DELIMITER . $clientParticipantId]],
                ],
                'output' => [null, 1, $operatorClientsIds, null],
            ]
        ];

        yield 'assign operator without clients' => [
            'input' => [$clientParticipant, $operatorParticipant],
            'redisData' => [
                'count' => 3,
                'input' => [
                    ['HSET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId, $operatorParticipantId]],
                    ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HSET', [
                        self::OPERATOR_CLIENTS_MAP_NAME,
                        $operatorParticipantId,
                        \implode(self::MAP_DELIMITER, [$clientParticipantId])
                    ]],
                ],
                'output' => [null, 0, null],
            ]
        ];
    }

    /**
     * @dataProvider assignOperatorToClientDataProvider
     */
    public function testAssignOperatorToClient(array $input, array $redisData): void
    {
        $this->redis
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->clientsOperatorsMap->assignOperatorToClient(...$input);
    }

    public function unassignClientFromOperatorDataProvider(): \Traversable
    {
        $clientParticipantId = 'clientId';
        $clientParticipant = new Participant($clientParticipantId);

        $operatorParticipantId = 'operatorId';
        $operatorClientsIds = 'clientId1,clientId2,clientId3';

        yield 'default' => [
            'input' => $clientParticipant,
            'redisData' => [
                'count' => 6,
                'input' => [
                    ['HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HGET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HDEL', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HGET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HSET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId, $operatorClientsIds]],
                ],
                'output' => [
                    1,
                    $operatorParticipantId,
                    null,
                    1,
                    'clientId1,clientId2,clientId3' . self::MAP_DELIMITER . $clientParticipantId,
                    null
                ],
            ]
        ];

        yield 'operator with only one client' => [
            'input' => $clientParticipant,
            'redisData' => [
                'count' => 7,
                'input' => [
                    ['HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HGET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HDEL', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientParticipantId]],
                    ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HGET', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]],
                    ['HDEL', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorParticipantId]]
                ],
                'output' => [
                    1,
                    $operatorParticipantId,
                    null,
                    1,
                    $clientParticipantId,
                    1,
                    null,
                ],
            ]
        ];
    }

    /**
     * @dataProvider unassignClientFromOperatorDataProvider
     */
    public function testUnassignClientFromOperator(ParticipantInterface $input, array $redisData): void
    {
        $this->redis
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->clientsOperatorsMap->unassignClientFromOperator($input);

    }

    public function operatorForClientDataProvider(): \Traversable
    {
        $clientId = 'clientId';
        $operatorId = 'operatorId';

        yield 'default' => [
            'input' => new Participant($clientId),
            'redisData' => [
                'input' => [
                    ['HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientId]],
                    ['HGET', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientId]]
                ],
                'output' => [
                    1,
                    $operatorId
                ],
                'count' => 2,
            ],
            'expected' => new Participant($operatorId),
            'expectedExceptionClass' => '',
        ];

        yield 'client not found' => [
            'input' => new Participant($clientId),
            'redisData' => [
                'input' => [
                    ['HEXISTS', [self::CLIENT_TO_OPERATOR_MAP_NAME, $clientId]],
                ],
                'output' => [
                    0,
                ],
                'count' => 1,
            ],
            'expected' => null,
            'expectedExceptionClass' => \LogicException::class,
        ];
    }

    /**
     * @dataProvider operatorForClientDataProvider
     */
    public function testOperatorForClient(
        ParticipantInterface $input,
        array $redisData,
        ?ParticipantInterface $expected,
        string $expectedExceptionClass
    ): void {
        if ('' !== $expectedExceptionClass) {
            $this->expectException($expectedExceptionClass);
        }

        $this->redis
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->assertEquals($expected, $this->clientsOperatorsMap->operatorForClient($input));
    }

    public function testOperatorClients(): void
    {
        $operatorClietnsIds = ['clientId_1', 'clientId_2', 'clientId_2'];
        $operatorId = 'operatorId';

        $this->redis
            ->expects($this->exactly(2))
            ->method('command')
            ->withConsecutive(
                ['HEXISTS', [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId]],
                ['HGET',    [self::OPERATOR_CLIENTS_MAP_NAME, $operatorId]]
            )
            ->willReturn(1, \implode(self::MAP_DELIMITER, $operatorClietnsIds));

        $expected = \array_map(fn($clientId) => new Participant($clientId), $operatorClietnsIds);

        $this->assertEquals($expected, $this->clientsOperatorsMap->operatorClients(new Participant($operatorId)));
    }
}
