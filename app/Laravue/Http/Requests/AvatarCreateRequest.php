<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'img_url' => 'required|url',
            'position' => 'required|numeric',
            'visible' => 'required|boolean',
        ];
    }
}