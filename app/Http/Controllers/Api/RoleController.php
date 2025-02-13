<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Laravue\Services\RoleService;
use App\Http\Resources\RoleResource;
use App\Laravue\Requests\RoleDestroyListRequest;
use App\Laravue\Requests\RoleStoreRequest;
use App\Laravue\Requests\RoleUpdateRequest;
use App\Laravue\Requests\RoleAddPermissionsRequest;

/**
 * Class RoleController
 *
 * @package App\Http\Controllers\Api
 */
class RoleController extends BaseController
{
    public function __construct(
        private RoleService $roleService,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResource
    {
        return RoleResource::collection(
            $this->roleService->list($request->all())
        );
    }

    public function store(RoleStoreRequest $request): RoleResource
    {
        $validated = $request->validated();

        return new RoleResource(
            $this->roleService->create($validated)
        );
    }

    public function show(Role $role): Response
    {
        //
    }

    public function update(RoleUpdateRequest $request, Role $role): JsonResponse
    {
        if ($role === null) {
            return $this->responseFactory->json(['error' => 'Role not found'], 404);
        }

        return $this->responseFactory->json([
            'data' => $this->roleService->update($request->validated(), $role)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if ($id != 11) {
            if ($this->roleService->delete($id)) {
                return $this->responseFactory->json(null, 204);
            } else {
                return $this->responseFactory->json(['error' => 'Removing an administrative role is prohibited']);
            }
        }
    }

    public function destroyList(RoleDestroyListRequest $request): JsonResponse
    {
        if ($this->roleService->destroyList($request->validated())) {
            return $this->responseFactory->json(null, 204);
        } else {
            return $this->responseFactory->json(['error' => 'error deleting selected roles'], 404);
        }
    }

    public function permissions(Role $role): JsonResource
    {
        return PermissionResource::collection($role->permissions);
    }

    public function addPermissions(RoleAddPermissionsRequest $request, Role $role): JsonResponse
    {
        if ($role === null) {
            return $this->responseFactory->error('Role not found');
        }

        $permissions = $request->get('permissions', []);

        $role->syncPermissions($permissions);

        return $this->responseFactory->messageSuccessJson(200, 'Update permission for Role ' . $role->name);
    }
}
