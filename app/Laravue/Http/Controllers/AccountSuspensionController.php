<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\AccountSuspension;
use App\Laravue\Models\AccountSuspensionStatus;
use App\Laravue\Models\ReasonForSuspension;
use Illuminate\Support\Facades\Auth;
use App\Laravue\Services\AccountSuspensionServices;
use App\Laravue\Services\ResponseFactoryService;

class AccountSuspensionController extends Controller
{
    public function __construct(
        private AccountSuspensionServices $accountSuspensionServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = (new AccountSuspension)->getAll($request);
        return json_encode($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isAccountIdBanned = AccountSuspension::where('account_id', $request->account_id)
            ->where('status_id','1')
            ->orderByDesc('created_at')
            ->first();
        $dateNow = strtotime(date('Y-m-d h:i:s'));
        $dateUnbloking = (!empty($isAccountIdBanned->unbloking_time))?strtotime($isAccountIdBanned->unbloking_time):0;
        if($dateUnbloking >= $dateNow){
            return json_encode([
                'error' => 'User already banned',
                'message' => '',
            ]);
        }
        try {
            $result = AccountSuspension::create(array_merge($request->all(),[
                'last_operation_user_id' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'unbloking_time' => date('Y-m-d H:i:s', strtotime('+'.$request->banned_days.' day')),
            ]));

            $this->accountSuspensionServices->sendPlayerBanned($request->account_id);

            $answer = ($result)?['error' => '','message' => 'Player banned']:['error' => 'Error, Player is not banned ','message' => ''];
            return json_encode($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $accountBanned = AccountSuspension::find($id);
            $accountBanned->status_id = $request->status_id;
            $accountBanned->last_operation_user_id = Auth::user()->id;
            $result = $accountBanned->save();

            $this->accountSuspensionServices->sendPlayerUnBanned($accountBanned->account_id);
            
            $answer = ($result)?['error' => '','message' => 'Player unbanned']:['error' => 'Error, user is not unbanned ','message' => ''];
            return json_encode($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = AccountSuspension::destroy($id);
            $answer = ($result)?['error' => '','message' => 'Record deleted']:['error' => 'Error, the entry was not deleted','message' => ''];
            return json_encode($answer);
        } catch(\Throwable $e) {
            return json_encode([
                'error' => $e->getMessage(),
                'message' => '',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status()
    {
        $model = AccountSuspensionStatus::all();
        return $model->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reason()
    {
        $model = ReasonForSuspension::all();
        return $model->toJson();
    }
}
