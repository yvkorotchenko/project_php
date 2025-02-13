<?php

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeWithdrawStateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'withdrawId' => 'required|int',
            'stateAction' => 'required|string',
        ];
    }
}