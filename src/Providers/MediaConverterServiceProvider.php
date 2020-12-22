<?php

namespace Meema\MediaConverter\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Meema\MediaConverter\Facades\MediaConvert;
use Meema\MediaConverter\Http\Middleware\VerifySignature;
use Meema\MediaConverter\MediaConverterManager;

class MediaConverterServiceProvider extends ServiceProvider
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
                __DIR__.'/../../config/config.php' => config_path('media-converter.php'),
            ], 'config');

            if (config('media-converter.track_media_conversions') && ! class_exists('CreateMediaConversionsTable')) {
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_media_conversions_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_media_conversions_table.php'),
                ], 'migrations');
            }
        }

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $router = $this->app->make(Router::class);

        if (! in_array('verify-signature', $router->getMiddleware())) {
            $router->aliasMiddleware('verify-signature', VerifySignature::class);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'media-converter');

        $this->registerMediaConverterManager();

        $this->registerAliases();
    }

    /**
     * Registers the Text to speech manager.
     *
     * @return void
     */
    protected function registerMediaConverterManager()
    {
        $this->app->singleton('media-converter', function ($app) {
            return new MediaConverterManager($app);
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
            'media-converter',
        ];
    }
}
