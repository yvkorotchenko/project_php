<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'visible' => 'required|bool',
            'parentName' => 'required|string',
            'game_categories' => 'array|nullable'
        ];
    }
}
