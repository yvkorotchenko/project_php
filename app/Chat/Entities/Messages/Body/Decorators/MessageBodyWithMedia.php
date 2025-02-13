<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body\Decorators;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;
use Illuminate\Support\Facades\Validator;

/**
 * Decarator to add media structure to response body
 */
class MessageBodyWithMedia implements MessageBodyInterface
{
    private MessageBodyInterface $originMessageBody;
    private array $media;

    public function __construct(array $media, MessageBodyInterface $messageBody)
    {
        Validator::validate($media, [
            'url' => 'required|string',
            'mime' => 'required|string',
        ]);
        $this->media = $media;
        $this->originMessageBody = $messageBody;
    }

    public function asArray(): array
    {
        return \array_merge(
            $this->originMessageBody->asArray(),
            ['media' => $this->media]
        );
    }
}
