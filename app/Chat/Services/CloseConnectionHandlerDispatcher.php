<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Services\CloseConnectionHandlers\ClientCloseConnectionHandler;
use App\Chat\Services\CloseConnectionHandlers\ClientInQueueCloseConnectionHandler;
use App\Chat\Services\CloseConnectionHandlers\CloseConnectionHandlerInterface;
use App\Chat\Services\CloseConnectionHandlers\OperatorCloseConnectionHandler;

class CloseConnectionHandlerDispatcher
{
    private ClientsOperatorsMap $clientsOperatorsMap;
    private ClientsQueue $clientsQueue;
    private ClientCloseConnectionHandler $clientCloseConnectionHandler;
    private ClientInQueueCloseConnectionHandler $clientInQueueCloseConnectionHandler;
    private OperatorCloseConnectionHandler $operatorCloseConnectionHandler;

    public function __construct(
        ClientsOperatorsMap $clientsOperatorsMap,
        ClientsQueue $clientsQueue,
        ClientCloseConnectionHandler $clientCloseConnectionHandler,
        ClientInQueueCloseConnectionHandler $clientInQueueCloseConnectionHandler,
        OperatorCloseConnectionHandler $operatorCloseConnectionHandler
    ) {
        $this->clientsOperatorsMap = $clientsOperatorsMap;
        $this->clientsQueue = $clientsQueue;
        $this->clientCloseConnectionHandler = $clientCloseConnectionHandler;
        $this->clientInQueueCloseConnectionHandler = $clientInQueueCloseConnectionHandler;
        $this->operatorCloseConnectionHandler = $operatorCloseConnectionHandler;
    }

    public function handler(ParticipantInterface $participant): CloseConnectionHandlerInterface
    {
        if ($this->clientsOperatorsMap->clientInMap($participant)) {
            return $this->clientCloseConnectionHandler;
        } elseif ($this->clientsQueue->inQueue($participant)) {
            return $this->clientInQueueCloseConnectionHandler;
        } else {
            return $this->operatorCloseConnectionHandler;
        }
    }
}
