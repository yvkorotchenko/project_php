<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|int',
            'name' => 'required|string',
            'visible' => 'required|bool',
            'parentName' => 'required|string',
            'game_categories' => 'array|nullable'
        ];
    }
}
