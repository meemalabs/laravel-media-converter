<?php

namespace Meema\MediaConverter\Tests;

use Aws\MediaConvert\MediaConvertClient;

class MediaConverterTest extends MediaConvertTestCase
{
    /**
     * @var \Aws\MediaConvert\MediaConvertClient
     */
    protected $client;

    /**
     * Setup client and results.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getMockBuilder(MediaConvertClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /** @test */
    public function it_can_successfully_create_a_job()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_a_job()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_cancel_a_job()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_list_jobs()
    {
        $this->markTestIncomplete();
    }
}
