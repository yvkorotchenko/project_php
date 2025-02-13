<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\DeviceNumberChangeRepository;

class DeviceNumberChangeService
{
    public function __construct(
        private DeviceNumberChangeRepository $deviceNumberChange,
    ) {}

    public function changeDeviceNumber(int $id, string $deviceId): void
    {
        $device = [
            'uid' => $id,
            'deviceId' => $deviceId,
        ];

        $this->deviceNumberChange->create($device);
    }
}