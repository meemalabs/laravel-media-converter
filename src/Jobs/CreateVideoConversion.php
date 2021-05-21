<?php

namespace Meema\MediaConverter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Meema\MediaConverter\Facades\MediaConvert;

class CreateVideoConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private array $jobSettings;

    /**
     * @var array
     */
    private int $metaData;

    /**
     * @var array
     */
    private array $tags;

    /**
     * Create a new job instance.
     *
     * @param array $jobSettings
     * @param array $metadata
     * @param array $tags
     */
    public function __construct($jobSettings, $metaData = [], $tags = [])
    {
        $this->jobSettings = $jobSettings;
        $this->metaData = $metaData;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        MediaConvert::createJob($this->jobSettings, $this->metaData, $this->tags);
    }
}
