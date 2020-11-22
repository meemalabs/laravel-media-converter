<?php

namespace Meema\MediaConvert\Tests;

use Meema\MediaConvert\Providers\MediaConvertServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConvertTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MediaConvertServiceProvider::class];
    }
}
