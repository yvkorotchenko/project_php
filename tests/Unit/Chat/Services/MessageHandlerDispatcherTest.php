<?php

declare(strict_types=1);

namespace Tests\Unit\Chat\Services;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Services\MessageHandlers\MessageHandlerInterface;
use App\Chat\Services\MessageHandlerDispatcher;
use App\Chat\Services\MessageHandlers\PongMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorCloseChatMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorDisconnectedMessageHandler;
use App\Chat\Services\MessageHandlers\AssignClientToOperatorMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorMessageHandler;
use App\Chat\Services\MessageHandlers\ClientMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorConnectMessageHandler;
use App\Chat\Services\MessageHandlers\ClientConnectMessageHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessageHandlerDispatcherTest extends TestCase
{
    private MessageHandlerDispatcher $messageDispatcher;
    private MockObject $clientConnectMessageHandler;
    private MockObject $operatorConnectMessageHandler;
    private MockObject $clientMessageHandler;
    private MockObject $operatorMessageHandler;
    private MockObject $assignClientToOperatorMessageHandler;
    private MockObject $operatorDisconnectedMessageHandler;
    private MockObject $operatorCloseChatMessageHandler;
    private MockObject $pongMessageHandler;

    public function setUp(): void
    {
        $this->clientConnectMessageHandler = $this->createMock(ClientConnectMessageHandler::class);
        $this->operatorConnectMessageHandler = $this->createMock(OperatorConnectMessageHandler::class);
        $this->clientMessageHandler = $this->createMock(ClientMessageHandler::class);
        $this->operatorMessageHandler = $this->createMock(OperatorMessageHandler::class);
        $this->assignClientToOperatorMessageHandler = $this->createMock(AssignClientToOperatorMessageHandler::class);
        $this->operatorDisconnectedMessageHandler = $this->createMock(OperatorDisconnectedMessageHandler::class);
        $this->operatorCloseChatMessageHandler = $this->createMock(OperatorCloseChatMessageHandler::class);
        $this->pongMessageHandler = $this->createMock(PongMessageHandler::class);

        $this->messageDispatcher = new MessageHandlerDispatcher(
            $this->clientConnectMessageHandler,
            $this->operatorConnectMessageHandler,
            $this->clientMessageHandler,
            $this->operatorMessageHandler,
            $this->assignClientToOperatorMessageHandler,
            $this->operatorDisconnectedMessageHandler,
            $this->operatorCloseChatMessageHandler,
            $this->pongMessageHandler
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->clientConnectMessageHandler,
            $this->operatorConnectMessageHandler,
            $this->clientMessageHandler,
            $this->assignClientToOperatorMessageHandler,
            $this->operatorDisconnectedMessageHandler,
            $this->operatorCloseChatMessageHandler,
            $this->pongMessageHandler,
            $this->messageDispatcher
        );
    }

    public function handerDataProvider(): \Traversable
    {
        $senderIdent = 'senderId';

        yield 'client connect' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_CLIENT_CONNECT,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expectedClass' => ClientConnectMessageHandler::class,
        ];

        yield 'operator connect' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_OPERATOR_CONNECT,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => OperatorConnectMessageHandler::class,
        ];

        yield 'client message' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_CLIENT_MESSAGE,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => ClientMessageHandler::class,
        ];

        yield 'operator message' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_OPERATOR_MESSAGE,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => OperatorMessageHandler::class,
        ];

        yield 'assign client to operator' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_ASSIGN_CLIENT_TO_OPERATOR,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => AssignClientToOperatorMessageHandler::class,
        ];

        yield 'operator disconnected' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_OPERATOR_DISCONECTED,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => OperatorDisconnectedMessageHandler::class,
        ];

        yield 'operator close chat' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_OPERATOR_CLOSE_CHAT,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => OperatorCloseChatMessageHandler::class,
        ];

        yield 'pong' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => MessageTypeEnum::MESSAGE_TYPE_PONG,
                'content' => '',
                'media' => null,
            ]),
            'exception' => null,
            'expected' => PongMessageHandler::class,
        ];

        $undefinedType = 'UNDEFINED TYPE';

        yield 'undefined type' => [
            'input' => new IncomingMessage($senderIdent, [
                'type' => $undefinedType,
                'content' => '',
                'media' => null,
            ]),
            'exception' => new \LogicException('Message handler not found for type: ' . $undefinedType),
            'expected' => null,
        ];
    }

    /**
     * @dataProvider handerDataProvider
     */
    public function testHander(
        IncomingMessage $input,
        ?\Throwable $exception,
        ?string $expectedClass
    ): void {
        if (null !== $exception) {
            $this->expectException(\get_class($exception));
            $this->expectExceptionMessage($exception->getMessage());
        }

        $this->assertInstanceOf(
            $expectedClass,
            $this->messageDispatcher->hander($input)
        );
    }
}
