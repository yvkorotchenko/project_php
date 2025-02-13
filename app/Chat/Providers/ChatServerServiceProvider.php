<?php

namespace App\Chat\Providers;

use App\Chat\Services\Server;
use App\Chat\WorkerEventsHandlers\Interfaces\OnCloseHandlerInterface;
use App\Chat\WorkerEventsHandlers\Interfaces\OnMessageHandlerInterface;
use App\Chat\WorkerEventsHandlers\OnCloseHandler;
use App\Chat\WorkerEventsHandlers\OnMessageHandler;
use Illuminate\Support\ServiceProvider;

class ChatServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app
            ->when(Server::class)
            ->needs('$workersAddress')
            ->give(env('APP_CHAT_WORKERS_ADDRESS', ''));
        $this->app
            ->when(Server::class)
            ->needs('$workersCount')
            ->give(env('APP_CHAT_WORKERS_COUNT', 0));
        $this->app
            ->when(Server::class)
            ->needs('$workerPort')
            ->give(env('APP_CHAT_WORKERS_PORT', 0));
        $this->app
            ->when(Server::class)
            ->needs('$gatewayPort')
            ->give(env('APP_CHAT_GATEWAY_PORT', 0));
        $this->app
            ->when(Server::class)
            ->needs('$gatewayStartPort')
            ->give(env('APP_CHAT_GATEWAY_PORT_START', 0));

        $this->app
            ->when(Server::class)
            ->needs('$pemFileCertificatePath')
            ->give(env('APP_CHAT_CERTIFICATE_PEM_FILE_PATH', ''));

        $this->app
            ->when(Server::class)
            ->needs('$keyFileCertificatePath')
            ->give(env('APP_CHAT_CERTIFICATE_KEY_FILE_PATH', ''));

        $this->app->bind(OnMessageHandlerInterface::class, OnMessageHandler::class);
        $this->app->bind(OnCloseHandlerInterface::class, OnCloseHandler::class);
    }
}
