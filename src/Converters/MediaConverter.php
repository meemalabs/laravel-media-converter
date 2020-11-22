<?php

namespace Meema\MediaConvert\Converters;

use Aws\Exception\AwsException;
use Aws\MediaConvert\MediaConvertClient;
use Meema\MediaConvert\Contracts\Converter;

class MediaConverter implements Converter
{
    /**
     * Client instance of Polly.
     *
     * @var \Aws\Polly\PollyClient
     */
    protected $client;

    /**
     * Construct converter.
     *
     * @param \Aws\MediaConvert\MediaConvertClient $client
     */
    public function __construct(MediaConvertClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the Polly Client.
     *
     * @return \Aws\MediaConvert\MediaConvertClient
     */
    public function getClient(): MediaConvertClient
    {
        return $this->client;
    }

    /**
     * Cancels an active job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function cancelJob(string $id): \Aws\Result
    {
        try {
            return $this->client->cancelJob([
                'Id' => $id,
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
        }
    }

    /**
     * Creates a new job based on the settings passed.
     *
     * @param array $settings
     * @return \Aws\Result
     */
    public function createJob(array $settings): \Aws\Result
    {
        try {
            return $this->client->createJob([
                "Role" => "IAM_ROLE_ARN",
                "Settings" => $settings, // JobSettings structure
                "Queue" => "JOB_QUEUE_ARN",
                "UserMetadata" => [
                    "Customer" => "Amazon"
                ],
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
        }
    }

    /**
     * Gets the job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function getJob(string $id): \Aws\Result
    {
        try {
            return $this->client->getJob([
                'Id' => $id,
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
        }
    }

    /**
     * Lists all of the jobs based on your options provided.
     *
     * @param array $options
     * @return \Aws\Result
     */
    public function listJobs(array $options): \Aws\Result
    {
        try {
            return $this->client->listJobs($options);
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
        }
    }
}
