<?php

namespace App\MtSports\Providers;

use App\MtSports\Services\ClientAuthenticator;
use App\MtSports\Services\MtSportsClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class MtSportsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(ClientAuthenticator::class)
            ->needs('$authUri')
            ->give(env('MTSPORTS_AUTH_URI', ''));
        $this->app
            ->when(ClientAuthenticator::class)
            ->needs('$serviceToken')
            ->give(env('MTSPORTS_AUTH_PUBLIC_TOKEN', ''));

        $this->app
            ->when(MtSportsClient::class)
            ->needs('$baseUri')
            ->give(env('MTSPORTS_BASE_URI', ''));
        $this->app
            ->when(MtSportsClient::class)
            ->needs('$token')
            ->give(env('MTSPORTS_AUTH_PUBLIC_TOKEN', ''));

//        $this->app
//            ->when(MtSportsClient::class)
//            ->needs(ClientInterface::class)
//            ->give(fn () => new Client(['defaults' => ['verify' => false]]));

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
