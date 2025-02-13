<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\StopServiceAnnouncementServices;
use App\Laravue\Models\User;
use App\Laravue\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

class StopServiceAnnouncementController extends Controller
{
  public function __construct(
      private ResponseFactoryService $responseFactory,
      private Google2FA $google2fa,
      private StopServiceAnnouncementServices $stopServiceAnnouncement,
  )
  {}

  public function store(Request $request, User $user): JsonResponse
  {
    $id = (int) Auth::user()->id;
    $googleCode = $request->get('google2faCode');

    $this->google2fa->setSecret($user->google_token);

    if ($this->google2fa->verify($googleCode, $this->google2fa->getSecret())) {

      $user->google_code = $googleCode;
      $user->save();

      try {
          $this->stopServiceAnnouncement->store($request->all());

          return $this->responseFactory->messageSuccessJson();
      } catch (\Throwable $e) {
          return $this->responseFactory->error($e->getMessage());
      }
    }

    return $this->responseFactory->error('Incorrect verification code, please try to enter again');
  }
}
