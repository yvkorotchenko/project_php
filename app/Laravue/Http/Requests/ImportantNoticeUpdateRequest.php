<?php

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportantNoticeUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|int',
            'titleEn' => 'required|string',
            'titleLocal' => 'required|string',
            'contentEn' => 'required|string',
            'contentLocal' => 'required|string',
            'state' => 'required|boolean'
        ];
    }
}