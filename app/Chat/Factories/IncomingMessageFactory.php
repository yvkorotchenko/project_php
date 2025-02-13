<?php


namespace App\Chat\Factories;


use App\Chat\Entities\Messages\IncomingMessage;
use Illuminate\Support\Facades\Validator;

class IncomingMessageFactory
{
    public function createByRawData(string $participantId, string $message)
    {
        $messageArray = $this->decodeStringToArray($message);
        Validator::validate($messageArray, [
            'type' => 'required|string',
            'content' => 'string',
            'media' => 'array|nullable',
            'clientId' => 'string',
        ]);
        if (\array_key_exists('media', $messageArray) && null !== $messageArray['media']) {
            Validator::validate($messageArray['media'], [
                'url' => 'required|string|min:4',
                'mime' => 'required|string|min:3',
            ]);
        }
        if (\array_key_exists('content', $messageArray)) {
            $messageArray['content'] = \strip_tags($messageArray['content']);
        }

        return new IncomingMessage($participantId, $messageArray);
    }

    private function decodeStringToArray(string $rawMessage): array
    {
        if ('' === $rawMessage) {
            throw new \InvalidArgumentException('Can not create message from empty string');
        }
        $messageArray = \json_decode($rawMessage, true, 512, JSON_THROW_ON_ERROR);
        if (!\is_array($messageArray) || empty($messageArray)) {
            throw new \InvalidArgumentException('Invalid message');
        }
        if (!\array_key_exists('type', $messageArray)) {
            throw new \LogicException('Undefined type of the message');
        }

        return $messageArray;
    }
}