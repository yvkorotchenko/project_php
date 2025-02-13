<?php

declare(strict_types=1);

namespace App\Chat\Services\CloseConnectionHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\Gateway;
use App\Chat\Services\ParticipantToModelMap;

class OperatorCloseConnectionHandler implements CloseConnectionHandlerInterface
{
    private const OPERATOR_GROUP_NAME = 'operators';

    private ClientsOperatorsMap $clientsOperatorsMap;
    private ParticipantToModelMap $participantToModelMap;
    private ClientChatInfoRepository $clientChatInfoRepository;
    private ClientsQueue $clientsQueue;
    private Gateway $gateway;

    public function __construct(
        ClientsOperatorsMap $clientsOperatorsMap,
        ParticipantToModelMap $participantToModelMap,
        ClientChatInfoRepository $clientChatInfoRepository,
        ClientsQueue $clientsQueue,
        Gateway $gateway
    ) {
        $this->clientsOperatorsMap = $clientsOperatorsMap;
        $this->participantToModelMap = $participantToModelMap;
        $this->clientChatInfoRepository = $clientChatInfoRepository;
        $this->clientsQueue = $clientsQueue;
        $this->gateway = $gateway;
    }

    public function handle(ParticipantInterface $participant): MessageCollection
    {
        $messages = new MessageCollection();

        /** @var ParticipantInterface[] $clients */
        $clients = $this->clientsOperatorsMap->operatorClients($participant);
        $operatorsGroup = new Participant(self::OPERATOR_GROUP_NAME, true);
        $this->participantToModelMap->removeOperator($participant);
        foreach ($clients as $client) {
            $this->clientChatInfoRepository->updateStatus($client, 'Waiting');

            $messages[] = new Message(
                new MessageBody(MessageTypeEnum::MESSAGE_TYPE_OPERATOR_DISCONECTED, ''),
                $client,
                $this->gateway
            );
            $messages[] = new Message(
                new MessageBodyWithClientId(
                    $client->identifier(),
                    new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_CONNECT, '')
                ),
                $operatorsGroup,
                $this->gateway
            );
            $this->clientsQueue->add($client);
            $this->clientsOperatorsMap->unassignClientFromOperator($client);
        }

        return $messages;
    }
}
