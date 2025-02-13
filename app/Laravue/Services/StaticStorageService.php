<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;

class StaticStorageService
{
    private Client $httpClient;
    private string $token;
    private string $uploadImageUri;
    private string $deleteImagesUri;
    private array $supportedTypes = ['gif', 'jpeg', 'jpg', 'png', 'svg', 'svg+xml'];
    private array $img = ['buffer' => '', 'imgName' => '', 'extension' => ''];
    private bool $verify;

    public function __construct(string $token, string $uploadImageUri, string $deleteImagesUri, bool $verify)
    {
        $this->httpClient = new Client();
        $this->token = $token;
        $this->uploadImageUri = $uploadImageUri;
        $this->deleteImagesUri = $deleteImagesUri;
        $this->verify = $verify;
    }

    public function downloadImageByUrl(string $url): string
    {
        $response = $this->httpClient->get($url);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Response status not successful: ' . $response->getStatusCode() . ' url: [- ' . $url . ' -]');
        }

        $this->img['buffer'] = $response->getBody();

        $this->img['extension'] = $this->getBufferMimeTypeFile((string) $this->img['buffer']);

        $this->img['imgName'] = \md5(microtime() . '.' . $this->img['extension']) . '.' . $this->img['extension'];

        return $this->img['imgName'];
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function uploadImage(UploadedFile $file): string
    {
        $fileExtension = \preg_replace('#[^/]+\/#i', '.', $file->getMimeType());

        return $this->uploadFileContent($file->getContent(), $fileExtension, $file->getClientOriginalName());
    }

    public function uploadImageByUrl(string $url): string
    {
        $this->downloadImageByUrl($url);

        if (!in_array($this->img['extension'], $this->supportedTypes)) {
            throw new \LogicException('Invalid field type');
        }

        $response = $this->httpClient->post(
            $this->uploadImageUri,
            [
                'headers' => [
                    'Accept'                => 'application/json',
                    'Authorization'         => 'Bearer ' . $this->token,
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'filename' => $this->img['imgName'],
                        'contents' => $this->img['buffer'],
                    ],
                ],
                'verify' => $this->verify,
            ],
        );

        $body = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unsuccessful response code ' . $body);
        }

        return \json_decode($body, true, JSON_THROW_ON_ERROR)['url'];
    }

    public function uploadImagesByUrls(array $urls): array
    {
        $links = [];

        foreach ($urls as $url) {
            $links[] = $this->uploadImagesByUrls($url);
        }

        return $links;
    }

    public function uploadFileContent(string $content, string $fileExtension, ?string $fileName = null): string
    {
        if (null === $fileName) {
            $fileName =  \md5((string)time());
        }

        $response = $this->httpClient->post(
            $this->uploadImageUri,
            [
                'headers' => [
                    'Accept'                => 'application/json',
                    'Authorization'         => 'Bearer ' . $this->token,
                ],
                'multipart' => [
                    [
                        'name'     => 'file',
                        'filename' => $fileName . '.' . $fileExtension,
                        'contents' => $content,
                    ],
                ],
                'verify' => $this->verify,
            ],
        );

        $body = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unsuccessful response code ' . $body);
        }

        $result = \json_decode($body, true, JSON_THROW_ON_ERROR);
        if (\is_array($result) && \array_key_exists('url', $result)) {
            return $result['url'];
        } else {
            throw new \Exception('Can not upload file. Undefined error');
        }
        return \json_decode($body, true, JSON_THROW_ON_ERROR)['url'];
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function deleteImages(array $links = []): void
    {
        if (\count($links) === 0) {
            return;
        }

        $response = $this->httpClient->delete(
            $this->deleteImagesUri,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [
                    'urls' => $links
                ],
                'verify' => $this->verify,
            ],
        );

        $code = $response->getStatusCode();

        if ($code !== 204) {
            throw new \Exception('Unsuccessful response code ' . $code);
        }
    }

    public function isValidUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        return $this->isSameDomain($url);
    }

    private function isSameDomain(string $url): bool
    {
        try {
            return $this->parseDomain($url) === $this->parseDomain($this->uploadImageUri);
        } catch (\Throwable $_) {
            return false;
        }
    }

    private function parseDomain(string $url): string
    {
        $exploded = \explode('/', $url);
        if (\array_key_exists(2, $exploded) && $exploded[2] !== '') {
            return $exploded[2];
        } else {
            throw new \Exception('Invalid url');
        }
    }

    private function getBufferMimeTypeFile(string $file): string
    {
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->buffer($file);
        $extensionFile = \explode('/', $mimeType);

        if (count($extensionFile) === 2) {
            return $extensionFile[1];
        }

        throw new \Exception('Array length less than 2 indices');

        return '';
    }

    private function restoreImg(): void
    {
        $this->img['buffer'] = '';
        $this->img['imgName'] = '';
        $this->img['extension'] = '';
    }
}
