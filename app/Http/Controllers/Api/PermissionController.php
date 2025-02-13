<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use App\Laravue\Models\Permission;
use App\Laravue\Services\PermissionService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use App\Laravue\Services\ResponseFactory as ServiceResponseFactory;
use App\Laravue\Requests\PermissionsIndexRequest;
use App\Laravue\Requests\PermissionDestroyList;
use App\Laravue\Requests\PermissionStoreRequest;
use App\Laravue\Requests\PermissionUpdateRequest;
/**
 * Class PermissionController
 *
 * @package App\Http\Controller\Api
 */
class PermissionController extends BaseController
{
    public function __construct(
        private PermissionService $permissionService,
        private ResponseFactory $responseFactory,
        private ServiceResponseFactory $serviceResponseFactory,
    ) {}

    public function all(): JsonResponse
    {
        try {
            return $this->serviceResponseFactory->formattedJson(
                $this->permissionService->all(),
            );
        } catch (\Throwable $e) {
            return $this->serviceResponseFactory->error($e->getMessage());
        }
    }

    public function index(PermissionsIndexRequest $request): JsonResource
    {
        return PermissionResource::collection(
            $this->permissionService->list($request->validated())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionStoreRequest $request): PermissionResource
    {
        $validated = $request->validated();

        return new PermissionResource(
            $this->permissionService->create($validated)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, Permission $permission): JsonResponse
    {
        if ($permission === null) {
            return $this->responseFactory->json(['error' => 'Permission not found'], 404);
        }

        return $this->responseFactory->json([
            'data' => $this->permissionService->update($request->validated(), $permission)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if ($this->permissionService->delete($id)) {
            return $this->responseFactory->json(null, 204);
        } else {
            return $this->responseFactory->json(['error' => 'Failed to delete this permission']);
        }
    }

    public function destroyList(PermissionDestroyList $request): JsonResponse
    {
        if ($this->permissionService->destroyList($request->validated())) {
            return $this->responseFactory->json(null, 204);
        } else {
            return $this->responseFactory->json(['error' => 'error deleting selected roles'], 404);
        }
    }

    public function children(Request $request)
    {
        return $this->responseFactory->json(['data' => $request->all()]);
    }
}
