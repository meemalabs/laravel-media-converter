<?php

namespace Meema\MediaConvert\Contracts;

interface Converter
{
    /**
     * Cancels an active job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function cancelJob(string $id): \Aws\Result;

    /**
     * Creates a new job based on the settings passed.
     *
     * @param array $settings
     * @return \Aws\Result
     */
    public function createJob(array $settings): \Aws\Result;

    /**
     * Gets the job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function getJob(string $id): \Aws\Result;

    /**
     * Lists all of the jobs based on your options provided.
     *
     * @param array $options
     * @return \Aws\Result
     */
    public function listJobs(array $options): \Aws\Result;
}
