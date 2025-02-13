<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Models\QuestionType;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\ClientLanguageRepository;
use App\Chat\Services\Gateway;

class ClientConnectMessageHandler implements MessageHandlerInterface
{
    private const OPERATOR_GROUP_NAME = 'operators';

    private ClientsQueue $clientsQueue;
    private ClientChatInfoRepository $chatInfoRepository;
    private Gateway $gateway;
    private ClientLanguageRepository $clientLanguageRepository;

    public function __construct(
        ClientsQueue $clientsQueue,
        ClientChatInfoRepository $chatInfoRepository,
        Gateway $gateway,
        ClientLanguageRepository $clientLanguageRepository
    ) {
        $this->clientsQueue = $clientsQueue;
        $this->chatInfoRepository = $chatInfoRepository;
        $this->gateway = $gateway;
        $this->clientLanguageRepository = $clientLanguageRepository;
    }

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $client = $incomingMessage->sender();
        if (!\array_key_exists('content', $incomingMessage->asArray())) {
            $this->gateway->closeParticipant($client);
            throw new \LogicException('Question type is not defined during clinet connect');
        }

        if (!\is_numeric($incomingMessage->asArray()['content'])) {
            $this->gateway->closeParticipant($client);
            throw new \Exception('Invalid question type id format');
        }

        $questuionTypeId = (int) $incomingMessage->asArray()['content'];
        $questionType = QuestionType::whereId($questuionTypeId)->first();

        if (null === $questionType) {
            $this->gateway->closeParticipant($client);
            throw new \Exception('Invalid question type');
        }
        $this->clientsQueue->add($client);
        $this->chatInfoRepository->updateStatus($client, 'Waiting');
        $this->chatInfoRepository->updateQuestionType($client, $questionType);

        return new MessageCollection([
            new Message(
                new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_CONNECT, $this->getWelcomeMessage($client)),
                $client,
                $this->gateway
            ),

            new Message(
                new MessageBodyWithClientId(
                    $client->identifier(),
                    new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_CONNECT, '')
                ),
                new Participant(self::OPERATOR_GROUP_NAME, true),
                $this->gateway
            ),
        ]);
    }

    private function getWelcomeMessage(ParticipantInterface $participant): string
    {
        $langMap = [
            'en' => 'Thank you for enquiring us about the problem you are experiencing! Our operator will get back to you as soon as possible.',
            'zn' => '感谢您向我们咨询您遇到的问题！ 我们的接线员会尽快回复您',
        ];
        $language = $this->clientLanguageRepository->getForClient($participant);
        if (!\array_key_exists($language, $langMap)) {
            $language = 'zn';
        }

        return $langMap[$language];
    }
}
