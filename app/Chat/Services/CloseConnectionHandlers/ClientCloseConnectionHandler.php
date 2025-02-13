<?php

declare(strict_types=1);

namespace App\Chat\Services\CloseConnectionHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Repositories\ChatHistoryRepository;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\ClientLanguageRepository;
use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\Gateway;
use App\Chat\Services\ParticipantToModelMap;

class ClientCloseConnectionHandler implements CloseConnectionHandlerInterface
{
    private ChatHistoryRepository $chatHistoryRepository;
    private ClientsOperatorsMap $clientsOperatorsMap;
    private ClientChatInfoRepository $clientChatInfoRepository;
    private ParticipantToModelMap $participantToModelMap;
    private Gateway $gateway;
    private ClientLanguageRepository $clientLanguageRepository;

    public function __construct(
        ChatHistoryRepository $chatHistoryRepository,
        ClientsOperatorsMap $clientsOperatorsMap,
        ClientChatInfoRepository $clientChatInfoRepository,
        ParticipantToModelMap $participantToModelMap,
        Gateway $gateway,
        ClientLanguageRepository $clientLanguageRepository
    )
    {
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->clientsOperatorsMap = $clientsOperatorsMap;
        $this->clientChatInfoRepository = $clientChatInfoRepository;
        $this->participantToModelMap = $participantToModelMap;
        $this->gateway = $gateway;
        $this->clientLanguageRepository = $clientLanguageRepository;
    }

    public function handle(ParticipantInterface $participant): MessageCollection
    {
        $chatHistory = $this->chatHistoryRepository->notCompletedChatHistoryForClientParticipant($participant);
        if (null !== $chatHistory) {
            $this->chatHistoryRepository->addCompleted($chatHistory, $participant);
        }

        $operatorAssignedToClient = $this->clientsOperatorsMap->operatorForClient($participant);
        $this->clientsOperatorsMap->unassignClientFromOperator($participant);
        $this->clientChatInfoRepository->remove($participant);
        $this->participantToModelMap->removeClient($participant);
        $this->clientLanguageRepository->del($participant);

        return new MessageCollection([
            new Message(
                new MessageBodyWithClientId(
                    $participant->identifier(),
                    new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_DISCONECTED, '')
                ),
                $operatorAssignedToClient,
                $this->gateway
            )
        ]);
    }
}
