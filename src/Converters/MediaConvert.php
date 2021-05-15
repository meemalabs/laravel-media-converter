<?php

namespace Meema\MediaConverter\Converters;

use Aws\Credentials\Credentials;
use Aws\MediaConvert\MediaConvertClient;
use Meema\MediaConverter\Contracts\Converter;

class MediaConvert implements Converter
{
    /**
     * Client instance of AWS MediaConvert.
     *
     * @var \Aws\MediaConvert\MediaConvertClient
     */
    protected $client;

    /**
     * The MediaConvert job's settings.
     *
     * @var array
     */
    public array $jobSettings;

    /**
     * Construct converter.
     *
     * @param \Aws\MediaConvert\MediaConvertClient $client
     */
    public function __construct(MediaConvertClient $client)
    {
        $config = config('media-converter');

        $this->jobSettings = (new $config['job_settings'])::get();

        $this->client = new MediaConvertClient([
            'version' => $config['version'],
            'region' => $config['region'],
            'credentials' => new Credentials($config['credentials']['key'], $config['credentials']['secret']),
            'endpoint' => $config['url'],
        ]);
    }

    /**
     * Get the MediaConvert Client.
     *
     * @return \Aws\MediaConvert\MediaConvertClient
     */
    public function getClient(): MediaConvertClient
    {
        return $this->client;
    }

    /**
     * Sets the path of the file input.
     *
     * @param string $path - represents the S3 path, e.g path/to/file.mp4
     * @param string|null $bucket - reference to the S3 Bucket name. Defaults to config value.
     * @return \Meema\MediaConverter\Converters\MediaConvert
     */
    public function path(string $path, $bucket = null): MediaConvert
    {
        $this->setFileInput($path, $bucket);

        return $this;
    }

    /**
     * Generates a web optimized MP4.
     *
     * @return \Meema\MediaConverter\Converters\MediaConvert
     */
    public function optimizeForWeb(): MediaConvert
    {
        // At the moment, we can simply return $this, because this method is simply used for readability purposes.
        // The "default job" has all the proper settings already to generate a web optimized mp4.
        return $this;
    }

    /**
     * Sets the settings required to generate the proper amount of thumbnails.
     *
     * @param int $framerateNumerator
     * @param int $framerateDenominator
     * @param int $maxCaptures
     * @param int|null $width
     * @param string|null $nameModifier
     * @param int $imageQuality
     * @return \Meema\MediaConverter\Converters\MediaConvert
     */
    public function withThumbnails(int $framerateNumerator, int $framerateDenominator, int $maxCaptures, $width = null, $nameModifier = null, $imageQuality = 80): MediaConvert
    {
        $this->jobSettings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['CodecSettings']['FrameCaptureSettings'] = [
            'FramerateNumerator' => $framerateNumerator,
            'FramerateDenominator' => $framerateDenominator,
            'MaxCaptures' => $maxCaptures,
            'Quality' => $imageQuality,
        ];

        if ($width) {
            $this->jobSettings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['Width'] = $width;
        }

        if ($nameModifier) {
            $this->jobSettings['OutputGroups'][0]['Outputs'][0]['NameModifier'] = $nameModifier;
        }

        return $this;
    }

    /**
     * Sets the S3 path & executes the job.
     *
     * @param string $s3Path
     * @param string|null $s3bucket
     * @return \Meema\MediaConverter\Converters\MediaConvert
     */
    public function saveTo(string $s3Path, $s3bucket = null): MediaConvert
    {
        $destination = 's3://'.($s3bucket ?? config('filesystems.disks.s3.bucket'));

        $this->jobSettings['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $destination.'/thumbnails/';
        $this->jobSettings['OutputGroups'][1]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $destination.'/mp4/';

        return $this;
    }

    /**
     * Cancels an active job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function cancelJob(string $id)
    {
        return $this->client->cancelJob([
            'Id' => $id,
        ]);
    }

    /**
     * Creates a new job based on the settings passed.
     *
     * @param array $settings
     * @param array $metaData
     * @param int $priority
     * @return \Aws\Result
     */
    public function createJob(array $settings, array $metaData = [], array $tags = [], int $priority = 0)
    {
        return $this->client->createJob([
            'Role' => config('media-converter.iam_arn'),
            'Settings' => $settings,
            'Queue' => config('media-converter.queue_arn'),
            'UserMetadata' => $metaData,
            'Tags' => $tags,
            'StatusUpdateInterval' => $this->getStatusUpdateInterval(),
            'Priority' => $priority,
        ]);
    }

    /**
     * Gets the job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function getJob(string $id)
    {
        return $this->client->getJob([
            'Id' => $id,
        ]);
    }

    /**
     * Lists all of the jobs based on your options provided.
     *
     * @param array $options
     * @return \Aws\Result
     */
    public function listJobs(array $options)
    {
        return $this->client->listJobs($options);
    }

    protected function getStatusUpdateInterval(): string
    {
        $webhookInterval = config('media-converter.webhook_interval');
        $allowedValues = [10, 12, 15, 20, 30, 60, 120, 180, 240, 300, 360, 420, 480, 540, 600];

        if (in_array($webhookInterval, [$allowedValues])) {
            return 'SECONDS_'.$webhookInterval;
        }

        return 'SECONDS_60'; // gracefully default to this value, in case the config value is missing or incorrect
    }

    /**
     * Sets the S3 input file path.
     *
     * @param string $path
     * @param string|null $bucket
     */
    protected function setFileInput(string $path, $bucket = null)
    {
        $fileInput = 's3://'.($bucket ?? config('filesystems.disks.s3.bucket')).'/'.$path;

        $this->jobSettings['Inputs'][0]['FileInput'] = $fileInput;
    }
}
