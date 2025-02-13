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
use App\Chat\Services\ClientLanguageRepository;
use App\Chat\Services\Gateway;
use App\Chat\Services\ParticipantToModelMap;

class ClientInQueueCloseConnectionHandler implements CloseConnectionHandlerInterface
{
    private const OPERATOR_GROUP_NAME = 'operators';

    private ClientsQueue $clientsQueue;
    private ClientChatInfoRepository $clientChatInfoRepository;
    private ParticipantToModelMap $participantToModelMap;
    private Gateway $gateway;
    private ClientLanguageRepository $clientLanguageRepository;

    public function __construct(
        ClientsQueue $clientsQueue,
        ClientChatInfoRepository $clientChatInfoRepository,
        ParticipantToModelMap $participantToModelMap,
        Gateway $gateway,
        ClientLanguageRepository $clientLanguageRepository
    ) {
        $this->clientsQueue = $clientsQueue;
        $this->clientChatInfoRepository = $clientChatInfoRepository;
        $this->participantToModelMap = $participantToModelMap;
        $this->gateway = $gateway;
        $this->clientLanguageRepository = $clientLanguageRepository;
    }

    public function handle(ParticipantInterface $participant): MessageCollection
    {
        $this->clientsQueue->remove($participant);
        $this->clientChatInfoRepository->remove($participant);
        $this->participantToModelMap->removeClient($participant);
        $this->participantToModelMap->removeAnonymous($participant);
        $this->clientLanguageRepository->del($participant);

        return new MessageCollection([
            new Message(
                new MessageBodyWithClientId(
                    $participant->identifier(),
                    new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_DISCONECTED, '')
                ),
                new Participant(self::OPERATOR_GROUP_NAME, true),
                $this->gateway
            )
        ]);
    }
}
