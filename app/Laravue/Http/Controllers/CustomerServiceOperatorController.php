<?php

namespace App\Laravue\Http\Controllers;

use App\Chat\Http\Requests\CustomerServiceOperator\CustomerServiceOperatorStoreRequest;
use App\Chat\Http\Requests\CustomerServiceOperator\CustomerServiceOperatorUpdateRequest;
use App\Chat\Http\Resources\CustomerServiceOperator as CustomerServiceOperatorResource;
use App\Chat\Models\CustomerServiceOperator;
use App\Http\Controllers\Controller;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Models\User;
use App\Laravue\Models\Role;
use App\Laravue\Models\OperatorToUser;
use App\Laravue\Models\ModelHasRoles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Chat\Services\CustomerServiceOperatorService;
use Illuminate\Support\Facades\DB;

class CustomerServiceOperatorController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private CustomerServiceOperatorService $customerServiceOperatorService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->customerServiceOperatorService->paginated(
            (int)$request->get('page', 0),
            (int)$request->get('limit', 0)
        );
        return $this->responseFactory->paginatedJson(
            $paginated->items(),
            $paginated->total()
        );
    }

    public function store(CustomerServiceOperatorStoreRequest $request): JsonResponse
    {
        $req = $request->all();
        $req['password'] = Hash::make($req['password']);
        $model = new CustomerServiceOperator($req);
        try {
            $model->save();
            $roleOperator = DB::table('roles')->where('alias', 'CustomerServiceOperator')->first();
            $newUser = new User($req);
            $newUser->save();
            DB::table('model_has_roles')->insert([
                'role_id' => $roleOperator->id,
                'model_type' => 'App\Laravue\Models\User',
                'model_id' => $newUser->id,
            ]);

            $modelOperatorToUser = new OperatorToUser();
            $modelOperatorToUser->user_id = $newUser->id;
            $modelOperatorToUser->operator_id = $model->id;
            $modelOperatorToUser->save();

        } catch (\Throwable $e) {
            return $this->responseFactory->json(['error' => $e->getMessage()], 400);
        }

        return $this->responseFactory->json(new CustomerServiceOperatorResource($model), 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->responseFactory->json(
            new CustomerServiceOperatorResource(
                CustomerServiceOperator::findOrFail($id)
            )
        );
    }

    public function update(CustomerServiceOperatorUpdateRequest $request, int $id): JsonResponse
    {

        $modelOperatorToUser = OperatorToUser::where('operator_id',$id)->first();
        $model = User::findOrFail($modelOperatorToUser->user_id);
        $model->nick = $request->email;
        if (!$model->fill($request->all())->save()) {
            throw new UnprocessableEntityHttpException();
        }

        $model = CustomerServiceOperator::findOrFail($id);

        if (!$model->fill($request->all())->save()) {
            throw new UnprocessableEntityHttpException();
        }

        return $this->responseFactory->json(new CustomerServiceOperatorResource($model));
    }

    public function destroy(int $id): Response
    {
        $modelOperatorToUser = OperatorToUser::where('operator_id',$id)->first();
        $modelRoles = Role::where('name','Customer Service Operator')->first();
        $modelModelHasRoles = ModelHasRoles::where('model_id','=',$modelOperatorToUser->user_id)->where('role_id','=',$modelRoles->id)->first();
        
        if (!$modelModelHasRoles::where('role_id', $modelModelHasRoles->role_id)
            ->where('model_type', $modelModelHasRoles->model_type)
            ->where('model_id', $modelModelHasRoles->model_id)->delete()) {
            throw new UnprocessableEntityHttpException();
        }
        if (!OperatorToUser::destroy($modelOperatorToUser->id)) {
            throw new UnprocessableEntityHttpException();
        }
        if (!User::destroy($modelOperatorToUser->user_id)) {
            throw new UnprocessableEntityHttpException();
        }
        if (!CustomerServiceOperator::destroy($id)) {
            throw new UnprocessableEntityHttpException();
        }

        return $this->responseFactory->make('', 204);
    }

    public function getAllOperators()
    {
        return User::all();
    }
}
