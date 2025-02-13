<?php

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWithdrawRequest extends FormRequest
{
    public function rules()
    {
        return [
            'uid' => 'required|int',
            'currencyId' => 'required|int',
            'withdrawAmount' => 'required|numeric',
            'receiveAddress' => 'required|string',
            'notice' => 'string',
        ];
    }
}