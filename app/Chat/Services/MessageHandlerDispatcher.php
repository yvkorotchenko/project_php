<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Services\MessageHandlers\AssignClientToOperatorMessageHandler;
use App\Chat\Services\MessageHandlers\ClientConnectMessageHandler;
use App\Chat\Services\MessageHandlers\ClientMessageHandler;
use App\Chat\Services\MessageHandlers\MessageHandlerInterface;
use App\Chat\Services\MessageHandlers\OperatorCloseChatMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorConnectMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorDisconnectedMessageHandler;
use App\Chat\Services\MessageHandlers\OperatorMessageHandler;
use App\Chat\Services\MessageHandlers\PongMessageHandler;

class MessageHandlerDispatcher
{
    private ClientConnectMessageHandler $clientConnectMessageHandler;
    private OperatorConnectMessageHandler $operatorConnectMessageHandler;
    private ClientMessageHandler $clientMessageHandler;
    private OperatorMessageHandler $operatorMessageHandler;
    private AssignClientToOperatorMessageHandler $assignClientToOperatorMessageHandler;
    private OperatorDisconnectedMessageHandler $operatorDisconnectedMessageHandler;
    private OperatorCloseChatMessageHandler $operatorCloseChatMessageHandler;
    private PongMessageHandler $pongMessageHandler;

    public function __construct(
        ClientConnectMessageHandler $clientConnectMessageHandler,
        OperatorConnectMessageHandler $operatorConnectMessageHandler,
        ClientMessageHandler $clientMessageHandler,
        OperatorMessageHandler $operatorMessageHandler,
        AssignClientToOperatorMessageHandler $assignClientToOperatorMessageHandler,
        OperatorDisconnectedMessageHandler $operatorDisconnectedMessageHandler,
        OperatorCloseChatMessageHandler $operatorCloseChatMessageHandler,
        PongMessageHandler $pongMessageHandler
    ) {
        $this->clientConnectMessageHandler = $clientConnectMessageHandler;
        $this->operatorConnectMessageHandler = $operatorConnectMessageHandler;
        $this->clientMessageHandler = $clientMessageHandler;
        $this->operatorMessageHandler = $operatorMessageHandler;
        $this->assignClientToOperatorMessageHandler = $assignClientToOperatorMessageHandler;
        $this->operatorDisconnectedMessageHandler = $operatorDisconnectedMessageHandler;
        $this->operatorCloseChatMessageHandler = $operatorCloseChatMessageHandler;
        $this->pongMessageHandler = $pongMessageHandler;
    }

    public function hander(IncomingMessage $incomingMessage): MessageHandlerInterface
    {
        switch ($incomingMessage->type()) {
            case MessageTypeEnum::MESSAGE_TYPE_CLIENT_CONNECT:            return $this->clientConnectMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_OPERATOR_CONNECT:          return $this->operatorConnectMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_CLIENT_MESSAGE:            return $this->clientMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_OPERATOR_MESSAGE:          return $this->operatorMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_ASSIGN_CLIENT_TO_OPERATOR: return $this->assignClientToOperatorMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_OPERATOR_DISCONECTED:      return $this->operatorDisconnectedMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_OPERATOR_CLOSE_CHAT:       return $this->operatorCloseChatMessageHandler;
            case MessageTypeEnum::MESSAGE_TYPE_PONG:                      return $this->pongMessageHandler;
            default:                                                      throw new \LogicException('Message handler not found for type: ' . $incomingMessage->type());
        }
    }
}
