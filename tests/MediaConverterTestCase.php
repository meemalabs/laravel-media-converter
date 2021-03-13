<?php

namespace Meema\MediaConverter\Tests;

use Meema\MediaConverter\Providers\MediaConverterServiceProvider;
use Orchestra\Testbench\TestCase;

class MediaConverterTestCase extends TestCase
{
    public $settings = [];

    public $sizes = [];

    protected function getPackageProviders($app)
    {
        return [MediaConverterServiceProvider::class];
    }

    public function initializeDotEnv()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }

    public function setDestination()
    {
        $config =  config('media-converter.settings');
        $this->settings = $config;

        $destination = 's3://meema-stage/';

        $this->settings['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $destination.'/thumbnails/';
        $this->settings['OutputGroups'][1]['OutputGroupSettings']['HlsGroupSettings']['Destination'] = $destination.'/hls/';
        $this->settings['OutputGroups'][2]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $destination.'/mp4/';
    }

    public function setFileInput()
    {
        $fileDestination = 's3://meema-stage/test-me.mp4';

        $this->settings['Inputs'][0]['FileInput'] = $fileDestination;
    }

     /**
     * @see https://docs.aws.amazon.com/mediaconvert/latest/ug/cbr-vbr-qvbr.html?icmpid=docs_mediaconvert_errormsg
     */
    public function setQvbrSettings()
    {
        $this->sizes = [
            'width' => 1024,
            'height' => 768,
        ];

        $height = $this->sizes['height'] ?? 721; // default to a medium bitrate

        if ($height > 1080) {
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = 6000000;
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['QvbrSettings']['QvbrQualityLevel'] = 9;

            return;
        }

        if ($height > 720) {
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = 2000000;
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['QvbrSettings']['QvbrQualityLevel'] = 7;

            return;
        }

        if ($height > 480) {
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = 1000000;
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['QvbrSettings']['QvbrQualityLevel'] = 7;

            return;
        }

        if ($height > 360) {
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = 700000;
            $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['QvbrSettings']['QvbrQualityLevel'] = 7;

            return;
        }

        $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['MaxBitrate'] = 350000;
        $this->settings['OutputGroups'][2]['Outputs'][0]['VideoDescription']['CodecSettings']['H264Settings']['QvbrSettings']['QvbrQualityLevel'] = 7;
    }

    /**
     * @see https://aws.amazon.com/blogs/media/create-a-poster-frame-and-thumbnail-images-for-videos-using-aws-elemental-mediaconvert/
     */
    public function setSpriteImageSettings()
    {
        $framesToBeCaptured = $this->getFramesToBeCaptured();
        $totalFrameCount = 500;
        $fps = 24;

        $this->settings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['CodecSettings']['FrameCaptureSettings'] = [
            'FramerateNumerator' => $fps,
            'FramerateDenominator' => ceil($totalFrameCount / $framesToBeCaptured),
            'MaxCaptures' => $framesToBeCaptured,
            'Quality' => 80,
        ];

        // Netflix, Disney Plus and other big streaming platforms use 240px width for the thumbnails within the sprite
        $this->settings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['Width'] = 240;
    }

    protected function getFramesToBeCaptured()
    {
        $totalFrameCount = 500;
        $defaultAmountOfFrames = 250; // technically, we could also name this variable $minAmountOfFrames
        $durationInSeconds = 60;
        $framesToBeCaptured = 500;

        if ($framesToBeCaptured > $defaultAmountOfFrames) {
            return $framesToBeCaptured;
        }

        if ($framesToBeCaptured > $totalFrameCount) {
            return $totalFrameCount;
        }

        return $defaultAmountOfFrames;
    }
}
