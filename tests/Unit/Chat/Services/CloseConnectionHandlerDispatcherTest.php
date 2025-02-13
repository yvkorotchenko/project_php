<?php

declare(strict_types=1);

namespace Tests\Unit\Chat\Services;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\CloseConnectionHandlerDispatcher;
use App\Chat\Services\CloseConnectionHandlers\ClientCloseConnectionHandler;
use App\Chat\Services\CloseConnectionHandlers\ClientInQueueCloseConnectionHandler;
use App\Chat\Services\CloseConnectionHandlers\CloseConnectionHandlerInterface;
use App\Chat\Services\CloseConnectionHandlers\OperatorCloseConnectionHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CloseConnectionHandlerDispatcherTest extends TestCase
{
    private CloseConnectionHandlerDispatcher $closeConnectionDispatcher;
    private MockObject $clientsOperatorsMap;
    private MockObject $clientsQueue;
    private MockObject $clientCloseConnectionHandler;
    private MockObject $clientInQueueCloseConnectionHandler;
    private MockObject $operatorCloseConnectionHandler;


    public function setUp(): void
    {
        $this->clientsOperatorsMap = $this->createMock(ClientsOperatorsMap::class);
        $this->clientsQueue = $this->createMock(ClientsQueue::class);
        $this->clientCloseConnectionHandler = $this->createMock(ClientCloseConnectionHandler::class);
        $this->clientInQueueCloseConnectionHandler = $this->createMock(ClientInQueueCloseConnectionHandler::class);
        $this->operatorCloseConnectionHandler = $this->createMock(OperatorCloseConnectionHandler::class);
        $this->closeConnectionDispatcher = new CloseConnectionHandlerDispatcher(
            $this->clientsOperatorsMap,
            $this->clientsQueue,
            $this->clientCloseConnectionHandler,
            $this->clientInQueueCloseConnectionHandler,
            $this->operatorCloseConnectionHandler
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->clientsOperatorsMap,
            $this->clientsQueue,
            $this->clientCloseConnectionHandler,
            $this->clientInQueueCloseConnectionHandler,
            $this->operatorCloseConnectionHandler,
            $this->closeConnectionDispatcher
        );
    }

    public function handlerDataProvider(): \Traversable
    {
        $participant = new Participant('participantId');

        yield 'client disconnected' => [
            'input' => $participant,
            'clientsOperatorsMapData' => [
                'input' => $participant,
                'output' => true,
            ],
            'clientsQueueData' => [
                'count' => 0,
                'input' => $participant,
                'output' => false,
            ],
            'expected' => ClientCloseConnectionHandler::class,
        ];

        yield 'client in queue disconnected' => [
            'input' => $participant,
            'clientsOperatorsMapData' => [
                'input' => $participant,
                'output' => false,
            ],
            'clientsQueueData' => [
                'count' => 1,
                'input' => $participant,
                'output' => true,
            ],
            'expected' => ClientInQueueCloseConnectionHandler::class,
        ];

        yield 'operator disconnected' => [
            'input' => $participant,
            'clientsOperatorsMapData' => [
                'input' => $participant,
                'output' => false,
            ],
            'clientsQueueData' => [
                'count' => 1,
                'input' => $participant,
                'output' => false,
            ],
            'expected' => OperatorCloseConnectionHandler::class,
        ];
    }

    /**
     * @dataProvider handlerDataProvider
     */
    public function testHandler(
        ParticipantInterface $input,
        array $clientsOperatorsMapData,
        array $clientsQueueData,
        string $expectedClass
    ): void {

        $this->clientsOperatorsMap
            ->expects($this->once())
            ->method('clientInMap')
            ->with($clientsOperatorsMapData['input'])
            ->willReturn($clientsOperatorsMapData['output']);

        $this->clientsQueue
            ->expects($this->exactly($clientsQueueData['count']))
            ->method('inQueue')
            ->with($clientsQueueData['input'])
            ->willReturn($clientsQueueData['output']);

        $this->assertInstanceOf($expectedClass, $this->closeConnectionDispatcher->handler($input));
    }
}
