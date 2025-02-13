<?php

namespace App\Providers;

use App\Chat\Services\AnonymousChatUserService;
use Illuminate\Support\ServiceProvider;
use App\Laravue\Services\StorageFactorysService;
use App\Laravue\Services\StaticStorageService;
use App\Laravue\Services\BannerService;
use App\Laravue\Services\FeedbackServices;
use App\Laravue\Services\RewardsManagementService;
use App\Laravue\Services\SevenDaysWelfareService;
use App\Laravue\Services\TaskManagementServices;
use App\Laravue\Services\AvatarService;
use App\Chat\Http\Controllers\MediaController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(StaticStorageService::class)
            ->needs('$uploadImageUri')
            ->give(env('STATIC_FILES_UPLOAD_URI', ''));

        $this->app
            ->when(StaticStorageService::class)
            ->needs('$token')
            ->give(env('STATIC_FILES_TOKEN', ''));

        $this->app
            ->when(StaticStorageService::class)
            ->needs('$deleteImagesUri')
            ->give(env('STATIC_FILES_DELETE_IMAGES_URI', ''));

        $this->app
            ->when(StaticStorageService::class)
            ->needs('$verify')
            ->give(function() {
                return (env('APP_ENV') === 'local') ? false : true;
            });

        $this->app
            ->when(BannerService::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_BANNER_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
        });

        $this->app
            ->when(FeedbackServices::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_DELETE_DIR_FEEDBACKS_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
        });

        $this->app
            ->when(RewardsManagementService::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_REWARDS_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
        });

        $this->app
            ->when(SevenDaysWelfareService::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_WELFARES_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
        });

        $this->app
            ->when(TaskManagementServices::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_TASKMANAGEMENT_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
            });

        $this->app
            ->when(AnonymousChatUserService::class)
            ->needs('$privateKeyRelativePath')
            ->give(env('APP_CHAT_ANONYMOUS_USER_PRIVATE_KEY_PATH', ''));

        $this->app
            ->when(AnonymousChatUserService::class)
            ->needs('$privateKeyPassword')
            ->give(env('APP_CHAT_ANONYMOUS_USER_PRIVATE_KEY_PASSWORD', ''));

        $this->app
            ->when(AnonymousChatUserService::class)
            ->needs('$secretPhrase')
            ->give(env('APP_CHAT_SECRET_PHRASE', ''));
        // UPLOAD AVATARS
        $this->app
            ->when(AvatarService::class)
            ->needs(StaticStorageService::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_ICONS_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
            });

        $this->app
            ->when(MediaController::class)
            ->give(function() {
                return (new StorageFactorysService())->make(
                    env('STATIC_FILES_TOKEN', ''),
                    env('STATIC_FILES_UPLOAD_CHAT_URI', ''),
                    env('STATIC_FILES_DELETE_IMAGES_URI', ''),
                    (env('APP_ENV') === 'local') ? false : true,
                );
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
