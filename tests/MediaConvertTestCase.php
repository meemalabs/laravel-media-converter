<?php

namespace Meema\MediaConverter\Tests;

use Meema\MediaConverter\Providers\MediaConvertServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConvertTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MediaConvertServiceProvider::class];
    }
}
