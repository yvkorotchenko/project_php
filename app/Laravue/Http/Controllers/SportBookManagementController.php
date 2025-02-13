<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Laravue\Services\SportBookManagementService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\Request;

class SportBookManagementController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private SportBookManagementService $leaguesService,
    ) {}

    public function index(Request $request)
    {
        $result = $this->leaguesService->getAllLeagues();
        $answer = ($result)?['error' => '','message' => 'all marked leagues received ']:['error' => 'Error, all marked leagues are not received','message' => ''];
        return json_encode($answer);
    }

    public function update(Request $request) {
        $leagues =  $request->get('leagues');
        $result = $this->leaguesService->setAllLeagues($leagues);
        $answer = ($result)?['error' => '','message' => 'all marked leagues have been sent']:['error' => 'Error, all marked leagues have not been sent','message' => ''];
        return json_encode($answer);
    }
}
