<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class SingInConfiguration implements \JsonSerializable
{
    private array $singInConfiguration;

    public function __construct(array $singInConfiguration)
    {
        $singInConfiguration['signInActivity']['reward'] = \json_encode(
            $singInConfiguration['signInActivity']['reward'],
            JSON_THROW_ON_ERROR,
            512,
        );

        $this->singInConfiguration = $singInConfiguration;
    }

    public function jsonSerialize()
    {
        return $this->singInConfiguration;
    }
}