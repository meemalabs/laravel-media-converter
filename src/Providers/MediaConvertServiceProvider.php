<?php

namespace Meema\MediaConvert\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Meema\MediaConvert\Facades\MediaConvert;
use Meema\MediaConvert\Http\Middleware\VerifySignature;
use Meema\MediaConvert\MediaConvertManager;

class MediaConvertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('media-convert.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('verify-signature', VerifySignature::class);
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
