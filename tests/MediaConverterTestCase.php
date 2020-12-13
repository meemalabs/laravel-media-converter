<?php

namespace Meema\MediaConverter\Tests;

use Meema\MediaConverter\Providers\MediaConverterServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConverterTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MediaConverterServiceProvider::class];
    }
}
