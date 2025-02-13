<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class MailingListDeleteEntities implements \JsonSerializable
{
    public function __construct(
        public array $data
    ) {}

    public function jsonSerialize()
    {
        $sendData = [];
        foreach($this->data as $val){
            $sendData[] = new MailingListDeleteItem(
                $val['messageId'], $val['uid']
            );
        }
        return $sendData;
    }
}


class MailingListDeleteItem implements \JsonSerializable
{
    public function __construct(
        public int $messageId,
        public int $uid,
    ) {}

    public function jsonSerialize()
    {
        return 
        [
            'messageId' => $this->messageId,
            'uid'       => $this->uid,
        ];
    }
}
