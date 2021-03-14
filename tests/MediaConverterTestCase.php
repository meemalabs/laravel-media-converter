<?php

namespace Meema\MediaConverter\Tests;

use Meema\MediaConverter\Providers\MediaConverterServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConverterTestCase extends TestCase
{
    public array $settings = [];

    public array $sizes = [];

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

        $this->settings = json_decode($configFile, true);
    }
}
