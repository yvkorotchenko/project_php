<?php

declare(strict_types=1);

namespace App\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatHistorySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => 'date_format:"Y-m-d H:i:s"|different:to',
            'to' => 'date_format:"Y-m-d H:i:s"|different:from',
            'customerServiceId' => 'integer',
        ];
    }
}
