<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class Game implements \JsonSerializable
{
    public function __construct(
        public int          $id,
        public string       $gameCode,
        public string       $name,
        public string       $nameLocal,
        public string       $icon,
        public string       $coverPic,
        public int          $sequence,
        public bool         $visible,
        public bool         $canTry,
        public GameCategory $category,
        public Platform     $platform,
    ) {
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'gameCode' => $this->gameCode,
            'name' => $this->name,
            'nameLocal' => $this->nameLocal,
            'icon' => $this->icon,
            'coverPic' => $this->coverPic,
            'sequence' => $this->sequence,
            'visible' => $this->visible,
            'canTry' => $this->canTry,
            'category' => $this->category->jsonSerialize(),
            'platform' => $this->platform->jsonSerialize(),
        ];
    }
}
