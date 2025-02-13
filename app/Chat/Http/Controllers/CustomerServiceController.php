<?php

declare(strict_types=1);

namespace App\Chat\Http\Controllers;

use App\Chat\Http\Requests\CustomerService\CustomerServiceStoreRequest;
use App\Chat\Http\Requests\CustomerService\CustomerServiceUpdateRequest;
use App\Chat\Http\Resources\CustomerService as CustomerServiceResource;
use App\Chat\Models\CustomerService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CustomerServiceController
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function index(): JsonResponse
    {
        return $this->responseFactory->json(
            CustomerServiceResource::collection(
                CustomerService::all()
            )
        );
    }

    public function store(CustomerServiceStoreRequest $request): JsonResponse
    {
        $model = new CustomerService($request->all());
        $model->save();

        return $this->responseFactory->json(new CustomerServiceResource($model), 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->responseFactory->json(
            new CustomerServiceResource(
                CustomerService::findOrFail($id)
            )
        );
    }

    public function update(CustomerServiceUpdateRequest $request, int $id): JsonResponse
    {
        $model = CustomerService::findOrFail($id);

        if (!$model->fill($request->all())->save()) {
            throw new UnprocessableEntityHttpException();
        }

        return $this->responseFactory->json(new CustomerServiceResource($model));
    }

    public function destroy(int $id): Response
    {
        if (!CustomerService::destroy($id)) {
            return new UnprocessableEntityHttpException();
        }

        return $this->responseFactory->make('', 204);
    }
}
