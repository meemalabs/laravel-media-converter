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

    private array $jobSettings;

    private int $mediaId;

    /**
     * Create a new job instance.
     *
     * @param $jobSettings
     * @param $mediaId
     */
    public function __construct($jobSettings, $mediaId = null)
    {
        $this->jobSettings = $jobSettings;

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

        if ($this->mediaId) {
            $metaData = ['model_id' => $this->mediaId];
        }

        MediaConvert::createJob($this->jobSettings, $metaData);
    }
}
