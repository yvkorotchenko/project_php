<?php

namespace App\Laravue\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
            'name' => 'bail|required|max:27',
            'name_zh' => 'bail|required|max:27',
            'alias' => 'bail|required|max:27',
            'code' => 'bail|required|regex:/^[\d]{6}$/',
        ];
    }
}
