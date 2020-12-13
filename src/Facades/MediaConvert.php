<?php

namespace Meema\MediaConverter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aws\MediaConvert\MediaConvertClient getClient()
 * @method static \Aws\Result cancelJob(string $id)
 * @method static \Aws\Result createJob(array $settings, array $metaData = [], int $priority = 0)
 * @method static \Aws\Result getJob(string $id)
 * @method static \Aws\Result listJobs(array $options)
 */
class MediaConvert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'media-converter';
    }
}
