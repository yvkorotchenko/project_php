<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlatformUpdateCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories.*.id' => 'required|integer|min:1',
            'categories.*.sequence' => 'required|integer|max:999|min:0',
        ];
    }
}
