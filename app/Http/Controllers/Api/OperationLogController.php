<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\OperationLogResource;
use App\MtSports\JsonResponse;
use App\MtSports\Models\Permission;
use App\MtSports\Models\Role;
use App\MtSports\Models\User;
use App\MtSports\Models\OperationLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class OperationLogController extends BaseController
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();

        $operationQuery = OperationLog::query();

        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        $role = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $operationQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $operationQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $operationQuery->where('type_req', 'LIKE', '%' . $keyword . '%');
            $operationQuery->where('ip', 'LIKE', '%' . $keyword . '%');
        }

        return OperationLogResource::collection($operationQuery->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function create(Request $request)
    {
        /*
         * $u = Auth::user();
//    return  $request->ip();
//    return '<pre>' . print_r($_SERVER, true) . '</pre>';
    return '<pre>' . print_r($u->name, true) . '</pre>';
         * */

        /* $param = $request->all();

         $operation_log = OperationLog::create([
             'name' => Auth::user()->name,
             'action',
             'type_req' ,
             'path_req',
             'data_req',
             'ip' => $request->ip(),
         ]);
         return '{empty}';*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function show(OperationLog $operationLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function edit(OperationLog $operationLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OperationLog $operationLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(OperationLog $operationLog)
    {
        //
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return JsonResponse
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }
}

