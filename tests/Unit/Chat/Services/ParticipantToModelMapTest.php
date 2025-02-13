<?php

declare(strict_types=1);

namespace Tests\Unit\Chat\Services;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Models\CustomerServiceOperator;
use App\Chat\Repositories\CustomerServiceOperatorRepository;
use App\MtSports\Models\Client;
use Illuminate\Contracts\Redis\Connection;
use App\Chat\Services\ParticipantToModelMap;
use App\MtSports\Repositories\ClientsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ParticipantToModelMapTest extends TestCase
{
    private const OPERATOR_IDENT_HASH = 'operators_idents';
    private const CLIENT_IDENT_HASH = 'clients_idents';

    private ParticipantToModelMap $participantToModelMap;
    private MockObject $clientsRepository;
    private MockObject $operatorRepository;
    private MockObject $redisConnection;

    public function setUp(): void
    {
        $this->clientsRepository = $this->createMock(ClientsRepository::class);
        $this->operatorRepository = $this->createMock(CustomerServiceOperatorRepository::class);
        $this->redisConnection = $this->createMock(Connection::class);
        $this->participantToModelMap = new ParticipantToModelMap(
            $this->clientsRepository,
            $this->operatorRepository,
            $this->redisConnection
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->participantToModelMap,
            $this->clientsRepository,
            $this->operatorRepository,
            $this->redisConnection
        );
    }

    public function clientDataProvider(): \Traversable
    {
        $clientId = 1234;
        $clientParticipantId = 'clientId';

        $client = new Client();
        $client->id = $clientId;

        yield 'default' => [
            'input' => new Participant($clientParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                    ['HGET', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [1, $clientId],
            ],
            'clientsRepositoryData' => [
                'count' => 1,
                'input' => $clientId,
                'output' => $client,
            ],
            'expected' => $client,
        ];

        yield 'client not in map' => [
            'input' => new Participant($clientParticipantId),
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [0],
            ],
            'clientsRepositoryData' => [
                'count' => 0,
                'input' => null,
                'output' => null,
            ],
            'expected' => null,
        ];

        yield 'client not exists in repository' => [
            'input' => new Participant($clientParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                    ['HGET', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [1, $clientId],
            ],
            'clientsRepositoryData' => [
                'count' => 1,
                'input' => $clientId,
                'output' => null,
            ],
            'expected' => null,
        ];
    }

    /**
     * @dataProvider clientDataProvider
     */
    public function testClient(
        ParticipantInterface $input,
        array $redisData,
        array $clientsRepositoryData,
        ?Client $expected
    ): void {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->clientsRepository
            ->expects($this->exactly($clientsRepositoryData['count']))
            ->method('clientById')
            ->with($clientsRepositoryData['input'])
            ->willReturn($clientsRepositoryData['output']);

        $this->assertEquals(
            $this->participantToModelMap->client($input),
            $expected
        );
    }

    public function operatorDataProvider(): \Traversable
    {
        $operatorParticipantId = 'oeratorId';
        $operatorId = 1234;
        $operator = new CustomerServiceOperator();
        $operator->id = $operatorId;

        yield 'default' => [
            'input' => new Participant($operatorParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                    ['HGET', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [1, $operatorId],
            ],
            'operatorRepositoryData' => [
                'count' => 1,
                'input' => $operatorId,
                'output' => $operator,
            ],
            'expected' => $operator,
        ];

        yield 'operator not in map' => [
            'input' => new Participant($operatorParticipantId),
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [0],
            ],
            'clientsRepositoryData' => [
                'count' => 0,
                'input' => null,
                'output' => null,
            ],
            'expected' => null,
        ];

        yield 'operator not exists in repository' => [
            'input' => new Participant($operatorParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                    ['HGET', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [1, $operatorId],
            ],
            'clientsRepositoryData' => [
                'count' => 1,
                'input' => $operatorId,
                'output' => null,
            ],
            'expected' => null,
        ];
    }

    /**
     * @dataProvider operatorDataProvider
     */
    public function testOperator(
        ParticipantInterface $input,
        array $redisData,
        array $operatorRepositoryData,
        ?CustomerServiceOperator $expected
    ): void {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->operatorRepository
            ->expects($this->exactly($operatorRepositoryData['count']))
            ->method('operatorById')
            ->with($operatorRepositoryData['input'])
            ->willReturn($operatorRepositoryData['output']);

        $this->assertEquals(
            $this->participantToModelMap->operator($input),
            $expected
        );
    }

    public function addClientDataProvider(): \Traversable
    {
        $clientParticipantId = 'clientID';
        $clientParticipant = new Participant($clientParticipantId);
        $client = new Client();
        $clientId = 1234;
        $client->id = $clientId;

        yield 'default' => [
            'input' => [$clientParticipant, $client],
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                    ['HSET', [self::CLIENT_IDENT_HASH, $clientParticipantId, $clientId]],
                ],
                'output' => [0, null],
            ],
        ];

        yield 'client already in map' => [
            'input' => [$clientParticipant, $client],
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [1],
            ],
        ];
    }

    /**
     * @dataProvider addClientDataProvider
     */
    public function testAddClient(array $input, array $redisData): void
    {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->participantToModelMap->addClient(...$input);
    }

    public function removeClientDataProvider(): \Traversable
    {
        $clientParticipantId = 'clientId';

        yield 'default' => [
            'input' => new Participant($clientParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                    ['HDEL', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [1, null],
            ],
        ];

        yield 'remove non-existen client' => [
            'input' => new Participant($clientParticipantId),
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::CLIENT_IDENT_HASH, $clientParticipantId]],
                ],
                'output' => [0],
            ],
        ];
    }

    /**
     * @dataProvider removeClientDataProvider
     */
    public function testRemoveClient(ParticipantInterface $input, array $redisData): void
    {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->participantToModelMap->removeClient($input);
    }

    public function addOperatorDataProvider(): \Traversable
    {
        $operatorParticipantId = 'operatorId';
        $operatorId = 1234;

        $operatorParticpant = new Participant($operatorParticipantId);
        $operator = new CustomerServiceOperator();
        $operator->id = $operatorId;

        yield 'default' => [
            'input' => [$operatorParticpant, $operator],
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                    ['HSET', [self::OPERATOR_IDENT_HASH, $operatorParticipantId, $operatorId]],
                ],
                'output' => [0, null],
            ],
        ];

        yield 'operator already in map' => [
            'input' => [$operatorParticpant, $operator],
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [1],
            ],
        ];
    }

    /**
     * @dataProvider addOperatorDataProvider
     */
    public function testAddOperator(array $input, array $redisData)
    {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->participantToModelMap->addOperator(...$input);
    }

    public function removeOperatorDataProvider(): \Traversable
    {
        $operatorParticipantId = 'operatorId';

        yield 'default' => [
            'input' => new Participant($operatorParticipantId),
            'redisData' => [
                'count' => 2,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                    ['HDEL', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [1, null],
            ],
        ];

        yield 'remove non-existen client' => [
            'input' => new Participant($operatorParticipantId),
            'redisData' => [
                'count' => 1,
                'input' => [
                    ['HEXISTS', [self::OPERATOR_IDENT_HASH, $operatorParticipantId]],
                ],
                'output' => [0],
            ],
        ];

    }

    /**
     * @dataProvider removeOperatorDataProvider
     */
    public function testRemoveOperator(ParticipantInterface $input, array $redisData): void
    {
        $this->redisConnection
            ->expects($this->exactly($redisData['count']))
            ->method('command')
            ->withConsecutive(...$redisData['input'])
            ->willReturn(...$redisData['output']);

        $this->participantToModelMap->removeOperator($input);

    }
}
