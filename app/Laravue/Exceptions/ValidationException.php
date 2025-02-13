<?php


namespace App\Laravue\Exceptions;


use Illuminate\Support\MessageBag;
use Throwable;

class ValidationException extends \Exception
{
    private MessageBag $messages;

    public function __construct(
        $message,
        MessageBag $validationErrorMessages,
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->messages = $validationErrorMessages;
    }

    public function errorMessages(): MessageBag
    {
        return $this->messages;
    }

    public function errorMessagesStringArray(): array
    {
        $result = [];
        foreach ($this->errorMessages()->all() as $errorMessage) {
            $result[] = $errorMessage;
        }

        return $result;
    }
}