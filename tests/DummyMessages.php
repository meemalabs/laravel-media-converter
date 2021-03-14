<?php

// this file is currently only used to analyze the data structure of MediaConvert's responses

namespace Meema\MediaConverter\Tests;

class DummyMessages
{
    protected array $headers = [
        'Host' => 'meema-api.test:443',
        'x-forwarded-for' => '52.95.24.96',
        'x-forwarded-proto' => 'https',
        'Connection' => 'upgrade',
        'Content-Length' => '1520',
        'x-amz-sns-message-type' => 'Notification',
        'x-amz-sns-message-id' => '37210acf-236b-59e5-bbb8-52f4f85cd7b3',
        'x-amz-sns-topic-arn' => 'arn:aws:sns:us-east-2:954236614099:MediaConvertJobUpdate',
        'x-amz-sns-subscription-arn' => 'arn:aws:sns:us-east-2:954236614099:MediaConvertJobUpdate:186ceb0f-9602-4358-8773-168ff19a84e2',
        'Content-Type' => 'text/plain; charset=UTF-8',
        'User-Agent' => 'Amazon Simple Notification Service Agent',
        'Accept-Encoding' => 'gzip, deflate',
        'x-expose-request-id' => '5fc1d8f82dd22',
        'upgrade-insecure-requests' => '1',
        'x-exposed-by' => 'Expose unreleased',
        'x-original-host' => 'meema-api.sharedwithexpose.com',
    ];

