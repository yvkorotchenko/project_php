<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerImageUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'image' => 'required|image',
        ];
    }
}
