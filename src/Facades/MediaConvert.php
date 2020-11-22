<?php

namespace Meema\MediaConvert\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void convert($data, array $options)
 * @method static \Meema\MediaConvert\Contracts\Converter cancelJob(string $id)
 * @method static \Meema\MediaConvert\Contracts\Converter createJob(string $settings)
 * @method static \Meema\MediaConvert\Contracts\Converter getJob(string $id)
 * @method static \Meema\MediaConvert\Contracts\Converter listJobs(array $options)
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
        return 'media-convert';
    }
}
