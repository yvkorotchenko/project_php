<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\WorkerEventsHandlers\Interfaces\OnCloseHandlerInterface;
use App\Chat\WorkerEventsHandlers\Interfaces\OnMessageHandlerInterface;
use App\Chat\WorkerEventsHandlers\OnWebsocketConnect;
use GatewayWorker\BusinessWorker as GWBussinessWorker;

class BusinessWorker extends GWBussinessWorker
{
    private OnMessageHandlerInterface $onMessageHandler;
    private OnCloseHandlerInterface $onCloseHandler;

    /**
     * @override
     */
    public function __construct(
        OnMessageHandlerInterface $onMessageHandler,
        OnCloseHandlerInterface $onCloseHandler,
        OnWebsocketConnect $onWebsocketConnect,
        $socket_name = '',
        $context_option = []
    ) {
        parent::__construct($socket_name, $context_option);

        $this->onMessageHandler = $onMessageHandler;
        $this->onCloseHandler = $onCloseHandler;
        $this->_eventOnWebSocketConnect = $onWebsocketConnect;
    }

    /**
     * @override
     */
    protected function onWorkerStart()
    {
        parent::onWorkerStart();
        $this->_eventOnMessage = $this->onMessageHandler;
        $this->_eventOnClose = $this->onCloseHandler;
    }
}
