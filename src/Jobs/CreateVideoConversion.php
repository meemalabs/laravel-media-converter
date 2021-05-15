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
     * @var int
     */
    private int $mediaId;

    /**
     * @var array
     */
    private array $tags;

    /**
     * Create a new job instance.
     *
     * @param $jobSettings
     * @param $tags
     * @param $mediaId
     */
    public function __construct($jobSettings, $tags, $mediaId = null)
    {
        $this->jobSettings = $jobSettings;
        $this->tags = $tags;

        if ($mediaId) {
            $this->mediaId = $mediaId;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $metaData = [];
        $tags = $this->tags;

        if ($this->mediaId) {
            $metaData = ['model_id' => $this->mediaId];
        }

        MediaConvert::createJob($this->jobSettings, $metaData, $tags);
    }
}
