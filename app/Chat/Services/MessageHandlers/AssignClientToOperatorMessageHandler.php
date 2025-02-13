<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithClientId;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithOperatorId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Factories\ChatHistoryFactory;
use App\Chat\Models\ChatHistory;
use App\Chat\Models\CustomerService;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Repositories\ChatHistoryRepository;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\AnonymousChatUserService;
use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\Gateway;
use App\Chat\Services\ParticipantToModelMap;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

class AssignClientToOperatorMessageHandler implements MessageHandlerInterface
{
    private const OPERATOR_GROUP_NAME = 'operators';

    private ClientsOperatorsMap $clientsOperatorsMap;
    private ClientsQueue $clientsQueue;
    private ParticipantToModelMap $participantToModelMap;
    private ClientChatInfoRepository $clientChatInfoRepository;
    private ChatHistoryRepository $chatHistoryRepository;
    private Gateway $gateway;
    private ChatHistoryFactory $chatHistoryFactory;
    private ValidatorFactory $validatorFactory;
    private AnonymousChatUserService $anonymousChatUserService;

    public function __construct(
        ClientsOperatorsMap $clientsOperatorsMap,
        ClientsQueue $clientsQueue,
        ParticipantToModelMap $participantToModelMap,
        ClientChatInfoRepository $clientChatInfoRepository,
        ChatHistoryRepository $chatHistoryRepository,
        Gateway $gateway,
        ChatHistoryFactory $chatHistoryFactory,
        ValidatorFactory $validatorFactory
    ) {
        $this->clientsOperatorsMap = $clientsOperatorsMap;
        $this->clientsQueue = $clientsQueue;
        $this->participantToModelMap = $participantToModelMap;
        $this->clientChatInfoRepository = $clientChatInfoRepository;
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->gateway = $gateway;
        $this->chatHistoryFactory = $chatHistoryFactory;
        $this->validatorFactory = $validatorFactory;
    }

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $messageData = $incomingMessage->asArray();

        $this->validatorFactory->make($messageData, ['clientId' => 'required|string']);
        $client = new Participant($messageData['clientId']);
        $operator = $incomingMessage->sender();

        $this->clientsOperatorsMap->assignOperatorToClient($client, $operator);
        $this->clientsQueue->remove($client);

        $operatorModel = $this->participantToModelMap->operator($operator);

        $questionType = $this->clientChatInfoRepository->questionType($client);
        /** @var CustomerService $customerService */
        $customerService = $operatorModel->customerSerivce()->first();

        $clientModel = $this->participantToModelMap->client($client);
        if (null !== $clientModel) {
            $chatHistory = ($clientModel)? $this->chatHistoryRepository->notCompletedChatHistoryForClient($clientModel) : null;

            if (null === $chatHistory) {
                $chatHistory = $this->chatHistoryFactory->create(
                    $clientModel,
                    $questionType,
                    $customerService,
                    $operatorModel
                );
            } else {
                $chatHistory = $this->chatHistoryFactory->updateCustomerService($customerService, $chatHistory);
                $chatHistory = $this->chatHistoryFactory->updateOperator($operatorModel, $chatHistory);
            }
        } else {
            $anonymousClient = $this->participantToModelMap->anonymous($client);
            if (null !== $anonymousClient) {
                $chatHistory = $this->chatHistoryFactory->createForAnonymousClient(
                    $questionType,
                    $customerService,
                    $operatorModel
                );
            }
        }

        $this->chatHistoryRepository->addNotCompletedChatHistory($chatHistory, $client);
        $this->clientChatInfoRepository->updateStatus($client, 'Serving');

        return new MessageCollection([
            new Message(
                new MessageBodyWithOperatorId(
                    $operator->identifier(),
                    new MessageBodyWithClientId(
                        $client->identifier(),
                        new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_ASSIGNED, '')
                    )
                ),
                new Participant(self::OPERATOR_GROUP_NAME, true),
                $this->gateway
            ),

            new Message(
                new MessageBody(MessageTypeEnum::MESSAGE_TYPE_CLIENT_ASSIGNED, ''),
                $client,
                $this->gateway
            ),
        ]);
    }
}
