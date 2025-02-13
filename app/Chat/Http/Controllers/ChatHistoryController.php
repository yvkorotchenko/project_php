<?php

declare(strict_types=1);

namespace App\Chat\Http\Controllers;

use App\Chat\Entities\ChatHistoryFilter;
use App\Chat\Entities\ChatHistorySorter;
use App\Chat\Http\Requests\ChatHistorySearchRequest;
use App\Chat\Http\Resources\ChatHistory as ChatHistoryResource;
use App\Chat\Repositories\ChatHistoryRepository;
use App\MtSports\Models\Client;
use App\MtSports\Services\ClientAuthenticator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChatHistoryController
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private ChatHistoryRepository $chatHistoryRepository,
        private ClientAuthenticator $clientAuthenticator
    ) {}

    public function list(): JsonResponse
    {
        return $this->responseFactory->json(
            ChatHistoryResource::collection(
                $this->chatHistoryRepository->allDetailedCompleted()
            )
        );
    }

    public function view(int $id): JsonResponse
    {
        return $this->responseFactory->json(
            new ChatHistoryResource(
                $this->chatHistoryRepository->chatHistoryDetailed($id)
            )
        );
    }

    public function search(ChatHistorySearchRequest $request): JsonResponse
    {
        return $this->responseFactory->json(
            ChatHistoryResource::collection(
                $this->chatHistoryRepository->searchCompletedChatHistories(
                    new ChatHistoryFilter(
                        $request->get('from', ''),
                        $request->get('to', ''),
                        (int)$request->get('customerServiceId', 0),
                        (int)$request->get('limit', 0)

                    ),
                    new ChatHistorySorter(
                        $request->get('sortField', ''),
                        $request->get('sortOrder', '')
                    )
                )
            )
        );
    }

    public function notCompletedForClient(int $clientId): JsonResponse
    {
        $client = Client::find($clientId);
        if (null === $client) {
            throw new NotFoundHttpException();
        }

        $history = $this->chatHistoryRepository->notCompletedChatHistoryForClient($client);
        if (null === $history) {
            throw new NotFoundHttpException();
        }

        return $this->responseFactory->json(new ChatHistoryResource($history));
    }

    public function lastChatHistory(Request $request): JsonResponse
    {
        $client = $this->clientAuthenticator->authenticate(
            \str_replace('Bearer ', '', $request->header('Authorization', '')));
        if (null === $client) {
            throw new NotFoundHttpException();
        }

        $lastChatHistory = $this->chatHistoryRepository->lastCompletedChatHistory($client);
        if (null === $lastChatHistory) {
            throw new NotFoundHttpException();
        }

        return $this->responseFactory->json(new ChatHistoryResource($lastChatHistory));
    }
}
