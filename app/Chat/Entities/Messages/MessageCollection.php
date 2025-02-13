<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages;

use League\CommonMark\Util\ArrayCollection;

class MessageCollection extends ArrayCollection
{
    private const INVALID_ARGUMENT_MESSAGE = 'Element of collection must be of type ' . MessageInterface::class;

    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            if (!$element instanceof MessageInterface) {
                throw new \InvalidArgumentException(self::INVALID_ARGUMENT_MESSAGE);
            }
        }
        parent::__construct($elements);
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof MessageInterface) {
            throw new \InvalidArgumentException(self::INVALID_ARGUMENT_MESSAGE);
        }

        parent::offsetSet($offset, $value);
    }
}
