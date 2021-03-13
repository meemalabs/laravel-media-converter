<?php

uses(Meema\MediaConverter\Tests\MediaConverterTestCase::class);

beforeEach(function () {
    $this->initializeDotEnv();
    $this->setDestination();
    $this->setFileInput();
    $this->setQvbrSettings();
    $this->setSpriteImageSettings();
});

it('it can successfully initialize settings', function () {
    $this->assertTrue(is_array($this->settings));
});

it('it can successfully create a job', function () {
   $response = Meema\MediaConverter\Facades\MediaConvert::createJob($this->settings, []);

   $this->assertEquals($response['@metadata']['statusCode'], 201);
});

it('it can successfully get a job', function () {
    // Just a fixed Id fetched from aws media convert jobs
    $jobId = '1615614565083-g1cgjm';

    $response = Meema\MediaConverter\Facades\MediaConvert::getJob($jobId);

    $this->assertEquals($response['@metadata']['statusCode'], 200);
    $this->assertEquals($response['Job']['Id'], $jobId);
 });

 it('it can successfully cancel a job', function () {
    $job = Meema\MediaConverter\Facades\MediaConvert::createJob($this->settings, []);

    $response = Meema\MediaConverter\Facades\MediaConvert::cancelJob($job['Job']['Id']);

    $this->assertEquals($response['@metadata']['statusCode'], 202);
 });

 it('it can successfully list jobs', function () {
    $response = Meema\MediaConverter\Facades\MediaConvert::listJobs([]);

    $this->assertEquals($response['@metadata']['statusCode'], 200);
    $this->assertTrue(count($response) > 0);
 });
