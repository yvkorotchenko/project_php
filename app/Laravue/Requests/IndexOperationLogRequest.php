<?php

namespace App\Laravue\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexOperationLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_req' => 'string',
            'path_req' => 'string',
            'data_req' => 'string',
            'ip' => 'ip',
            'id' => 'sometimes|integer',
            'name' => 'string',
            'action' => 'string',
            'start' => 'date',
            'end' => 'date',
            'limit' => 'integer',
            'page' => 'integer',
            'searchParam' => 'string',
        ];
    }
}