    protected array $progressing = [
        'version' => '0',
        'id' => 'c5aebd1c-2c6a-ace0-3ab3-9945f986b4c4',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T04:58:31Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606539508649-2fu0hb',
        ],
        'detail' => [
            'timestamp' => 1606539511533,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606539508649-2fu0hb',
            'status' => 'PROGRESSING',
            'userMetadata' => [
                'model_id' => '21',
                'hello' => 'world',
            ],
        ],
    ];

    protected array $statusUpdate = [
        'version' => '0',
        'id' => '810b18d6-8abd-03dd-5e1d-b0c52f387a05',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T04:58:34Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606539508649-2fu0hb',
        ],
        'detail' => [
            'timestamp' => 1606539514554,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606539508649-2fu0hb',
            'status' => 'STATUS_UPDATE',
            'userMetadata' => [
                'model_id' => '21',
                'hello' => 'world',
            ],
            'framesDecoded' => 0,
            'jobProgress' => [
                'phaseProgress' => [
                    'PROBING' => [
                        'status' => 'COMPLETE',
                        'percentComplete' => 100,
                    ],
                    'TRANSCODING' => [
                        'status' => 'PENDING',
                        'percentComplete' => 0,
                    ],
                    'UPLOADING' => [
                        'status' => 'PENDING',
                        'percentComplete' => 0,
                    ],
                ],
                'jobPercentComplete' => 0,
                'currentPhase' => 'TRANSCODING',
                'retryCount' => 0,
            ],
        ],
    ];

    protected array $statusUpdate2 = [
        'version' => '0',
        'id' => '7f0eae16-c6d9-5511-0f77-93d2cbead642',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T04:59:51Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606539508649-2fu0hb',
        ],
        'detail' => [
            'timestamp' => 1606539591681,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606539508649-2fu0hb',
            'status' => 'STATUS_UPDATE',
            'userMetadata' => [
                'model_id' => '21',
                'hello' => 'world',
            ],
            'framesDecoded' => 1414,
            'jobProgress' => [
                'phaseProgress' => [
                    'PROBING' => [
                        'status' => 'COMPLETE',
                        'percentComplete' => 100,
                    ],
                    'TRANSCODING' => [
                        'status' => 'COMPLETE',
                        'percentComplete' => 100,
                    ],
                    'UPLOADING' => [
                        'status' => 'PROGRESSING',
                    ],
                ],
                'jobPercentComplete' => 66,
                'currentPhase' => 'TRANSCODING',
                'retryCount' => 0,
            ],
        ],
    ];

    protected array $complete = [
        'version' => '0',
        'id' => '3b9aa7e1-45ab-cf2a-1787-913f4452d16b',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T05:00:03Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606539508649-2fu0hb',
        ],
        'detail' => [
            'timestamp' => 1606539603045,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606539508649-2fu0hb',
            'status' => 'COMPLETE',
            'userMetadata' => [
                'model_id' => '21',
                'hello' => 'world',
            ],
            'outputGroupDetails' => [
                [
                    'outputDetails' => [
                        [
                            'durationInMs' => 0,
                            'videoDetails' => [
                                'widthInPx' => 0,
                                'heightInPx' => 0,
                            ],
                        ],
                    ],
                    'type' => 'FILE_GROUP',
                ],
                [
                    'outputDetails' => [
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_1.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 1920,
                                'heightInPx' => 1080,
                            ],
                        ],
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_2.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 1920,
                                'heightInPx' => 1080,
                            ],
                        ],
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_3.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 1280,
                                'heightInPx' => 720,
                            ],
                        ],
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_4.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 960,
                                'heightInPx' => 540,
                            ],
                        ],
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_5.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 768,
                                'heightInPx' => 432,
                            ],
                        ],
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo_6.m3u8',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 640,
                                'heightInPx' => 360,
                            ],
                        ],
                    ],
                    'playlistFilePaths' => [
                        's3://meema-stage/some/path/isI2oN3/hls/Raindrops_Videvo.m3u8',
                    ],
                    'type' => 'HLS_GROUP',
                ],
                [
                    'outputDetails' => [
                        [
                            'outputFilePaths' => [
                                's3://meema-stage/some/path/isI2oN3/mp4/Raindrops_Videvo.mp4',
                            ],
                            'durationInMs' => 58975,
                            'videoDetails' => [
                                'widthInPx' => 1920,
                                'heightInPx' => 1080,
                            ],
                        ],
                    ],
                    'type' => 'FILE_GROUP',
                ],
            ],
        ],
    ];

    protected array $inputInformation = [
        'version' => '0',
        'id' => 'e9ebf388-8936-bd57-64e3-c2c1f1d78897',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T05:00:02Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606539508649-2fu0hb',
        ],
        'detail' => [
            'timestamp' => 1606539602895,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606539508649-2fu0hb',
            'status' => 'INPUT_INFORMATION',
            'userMetadata' => [
                'model_id' => '21',
                'hello' => 'world',
            ],
            'inputDetails' => [
                [
                    'id' => 1,
                    'uri' => 's3://meema-stage/some/path/isI2oN3/Raindrops_Videvo.mp4',
                    'video' => [
                        [
                            'bitDepth' => 8,
                            'codec' => 'H_264',
                            'colorFormat' => 'YUV_420',
                            'fourCC' => 'avc1',
                            'frameRate' => 23.98,
                            'height' => 1080,
                            'interlaceMode' => 'PROGRESSIVE',
                            'sar' => '1:1',
                            'standard' => 'UNSPECIFIED',
                            'streamId' => 1,
                            'width' => 1920,
                        ],
                    ],
                ],
            ],
        ],
    ];

    protected array $error = [
        'version' => '0',
        'id' => '916792ce-208f-d04a-db2f-a573cf9b08bd',
        'detail-type' => 'MediaConvert Job State Change',
        'source' => 'aws.mediaconvert',
        'account' => '954236614099',
        'time' => '2020-11-28T05:56:51Z',
        'region' => 'us-east-2',
        'resources' => [
            'arn:aws:mediaconvert:us-east-2:954236614099:jobs/1606542703411-00h7yw',
        ],
        'detail' => [
            'timestamp' => 1606543011394,
            'accountId' => '954236614099',
            'queue' => 'arn:aws:mediaconvert:us-east-2:954236614099:queues/Default',
            'jobId' => '1606542703411-00h7yw',
            'status' => 'ERROR',
            'errorCode' => 1404,
            'errorMessage' => 'Unable to open input file [s3://meema-stage/some/path/AoTef8Z/Raindrops_Videvo.mp4]: [Failed probe/open: [Failed to read data: HeadObject failed]]',
            'userMetadata' => [
                'model_id' => '23',
                'hello' => 'world',
            ],
        ],
    ];
}
