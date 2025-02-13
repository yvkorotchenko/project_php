<?php


namespace App\Chat\Entities\Enum;


class MessageTypeEnum
{
    const
        MESSAGE_TYPE_CLIENT_MESSAGE = 'message',
        MESSAGE_TYPE_CLIENT_CONNECT = 'clientConnect',
        MESSAGE_TYPE_OPERATOR_CONNECT = 'operatorConnect',
        MESSAGE_TYPE_OPERATOR_MESSAGE = 'operatorMessage',
        MESSAGE_TYPE_CLIENT_ASSIGNED = 'clientAssigned',
        MESSAGE_TYPE_ASSIGN_CLIENT_TO_OPERATOR = 'assignClient',
        MESSAGE_TYPE_CLIENT_DISCONECTED = 'clientDisconected',
        MESSAGE_TYPE_OPERATOR_DISCONECTED = 'operatorDisconected',
        MESSAGE_TYPE_OPERATOR_CLOSE_CHAT = 'operatorCloseChat',
        MESSAGE_TYPE_PONG = 'pong'
    ;

    private string $value;
    private \ReflectionClass $reflection;

    public function __construct(string $messageType)
    {
        if (!\in_array($messageType, $this->list())) {
            throw new \InvalidArgumentException('Message type does not exists in message enum');
        }

        $this->value = $messageType;
        $this->reflection = new \ReflectionClass(self::class);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function list(): array
    {
        return $this->reflection->getConstants();
    }
}
