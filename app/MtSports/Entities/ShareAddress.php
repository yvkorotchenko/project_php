<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class ShareAddress implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $landPageUrl,
        private string $qrCodeUrl,
        private string $fbContent,
        private string $shareUrl,
        private string $shareQrCodeUrl
    ) {}

    public function jsonSerialize()
    {
        return [
            'id'             => $this->id,
            'landPageUrl'    => $this->landPageUrl,
            'qrCodeUrl'      => $this->qrCodeUrl,
            'fbContent'      => $this->fbContent,
            'shareUrl'       => $this->shareUrl,
            'shareQrCodeUrl' => $this->shareQrCodeUrl,
        ];
    }
}
