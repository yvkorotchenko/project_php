<?php

namespace App\MtSports\Entities;

class Game implements \JsonSerializable
{
    public function __construct(
        private int    $id,
        private string $gameCode,
        private string $name,
        private string $nameLocal,
        private string $icon,
        private string $coverPic,
        private int    $sequence,
        private bool   $visible,
        private bool   $canTry,
        private int    $categoryId,
        private int    $companyId,
        private string $companyName,
        private string $subscript = 'NORMAL',
        private string $washChipType = '',
        private int    $modified = 0,
    ) {
    }

    public function jsonSerialize()
    {
        return [
            'id'           => $this->id,
            'identity'     => $this->name,
            'gameCode'     => $this->gameCode,
            'name'         => $this->name,
            'nameLocal'    => $this->nameLocal,
            'icon'         => $this->icon,
            'coverPic'     => $this->coverPic,
            'seq'          => $this->sequence,
            'isOpen'       => $this->visible ? 'Y' : 'N',
            'isTry'        => $this->canTry ? 'Y' : 'N',
            'categoryId'   => $this->categoryId,
            'companyId'    => $this->companyId,
            'companyName'  => $this->companyName,
            'subscript'    => $this->subscript,
            'washChipType' => $this->washChipType,
            'modified'     => $this->modified,
        ];
    }
}
