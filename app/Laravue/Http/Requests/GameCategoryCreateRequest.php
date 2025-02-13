<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameCategoryCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identity' => 'sometimes|string',
            'name' => 'string|required',
            'nameLocal' => 'string|required',
            'sequence' => 'int|required',
            'visible' => 'bool|required',
        ];
    }
}
