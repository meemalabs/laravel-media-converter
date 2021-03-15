<?php

namespace Meema\MediaConverter\Tests;

use Illuminate\Support\Facades\Config;
use Meema\MediaConverter\Providers\MediaConverterServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConverterTestCase extends TestCase
{
    public $settings = [];

    public $sizes = [];

    protected function getPackageProviders($app): array
    {
        return [MediaConverterServiceProvider::class];
    }

    public function initializeDotEnv()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }

    public function initializeSettings()
    {
        $configFile = file_get_contents(__DIR__.'/config/job.json');

        // let's make sure these config values are set
        Config::set('media-converter.credentials.key', env('AWS_ACCESS_KEY_ID'));
        Config::set('media-converter.credentials.secret', env('AWS_SECRET_ACCESS_KEY'));
        Config::set('media-converter.url', env('AWS_MEDIACONVERT_ACCOUNT_URL'));
        Config::set('media-converter.iam_arn', env('AWS_IAM_ARN'));
        Config::set('media-converter.queue_arn', env('AWS_QUEUE_ARN'));

        $this->settings = json_decode($configFile, true);
    }
}
