<?php

namespace App\Chat\Http\Controllers;

use App\Chat\Services\ClientsOperatorsMap;
use App\Chat\Services\ParticipantToModelMap;
use App\Http\Controllers\Controller;
use App\Laravue\JsonResponse;
use App\Laravue\Models\OperatorToUser;
use App\Laravue\Models\User;
use App\MtSports\Services\ClientAuthenticator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OperatorsController extends Controller
{

    public function __construct(
        private ClientAuthenticator $clientAuthenticator,
        private ClientsOperatorsMap $clientsOperatorsMap,
        private ParticipantToModelMap $participantToModelMap,
    ) {}

    public function login(Request $request)
    {
        $validator = \Validator::make($request->json()->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth('api-operators-chat')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([], 200);
    }

    public function operatorInfo(Request $request)
    {
        $client = $this->clientAuthenticator->authenticate(
            \str_replace('Bearer ', '', $request->header('Authorization', '')));
        if (null === $client) {
            throw new NotFoundHttpException();
        }

        $operator = $this->participantToModelMap->operator(
            $this->clientsOperatorsMap->operatorForClient(
                $this->participantToModelMap->clientParticipantId($client)
            )
        );
        if (!$operator) {
            throw new NotFoundHttpException();
        }
        $operatorToUser = OperatorToUser::whereOperatorId($operator->id)->first();
        if (!$operatorToUser) {
            throw new NotFoundHttpException();
        }
        $operatorUser = User::find($operatorToUser->user_id);
        if (!$operatorUser) {
            throw new NotFoundHttpException();
        }

        return [
            'id' => $operatorUser->id,
            'name' => $operatorUser->nick,
            'avatar' => $operatorUser->avatar
        ];
    }

    private function createNewToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api-operators-chat')->factory()->getTTL() * 60,
            'user' => auth('api-operators-chat')->user()
        ]);
    }
}
