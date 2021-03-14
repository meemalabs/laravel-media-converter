<?php

use Meema\MediaConverter\Facades\MediaConvert;

uses(Meema\MediaConverter\Tests\MediaConverterTestCase::class);

beforeEach(function () {
    $this->initializeDotEnv();
    $this->initializeSettings();
});

it('can successfully initialize settings', function () {
    $this->assertTrue(is_array($this->settings));
});

it('can successfully create a job', function () {
    $response = MediaConvert::createJob($this->settings);

    $this->assertEquals($response['@metadata']['statusCode'], 201);
});

it('can successfully get a job', function () {
    // just a hardcoded job ID retrieved from one of the AWS MediaConvert jobs
    $jobId = '1615614565083-g1cgjm';

    $response = MediaConvert::getJob($jobId);

    $this->assertEquals($response['@metadata']['statusCode'], 200);
    $this->assertEquals($response['Job']['Id'], $jobId);
});

 it('can successfully cancel a job', function () {
     $job = MediaConvert::createJob($this->settings, []);

     $response = MediaConvert::cancelJob($job['Job']['Id']);

     $this->assertEquals($response['@metadata']['statusCode'], 202);
 });

 it('can successfully list jobs', function () {
     $response = MediaConvert::listJobs([]);

     $this->assertEquals($response['@metadata']['statusCode'], 200);
     $this->assertTrue(count($response) > 0);
 });
