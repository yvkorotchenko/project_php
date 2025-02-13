<?php

declare(strict_types=1);

namespace App\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    private const IMG_MIME_TYPES = 'image/bmp,image/gif,image/jpeg,image/png,image/png,image/tiff';
//    private const VIDEO_MIME_TYPES = 'video/mpeg,video/x-msvideo,video/ogg,video/3gpp,video/mp4';
    private const VIDEO_MIME_TYPES = 'video/mp4';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
             'media' => 'file|mimetypes:' . self::IMG_MIME_TYPES . ',' . self::VIDEO_MIME_TYPES,
        ];
    }
}
