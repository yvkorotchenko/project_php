<?php

namespace App\Chat\Http\Requests\CustomerServiceOperator;

use App\Chat\Models\CustomerService;
use Illuminate\Foundation\Http\FormRequest;

class CustomerServiceOperatorStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email',
            'customer_service_id' => 'required|int|exists:' . CustomerService::class . ',id',
        ];
    }
}
