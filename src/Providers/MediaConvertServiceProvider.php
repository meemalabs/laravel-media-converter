<?php

namespace Meema\MediaConvert\Providers;

use Meema\MediaConvert\Facades\MediaConvert;
use Meema\MediaConvert\MediaConvertManager;
use Illuminate\Support\ServiceProvider;

class MediaConvertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('media-convert.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'media-convert');

        $this->registerMediaConvertManager();

        $this->registerAliases();
    }

    /**
     * Registers the Text to speech manager.
     *
     * @return void
     */
    protected function registerMediaConvertManager()
    {
        $this->app->singleton('media-convert', function ($app) {
            return new MediaConvertManager($app);
        });
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias(MediaConvert::class, 'MediaConvert');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'media-convert',
        ];
    }
}
