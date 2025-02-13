<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Laravue\Models\AccountSuspension;
use App\Laravue\Models\AccountSuspensionStatus;
use App\Laravue\Models\ReasonForSuspension;
use Illuminate\Support\Facades\DB;

class AccountSuspensionController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->created_at = date('Y-m-d H:i:s');
        $request->updated_at = date('Y-m-d H:i:s');
        $request->unbloking_time = date('Y-m-d H:i:s', strtotime('+'.$request->banned_days.' day'));
//        dd($request->all());
        AccountSuspension::create($request->all());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AccountSuspension::destroy($id);
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
