<?php

use Meema\MediaConverter\Facades\MediaConvert;

uses(Meema\MediaConverter\Tests\MediaConverterTestCase::class);

beforeEach(function () {
    $this->initializeDotEnv();
    $this->initializeSettings();
});

it('can successfully initialize settings', function () {
    $converter = MediaConvert::path('my-video.mkv', 'test-bucket');
    $this->assertTrue(is_array($converter->jobSettings));
});

it('can successfully set a path', function () {
    $converter = MediaConvert::path('my-video.mkv', 'test-bucket');
    $fileInput = 's3://test-bucket/my-video.mkv';
    $this->assertEquals($converter->jobSettings['Inputs'][0]['FileInput'], $fileInput);
});

it('can successfully web optimize a video', function () {
    $converter = MediaConvert::path('my-video.mkv', 'test-bucket')->optimizeForWeb();
    // todo: we could likely improve this by running an actual test and compare the output file
    $this->assertEquals($converter->jobSettings['OutputGroups'][1]['CustomName'], 'MP4');
});

it('can successfully set the settings to generate thumbnails', function () {
    $framerateNumerator = 1;
    $framerateDenominator = 2;
    $maxCaptures = 3;
    $imageQuality = 75;
    $width = 100;
    $nameModifier = '.$w$x$h$';

    $converter = MediaConvert::path('my-video.mkv', 'test-bucket')
        ->optimizeForWeb()
        ->withThumbnails($framerateNumerator, $framerateDenominator, $maxCaptures, $width, $nameModifier, $imageQuality);

    $this->assertEquals($converter->jobSettings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['CodecSettings']['FrameCaptureSettings'], [
        'FramerateNumerator' => $framerateNumerator,
        'FramerateDenominator' => $framerateDenominator,
        'MaxCaptures' => $maxCaptures,
        'Quality' => $imageQuality,
    ]);

    $this->assertEquals($converter->jobSettings['OutputGroups'][0]['Outputs'][0]['VideoDescription']['Width'], $width);
    $this->assertEquals($converter->jobSettings['OutputGroups'][0]['Outputs'][0]['NameModifier'], $nameModifier);
});

it('can successfully update the output path', function () {
    $inputName = 'my-video.mkv';
    $outputName = 'my-video.mp4';
    $bucket = 'test-bucket';

    $converter = MediaConvert::path($inputName, 'test-bucket')
        ->optimizeForWeb()
        ->withThumbnails(1, 2, 3, 100)
        ->saveTo($outputName, $bucket);

    $destination = 's3://test-bucket'.'/'.$outputName;

    $this->assertEquals($converter->jobSettings['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'], $destination.'/thumbnails/');
    $this->assertEquals($converter->jobSettings['OutputGroups'][1]['OutputGroupSettings']['FileGroupSettings']['Destination'], $destination.'/mp4/');
});

it('can successfully create a job', function () {
    $response = MediaConvert::createJob($this->jobSettings);

    $this->assertEquals($response['@metadata']['statusCode'], 201);
});

it('can successfully create a job chained', function () {
    $inputName = 'my-video.mkv';
    $outputName = 'my-video.mp4';
    $bucket = 'test-bucket';

    $response = MediaConvert::path($inputName, 'test-bucket')
        ->optimizeForWeb()
        ->withThumbnails(1, 2, 3, 100)
        ->saveTo($outputName, $bucket)
        ->createJob();

    $this->assertEquals($response['@metadata']['statusCode'], 201);
});

it('can successfully get a jobs', function () {
    // just a hardcoded job ID retrieved from one of the AWS MediaConvert jobs
    $jobId = '1627096589458-nqj4pr';

    $response = MediaConvert::getJob($jobId);

    $this->assertEquals($response['@metadata']['statusCode'], 200);
    $this->assertEquals($response['Job']['Id'], $jobId);
});

it('can successfully cancel a job', function () {
    $job = MediaConvert::createJob($this->jobSettings, []);

    $response = MediaConvert::cancelJob($job['Job']['Id']);

    $this->assertEquals($response['@metadata']['statusCode'], 202);
});

it('can successfully list jobs', function () {
    $response = MediaConvert::listJobs([]);

    $this->assertEquals($response['@metadata']['statusCode'], 200);
    $this->assertTrue(count($response) > 0);
});
