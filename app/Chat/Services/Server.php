<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Services\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

class Server
{
    private const
        BUSSINESS_WORKER_NAME = 'BUSSINES_WORKER',
        GATEWAY_PROTOCOL = 'Websocket',
        GATEWAY_NAME = 'GATEWAY',
        GATEWAY_PING_INTERVAL = 10
    ;

    private BusinessWorker $bussinessWorker;
    private Gateway $gatewayWorker;
    private Register $registerWorker;

    private string $workersAddress;
    private int $workersCount;
    private int $workerPort;
    private int $gatewayPort;
    private int $gatewayStartPort;
    private string $pemFileCertificatePath;
    private string $keyFileCertificatePath;

    public function __construct(
        string $workersAddress,
        int $workersCount,
        int $workerPort,
        int $gatewayPort,
        int $gatewayStartPort,
        string $pemFileCertificatePath,
        string $keyFileCertificatePath,
        BusinessWorker $businessWorker
    ) {
        $this->workersAddress = $workersAddress;
        $this->workersCount = $workersCount;
        $this->workerPort = $workerPort;
        $this->gatewayPort = $gatewayPort;
        $this->gatewayStartPort = $gatewayStartPort;
        $this->bussinessWorker = $businessWorker;
        $this->pemFileCertificatePath = $pemFileCertificatePath;
        $this->keyFileCertificatePath = $keyFileCertificatePath;
    }

    public function serve(): void
    {
         if (
            $this->workerPort === $this->gatewayStartPort
            || $this->gatewayStartPort === $this->gatewayPort
            || $this->workerPort === $this->gatewayPort
        ) {
            throw new \Exception('Workers port and gateway port can not be the same');
        }

        $this->bussinessWorker->name = self::BUSSINESS_WORKER_NAME;
        $this->bussinessWorker->count = $this->workersCount;
        $this->bussinessWorker->registerAddress = $this->workersAddress . ':' . $this->workerPort;

        $this->gatewayWorker = $this->getGateway(
            self::GATEWAY_PROTOCOL . '://0.0.0.0:' . (string)$this->gatewayPort,
            self::GATEWAY_NAME,
            $this->workersCount,
            $this->workersAddress,
            $this->gatewayStartPort,
            self::GATEWAY_PING_INTERVAL,
            $this->getGatewayPingData(),
            $this->workersAddress . ':' . $this->workerPort,
            [
                'ssl' => [
                    'local_cert' => $this->pemFileCertificatePath,
                    'local_pk' => $this->keyFileCertificatePath,
                    'verify_peer' => false,
                ]
            ]

        );

        $this->registerWorker = $this->getRegister('text://0.0.0.0:' . $this->workerPort);
        Worker::runAll();
    }

    private function getGateway(
        string $socketName,
        string $name,
        int $workersCount,
        string $lanIp,
        int $startPort,
        int $pingInterval,
        string $pingData,
        string $registerAddress,
        array $context
    ): Gateway {
        $gateway = new Gateway($socketName, $context);
        $gateway->name = $name;
        $gateway->count = $workersCount;
        $gateway->lanIp = $lanIp;
        $gateway->startPort = $startPort;
        $gateway->pingInterval = $pingInterval;
        $gateway->pingData = $pingData;
        $gateway->registerAddress = $registerAddress;

        return $gateway;
    }

    private function getRegister(string $socketName): Register
    {
        return new Register($socketName);
    }

    private function getGatewayPingData(): string
    {
        return \json_encode(['type' => 'ping']);
    }
}
