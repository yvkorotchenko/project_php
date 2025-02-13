<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Factories\ChatHistoryMessageFactory;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithDateCreated;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithMedia;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Repositories\ChatHistoryRepository;
use App\Chat\Services\Gateway;
use App\Chat\Services\StorageService;
use Illuminate\Support\Facades\Validator;

class OperatorMessageHandler implements MessageHandlerInterface
{
    private ChatHistoryRepository $chatHistoryRepository;
    private ChatHistoryMessageFactory $chatHistoryMessageFactory;
    private Gateway $gateway;
    private StorageService $storageService;

    public function __construct(
        ChatHistoryRepository $chatHistoryRepository,
        ChatHistoryMessageFactory $chatHistoryMessageFactory,
        Gateway $gateway,
        StorageService $storageService
    ) {

        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->chatHistoryMessageFactory = $chatHistoryMessageFactory;
        $this->gateway = $gateway;
        $this->storageService = $storageService;
    }

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $messageData = $incomingMessage->asArray();

        Validator::validate($messageData, [
            'clientId' => 'required|string',
            'content' => 'string',
            'media' => 'array|nullable'
        ]);

        $operator = $incomingMessage->sender();
        $client = new Participant($messageData['clientId']);

        $messageBody = new MessageBody(MessageTypeEnum::MESSAGE_TYPE_OPERATOR_MESSAGE, $messageData['content']);
        $media = null;
        if (\array_key_exists('media', $messageData) && null !== $messageData['media']) {
            $media = $this->normalizeMedia($messageData['media']);
            $messageBody = new MessageBodyWithMedia($media, $messageBody);
        }

        $chatHistory = $this->chatHistoryRepository->notCompletedChatHistoryForClientParticipant($client);
        if (null !== $chatHistory) {
            $this->chatHistoryMessageFactory->createForChatHistory(
                'operator',
                $chatHistory,
                $messageBody->asArray()['content'],
                $media === null ? null : $media['url']
            );
        }

        return new MessageCollection([
            new Message(
                new MessageBodyWithClientId(
                    $client->identifier(),
                    $messageBody
                ),
                $operator,
                $this->gateway
            ),

            new Message(
                new MessageBodyWithDateCreated(new \DateTimeImmutable(), $messageBody),
                $client,
                $this->gateway
            ),
        ]);
    }

    private function normalizeMedia(array $media): array
    {
        Validator::validate($media, [
            'url' => 'required|string|min:10',
            'mime' => 'required|string|min:3',
        ]);

        $fliePath = $this->storageService->getLocalFilePathByUrl($media['url']);
        if ('' === $fliePath) {
            dump($media['url'], $this->storageService->getLocalFilePathByUrl($media['url']));
            throw new \Exception('File url is invalid. File does not exists');
        }
        $media['mime'] = $this->storageService->getFileExtension($fliePath);

        return $media;
    }
}
