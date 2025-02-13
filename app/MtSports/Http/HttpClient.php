<?php

namespace App\MtSports\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HttpClient
{
    private const DEFAULT_PATH = 'news/';
    private const DEFAULT_STORAGE = 'local';

    public function get(string $url): string
    {
        if ($url === '') {
            throw new \InvalidArgumentException('Empty link passed!');
        }

        $response = Http::get($url);

        if ($response->status() !== 200 || $response->failed()) {
            throw new \Exception('Response status not successful: ' . $response->status() . ' url: ' . $url);
        }

        return $response->body();
    }

    public function downloadImage(
        string $url,
        string $path = self::DEFAULT_PATH,
        string $storage = self::DEFAULT_STORAGE
    ): string {
        if (!Storage::disk($storage)->exists($path)) {
            Storage::disk($storage)->makeDirectory($path);
            echo 'Directory created: -> ' . $path . PHP_EOL;
        }

        $path .= $fileName = \md5(microtime());

        Storage::disk($storage)->put($path, $this->get($url));

        $file = Storage::disk($storage)->path($path);

        $extension = \image_type_to_extension(getimagesize($file)[2]);

        Storage::disk($storage)->move($path, $path . $extension);

        return $fileName . $extension;
    }

    public function addToPath(string $path): string
    {
        return self::DEFAULT_PATH . $path . '/';
    }

    public function deleteImage(
        string $path,
        string $name,
        string $storage = self::DEFAULT_STORAGE
    ): bool
    {
        $path = $this->addToPath($path);

        if ($name !== null or $name !== '') {
            if (Storage::disk($storage)->delete($path . $name))
            {
                echo 'Image <' . $name . '> on path [' . $path . '] deleted success' . PHP_EOL;
            } else {
                echo 'Image <' . $name . '> on path [' . $path . '] deleted error!!!' . PHP_EOL;
                return false;
            }
            return true;
        }

        echo 'Image name empty!!!' . PHP_EOL;

        return false;
    }
}
