<?php

declare(strict_types=1);

namespace App\Chat\Http\Controllers;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Models\QuestionType;
use App\Chat\Queue\ClientsQueue;
use App\Chat\Repositories\ClientChatInfoRepository;
use App\Chat\Services\AnonymousChatUserService;
use App\Chat\Services\ParticipantToModelMap;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

class ClientsController
{
    public function __construct(
        private ClientsQueue $clientsQueue,
        private ResponseFactoryService $responseFactory,
        private ClientChatInfoRepository $clientChatInfoRepository,
        private ParticipantToModelMap $participantToModelMap,
        private AnonymousChatUserService $anonymousChatUserService,
    )
    {}

    public function clientsInQueue(): JsonResponse
    {
        $clientsInQueue = $this->clientsQueue->all();
        $responseData = array_map(fn(string $clientId) => $this->getClientInfo($clientId), $clientsInQueue);

        return $this->responseFactory->json($responseData);
    }

    public function clientInfo(string $clientId): JsonResponse
    {
        return $this->responseFactory->json($this->getClientInfo($clientId));
    }

    public function clientDetailedInfo(string $clientId): JsonResponse
    {
        $client = $this->participantToModelMap->client(new Participant($clientId));
        if (null === $client) {
            $anonymousChatClient = $this->participantToModelMap->anonymous(new Participant($clientId));
            if (null !== $anonymousChatClient) {
                return $this->responseFactory->json([
                    'id' => $clientId,
                    'clientId' => 0,
                    'name' => 'Anonymous',
                    'phone' => 'Anonymous',
                    'bankAccount' => '**** **** **** ****',
                    'bankCard' => '**** **** **** ****',
                    'cardHolderName' => 'Anonymous',
                    'email' => 'anonymous',
                ]);
            } else {
                throw new NotFoundHttpException();
            }
        }

        return $this->responseFactory->json([
            'id' => $clientId,
            'clientId' => $client->id,
            'name' => $client->nickname,
            'phone' => $client->phone,
            'bankAccount' => '1111 1111 1111 1111',
            'bankCard' => '0000 0000 0000 0000',
            'cardHolderName' => 'Bernard Smith',
            'email' => 'test@example.com',
        ]);
    }

    private function getClientInfo(string $clientId): array
    {
        $clientChatParticipant = new Participant($clientId);
        try {
            $clientStatus = $this->clientChatInfoRepository->status($clientChatParticipant);
        } catch (\Throwable $e) {
            $clientStatus = 'Undefined';
        }

        try {
            $clientQuestionType = $this->clientChatInfoRepository->questionType($clientChatParticipant);
        } catch (\Throwable $e) {
            $clientQuestionType = QuestionType::first();
        }

        return [
            'id' => $clientId,
            'customerId' => \bin2hex(\random_bytes(4)),
            'date' => date('Y-m-d H:i:s'),
            'name' => \bin2hex(\random_bytes(4)),
            'category' => $clientQuestionType->name,
            'status' => $clientStatus,
        ];
    }
    
    public function clientPublicKey(Request $request): JsonResponse
    {
        if($this->anonymousChatUserService->validPublicKey((string)$request->get('publicKey', ''))){
            $token = $this->anonymousChatUserService->generateTemporayToken();
            $this->anonymousChatUserService->createAnonymousChatClient($token);

            return $this->responseFactory->json(['token' => $token]);
        }

        return $this->responseFactory->error('invalid public key');
    }
}
