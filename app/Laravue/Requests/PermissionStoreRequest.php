<?php

namespace App\Laravue\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
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
            'name' => 'string',
            'parent' => 'nullable|integer',
            'icon' => 'nullable|string',
            'route' => 'nullable|string',
            'showName' => 'nullable|string',
            'sort' => 'integer',
            'visible' => 'integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'showName' => $this->parent ?: '.',
            'route' => $this->route ?: '.',
        ]);
    }
}
