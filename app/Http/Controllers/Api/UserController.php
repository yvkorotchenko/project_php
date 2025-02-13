<?php

namespace App\Http\Controllers\Api;

use App\Chat\Entities\customerServiceOperators;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\User;
use App\Laravue\Requests\UserAddPermissionsRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use App\Laravue\Services\UserService;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Http\Controllers\Google2faAuthenticationVerificationController as Google2FA;
use Workerman\Protocols\Http;
use App\Laravue\Models\CustomerServiceOperators as CustomerServiceOperatorsModel;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class UserController extends BaseController
{
    const ITEM_PER_PAGE = 20;

    public function __construct(
        private UserService $userService,
        private ResponseFactoryService $responseFactory,
        private Google2FA $google2fa
    ) {}

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        return UserResource::collection(
            $this->userService->paginated(
                $request->get('limit', null),
                $request->get('role', ''),
                $request->get('keyword', '')
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return HttpJsonResponse
     */
    public function store(Request $request): HttpJsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            \array_merge(
                $this->getValidationNameAndNickRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return $this->responseFactory->error($validator->errors(), 403);
        }

        $password = Hash::make($request->input('password'));
        $name = $request->input('name');
        $nick = $request->input('nick');
        $ip = $request->input('ip');
        $google_token = $this->google2fa->getSecretKey();
        $google_code = '';

        $user = User::create([
            'name' => $name,
            'nick' => $nick,
            'ip' => $ip,
            'password' => $password,
            'google_token' => $google_token,
            'google_code' => $google_code,
        ]);

        return $this->responseFactory->formattedJson((new UserResource($user))->toArray($user));
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return HttpJsonResponse
     */
    public function show(User $user): HttpJsonResponse
    {
        return $this->responseFactory->formattedJson((new UserResource($user))->toArray($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return HttpJsonResponse
     */
    public function update(Request $request, User $user): HttpJsonResponse
    {
        if ($user === null) {
            return $this->responseFactory->error('User not found', 404);
        }
        if ($user->isAdmin()) {
            return $this->responseFactory->error('Admin can not be modified', 403);
        }

        $currentUser = Auth::user();

        if ( !$currentUser->isAdmin() && $currentUser->id !== $user->id && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_USER_MANAGE) ) {
            return $this->responseFactory->error('Permission denied', 403);
        }

        $validator = Validator::make(
            $request->all(),
            $this->getValidationNameAndNickRules(false)
        );

        if ($validator->fails()) {
            return $this->responseFactory->error($validator->errors(), 403);
        }

        $user_id = $request->get('id');
        // found user in id
        $found = User::where('id', $user_id)->first();

        if ($found && $found->id !== $user->id) {
            return $this->responseFactory->error('Name has been taken', 403);
        }

        $user->name = $request->get('name');
        $user->nick = $request->get('nick');

        if ($request->get('ip') !== '') {
            $user->ip = $request->get('ip');
        }

        if ($request->get('password') !== '') {
            if ($request->get('password') !== $request->get('confirmPassword')) {
                return $this->responseFactory->error('Passwords are not the same');
            }

            $user->password = Hash::make($request->get('password'));
        }

        // проверяем является ли юзер Customer Service Operator
        $checkCSOperator = CustomerServiceOperatorsModel::find($user->id);
        if ($checkCSOperator) {
            $checkCSOperator->name = $user->name;
            $checkCSOperator->customer_service_id = 1;
            $checkCSOperator->created_at = $user->created_at;
            $checkCSOperator->updated_at = $user->updated_at;
            $checkCSOperator->email = $user->nick;
            $checkCSOperator->password = $user->password;
            $checkCSOperator->phone = $user->phone;
            $checkCSOperator->save();
        }

        $user->save();

        return $this->responseFactory->messageSuccessJson(200, 'User update successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return HttpJsonResponse
     */
    public function updatePermissions(Request $request, User $user): HttpJsonResponse
    {
        if ($user === null) {
            return $this->responseFactory->error('User not found', 404);
        }

//        if ($user->isAdmin()) {
//            return $this->responseFactory->error('Admin can not be modified', 403);
//        }

        $permissionIds = $request->get('permissions', null);

        if ($permissionIds !== null) {
            try {
                $user->syncPermissions($permissionIds);

                return $this->responseFactory->messageSuccessJson(200, 'Success change permission');
            } catch (\Throwable $e) {
                return $this->responseFactory->error('Failed to change permission for user. ' . $e->getMessage());
            }
        }

        return $this->responseFactory->error('Permission empty', 404);

//        $rolePermissionIds = array_map(
//            function($permission) {
//                return $permission['id'];
//            },
//
//            $user->getPermissionsViaRoles()->toArray()
//        );

//        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
//        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();

//        $user->syncPermissions($permissions);

//        return new UserResource($user);
    }

    public function updateRoles(Request $request, User $user): HttpJsonResponse
    {
        if ($user === null) {
            return $this->responseFactory->error('User not found', 404);
        }

        $roles = $request->get('roles', null);

        // проверка на Update User in role = id 11
        // проверяем если такой оператор
        // update or insert
        if (in_array(11,$roles)) {
            $csOperator = CustomerServiceOperatorsModel::find($user['id']);
            if ($csOperator) {
                $csOperator->name = $user['name'];
                $csOperator->customer_service_id = 1;
                $csOperator->created_at = $user['created_at'];
                $csOperator->updated_at = $user['updated_at'];
                $csOperator->email = $user['nick'];
                $csOperator->password = $user['password'];
                $csOperator->phone = $user['phone'];
                $csOperator->save();
            } else {
                $csOperatorNew = new CustomerServiceOperatorsModel();
                $csOperatorNew->id = $user['id'];
                $csOperatorNew->name = $user['name'];
                $csOperatorNew->customer_service_id = 1;
                $csOperatorNew->created_at = $user['created_at'];
                $csOperatorNew->updated_at = $user['updated_at'];
                $csOperatorNew->email = $user['nick'];
                $csOperatorNew->password = $user['password'];
                $csOperatorNew->phone = $user['phone'];
                $csOperatorNew->save();
            }
        }
        // когда роль "Customer Service Operator" отсуствует
        else {
            $csOperator = CustomerServiceOperatorsModel::find($user['id']);
            if( $csOperator ) {
                $csOperator->delete();
            }
        }

        if ($roles !== null) {
            try {
                $user->syncRoles($roles);

                return $this->responseFactory->messageSuccessJson(200, 'Success change roles');
            } catch(\Throwable $e) {
                return $this->responseFactory->error('Failed to change role for user. ' . $e->getMessage(), 404);
            }
        }

        return $this->responseFactory->error('Roles empty', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String $id
     * @return HttpJsonResponse
     */
    public function destroy($id): HttpJsonResponse
    {
        // list users ids
        $ids = explode(',', $id);
        // errors messages
        $errors = '';

        try {
            foreach($ids as $userId) {
                $user = User::find((int) $userId);

                if ($user !== null) {
                    if (!$user->isAdmin()) {
                        $user->delete();
                    } else {
                        // Try delete admin user
                        $errors = 'Ehhh! Can not delete admin user';
                    }
                } else {
                    // user not isset
                    $errors = 'User not found';
                }
            }

        } catch(\Exception $e) {
            return $this->responseFactory->error($e->getMessage(), 403);
        }

        return ($errors == '')
            ?
            $this->responseFactory->messageSuccessJson(204)
            :
            $this->responseFactory->error($errors, 404);
    }

    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
                'roles' => PermissionResource::collection($user->roles),
                'permissions' => PermissionResource::collection($user->getAllPermissions()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    public function addPermissions(UserAddPermissionsRequest $request, User $user): HttpJsonResponse
    {
        if ($user === null) {
            return $this->responseFactory->error('User not found', 404);
        }

        $permissions = $request->get('permissions', '');

        $user->syncPermissions($permissions);

        return $this->responseFactory->messageSuccessJson(200, 'Update permissions for User ' . $user->name);
    }

    private function getValidationRolesRules(): array
    {
        return [
            'roles' => [
                'required',
                'array'
            ],
        ];
    }

    private function getValidationNameAndNickRules(bool $isNew = true): array
    {
        return [
            'name' => $isNew ? 'bail|required|unique:users' : 'required|min:3',
            'nick' => $isNew ? 'bail|required|unique:users' : 'required|min:3',
        ];
    }
}
