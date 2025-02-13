<?php


namespace App\Chat\Http\Controllers;


use App\Chat\Http\Resources\QuestionType;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;

class QuestionTypeController
{
    public function __construct(
        private ResponseFactoryService $responseFactory
    ) {}

    public function list(): JsonResponse
    {
        return $this->responseFactory->json(
            QuestionType::collection(\App\Chat\Models\QuestionType::all())
        );
    }
}