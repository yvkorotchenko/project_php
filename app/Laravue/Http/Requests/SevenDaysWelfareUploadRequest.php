<?php

declare(strict_types=1);

namespace App\Laravue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SevenDaysWelfareUploadRequest extends FormRequest
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
            'type' => 'required:string',
            'image' => 'required|image',
        ];
    }
}
