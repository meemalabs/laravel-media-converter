<?php

namespace Meema\MediaConverter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Aws\MediaConvert\MediaConvertClient getClient()
 * @method static \Meema\MediaConverter\Converters\MediaConvert path(string $s3Path, $s3bucket = null)
 * @method static \Meema\MediaConverter\Converters\MediaConvert optimizeForWeb()
 * @method static \Meema\MediaConverter\Converters\MediaConvert withThumbnails(int $framerateNumerator, int $framerateDenominator, int $maxCaptures, $width = null, $nameModifier = null, $imageQuality = 80)
 * @method static \Aws\Result saveTo(string $s3Path, $s3bucket = null)
 * @method static \Aws\Result cancelJob(string $id)
 * @method static \Aws\Result createJob(array $settings, array $metaData = [], array $tags = [], int $priority = 0)
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
