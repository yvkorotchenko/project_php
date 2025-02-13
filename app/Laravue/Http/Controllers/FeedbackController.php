<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\FeedbackServices;
use Illuminate\Support\Facades\Auth;
use App\Laravue\Models\User;

class FeedbackController extends Controller
{
    
    
    public function __construct(
        private FeedbackServices $feedbackServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function list(Request $request)
    {

        $page = (int) $request->page ?? 1;
        $size = (int) $request->size ?? 15;

        try {
            $listPagination = $this->feedbackServices->list($page, $size);
            
            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->total,
            );
            
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
    public function updateReply(Request $request )
    {
        $operatorId = (int) $request->get('operatorId', 0);
        $operatorName = (User::where('id', $operatorId)->first())->name;
        $data = [
            "created"      => (string) $request->get('created', ""),
            "id"           => (int) $request->get('id', 0),
            "message"      => (string) $request->get('message', ""),
            "operatorId"   => $operatorId,
            "operatorName" => $operatorName,
        ];

        try {
            $result = $this->feedbackServices->update($data);
            $answer = ($result)? ['error' => '','message' => 'Task Management created', 'result' => $result] :
                                 ['error' => 'Error, Player is not banned ','message' => ''];
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
    public function destroy(Request $request)
    {
        $id = (int) $request->id ?? false;
        if($id){
            try {
                $result = $this->feedbackServices->destroy($id);
                $answer = ($result)? ['error' => '','message' => 'Task Management created', 'result' => $result] : 
                                    ['error' => 'Error, Player is not banned ','message' => ''];
                return json_encode($answer);
            } catch(\Throwable $e) {
                return $this->responseFactory->error($e->getMessage());
            }
        } else {
            return $this->responseFactory->error('ID not null');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOperator()
    {
        return json_encode([
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
    }

}
