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
    public function cancelJob(string $id);

    /**
     * Creates a new job based on the settings passed.
     *
     * @param array $settings
     * @return \Aws\Result
     */
    public function createJob(array $settings);

    /**
     * Gets the job.
     *
     * @param string $id
     * @return \Aws\Result
     */
    public function getJob(string $id);

    /**
     * Lists all of the jobs based on your options provided.
     *
     * @param array $options
     * @return \Aws\Result
     */
    public function listJobs(array $options);
}
