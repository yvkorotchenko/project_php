<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Repositories\ChatHistoryRepository;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\Gateway;
use App\Chat\Services\ParticipantToModelMap;
use Illuminate\Support\Facades\Validator;

class OperatorCloseChatMessageHandler implements MessageHandlerInterface
{
    private Gateway $gateway;
    private ChatHistoryRepository $chatHistoryRepository;
    private ClientsOperatorsMap $clientsOperatorsMap;
    private ClientChatInfoRepository $clientChatInfoRepository;
    private ParticipantToModelMap $participantToModelMap;

    public function __construct(
        Gateway $gateway,
        ChatHistoryRepository $chatHistoryRepository,
        ClientsOperatorsMap $clientsOperatorsMap,
        ClientChatInfoRepository $clientChatInfoRepository,
        ParticipantToModelMap $participantToModelMap
    )
    {
        $this->gateway = $gateway;
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->clientsOperatorsMap = $clientsOperatorsMap;
        $this->clientChatInfoRepository = $clientChatInfoRepository;
        $this->participantToModelMap = $participantToModelMap;
    }

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $messageData = $incomingMessage->asArray();

        Validator::validate($messageData, [
            'clientId' => 'required|string',
        ]);

        $clientParticipant = new Participant($messageData['clientId']);
        $messages = $this->onClientDisconnect($clientParticipant);
        $this->gateway->closeParticipant($clientParticipant);

        return $messages;
    }

    private function onClientDisconnect(ParticipantInterface $participant)
    {
        $chatHistory = $this->chatHistoryRepository->notCompletedChatHistoryForClientParticipant($participant);
        if (null !== $chatHistory) {
            $this->chatHistoryRepository->addCompleted($chatHistory, $participant);
        }

        $operatorAssignedToClient = $this->clientsOperatorsMap->operatorForClient($participant);
        $this->clientsOperatorsMap->unassignClientFromOperator($participant);
        $this->clientChatInfoRepository->remove($participant);
        $this->participantToModelMap->removeClient($participant);

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
