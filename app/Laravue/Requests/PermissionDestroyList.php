<?php

namespace App\Laravue\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionDestroyList extends FormRequest
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
    public function rules(): array
    {
        return [
            'ids' => 'present|array|min:1',
        ];
    }
}
