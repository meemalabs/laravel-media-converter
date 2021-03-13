<?php

return [
    /*
     * The fully qualified class name of the "media" model.
     */
    'media_model' => \App\Models\Media::class,

    /**
     * IAM Credentials from AWS.
     */
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'version' => 'latest',
    'url' => env('AWS_MEDIACONVERT_ACCOUNT_URL'),

    /**
     * Specify the IAM Role ARN.
     *
     * You can find the Role ARN visiting the following URL:
     * https://console.aws.amazon.com/iam/home?region=us-east-1#/roles
     * Please note to adjust the "region" in the URL above.
     * Tip: in case you need to create a new Role, you may name it `MediaConvert_Default_Role`
     * by making use of this name, AWS MediaConvert will default to using this IAM Role.
     */
    'iam_arn' => env('AWS_IAM_ARN'),

    /**
     * Specify the queue you would like use.
     *
     * It can be found by visiting the following URL:
     * https://us-east-1.console.aws.amazon.com/mediaconvert/home?region=us-east-1#/queues/details/Default
     * Please note to adjust the "region" in the URL above.
     */
    'queue_arn' => env('AWS_QUEUE_ARN'),

    /**
     * Specify how often MediaConvert sends STATUS_UPDATE events to Amazon CloudWatch Events.
     * Set the interval, in seconds, between status updates.
     *
     * MediaConvert sends an update at this interval from the time the service begins processing
     * your job to the time it completes the transcode or encounters an error.
     *
     * Accepted values: 10, 12, 15, 20, 30, 60, 120, 180, 240, 300, 360, 420, 480, 540, 600
     */
    'webhook_interval' => 60,

    /**
     * This value indicates whether.
     *
     * Note: in case you *do* want to track media conversions, you will need to execute the
     * migration as part of the package.
     */
    'track_media_conversions' => true,

    /**
     * If track_media_conversions is set to true, you may specify the events you would like to fire/track.
     * By default, it will track all status updates.
     *
     * Read more about MediaConvert conversion statuses here:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/mediaconvert_cwe_events.html
     */
    'statuses_to_track' => ['complete', 'error', 'new_warning', 'progressing', 'status_update', 'input_information', 'queue_hop'],

    'settings' => [
        'OutputGroups' => [
            // first thumbnails for sprite generation
            [
                'CustomName' => 'Thumbnails',
                'Name' => 'File Group',
                'Outputs' => [
                    [
                        'ContainerSettings' => [
                            'Container' => 'RAW',
                        ],
                        'VideoDescription' => [
                            'ScalingBehavior' => 'DEFAULT',
                            'TimecodeInsertion' => 'DISABLED',
                            'AntiAlias' => 'ENABLED',
                            'Sharpness' => 50,
                            'CodecSettings' => [
                                'Codec' => 'FRAME_CAPTURE',
                                'FrameCaptureSettings' => [
                                    'FramerateNumerator' => null, // to be set dynamically
                                    'FramerateDenominator' => null, // to be set dynamically
                                    'MaxCaptures' => null, // to be set dynamically
                                    'Quality' => 80,
                                ],
                            ],
                            'AfdSignaling' => 'NONE',
                            'DropFrameTimecode' => 'ENABLED',
                            'RespondToAfd' => 'NONE',
                            'ColorMetadata' => 'INSERT',
                            'Width' => null, // to be set dynamically
                        ],
                        'NameModifier' => '.$w$x$h$', // e.g. moderation-test-video.240x136.0000098.jpg
                    ],
                ],
                'OutputGroupSettings' => [
                    'Type' => 'FILE_GROUP_SETTINGS',
                    'FileGroupSettings' => [
                        'Destination' => null, // to be set dynamically
                        'DestinationSettings' => [
                            'S3Settings' => [
                                'AccessControl' => [
                                    'CannedAcl' => 'PUBLIC_READ',
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // second hls
            [
                'Name'=> 'Apple HLS',
                'Outputs' => [
                    [
                        'ContainerSettings' => [
                            'Container' => 'M3U8',
                            'M3u8Settings' => [
                                'AudioFramesPerPes' => 4,
                                'PcrControl' => 'PCR_EVERY_PES_PACKET',
                                'PmtPid' => 480,
                                'PrivateMetadataPid' => 503,
                                'ProgramNumber' => 1,
                                'PatInterval' => 0,
                                'PmtInterval' => 0,
                                'Scte35Source' => 'NONE',
                                'NielsenId3' > 'ONE',
                                'TimedMetadata' => 'NONE',
                                'VideoPid' => 481,
                                'AudioPids' => [
                                    482,
                                    483,
                                    484,
                                    485,
                                    486,
                                    487,
                                    488,
                                    489,
                                    490,
                                    491,
                                    492,
                                ],
                                'AudioDuration' => 'DEFAULT_CODEC_DURATION',
                            ],
                        ],
                        'VideoDescription' => [
                            'ScalingBehavior' => 'DEFAULT',
                            'TimecodeInsertion' => 'DISABLED',
                            'AntiAlias' => 'ENABLED',
                            'Sharpness' => 50,
                            'CodecSettings' => [
                                'Codec' => 'H_264',
                                'H264Settings' => [
                                    'InterlaceMode' => 'PROGRESSIVE',
                                    'NumberReferenceFrames' => 3,
                                    'Syntax' => 'DEFAULT',
                                    'Softness' => 0,
                                    'GopClosedCadence' => 1,
                                    'GopSize' => 90,
                                    'Slices' => 1,
                                    'GopBReference' => 'DISABLED',
                                    'SlowPal' => 'DISABLED',
                                    'EntropyEncoding' => 'CABAC',
                                    'FramerateControl' => 'INITIALIZE_FROM_SOURCE',
                                    'RateControlMode' => 'QVBR',
                                    'CodecProfile' => 'MAIN',
                                    'Telecine' => 'NONE',
                                    'MinIInterval' => 0,
                                    'AdaptiveQuantization' => 'AUTO',
                                    'CodecLevel' => 'AUTO',
                                    'FieldEncoding' => 'PAFF',
                                    'SceneChangeDetect' => 'ENABLED',
                                    'QualityTuningLevel' => 'MULTI_PASS_HQ',
                                    'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                    'UnregisteredSeiTimecode' => 'DISABLED',
                                    'GopSizeUnits' => 'FRAMES',
                                    'ParControl' => 'INITIALIZE_FROM_SOURCE',
                                    'NumberBFramesBetweenReferenceFrames' => 2,
                                    'RepeatPps' => 'DISABLED',
                                    'DynamicSubGop' => 'STATIC',
                                ],
                            ],
                            'AfdSignaling' => 'NONE',
                            'DropFrameTimecode' => 'ENABLED',
                            'RespondToAfd' => 'NONE',
                            'ColorMetadata' => 'INSERT',
                        ],
                        'AudioDescriptions' => [
                            [
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'CodecSettings' => [
                                    'Codec' => 'AAC',
                                    'AacSettings' => [
                                        'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                        'Bitrate' => 96000,
                                        'RateControlMode' => 'CBR',
                                        'CodecProfile' => 'LC',
                                        'CodingMode' => 'CODING_MODE_2_0',
                                        'RawFormat' => 'NONE',
                                        'SampleRate' => 48000,
                                        'Specification' => 'MPEG4',
                                    ],
                                ],
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                            ],
                        ],
                        'OutputSettings' => [
                            'HlsSettings' => [
                                'AudioGroupId' => 'program_audio',
                                'AudioOnlyContainer' => 'AUTOMATIC',
                                'IFrameOnlyManifest' => 'EXCLUDE',
                            ],
                        ],
                    ],
                ],
                'OutputGroupSettings' => [
                    'Type' => 'HLS_GROUP_SETTINGS',
                    'HlsGroupSettings' => [
                        'ManifestDurationFormat' => 'INTEGER',
                        'SegmentLength' => 10,
                        'TimedMetadataId3Period' => 10,
                        'CaptionLanguageSetting' => 'OMIT',
                        'Destination' => null,
                        'TimedMetadataId3Frame' => 'PRIV',
                        'CodecSpecification' => 'RFC_4281',
                        'OutputSelection' => 'MANIFESTS_AND_SEGMENTS',
                        'ProgramDateTimePeriod' => 600,
                        'MinSegmentLength' => 0,
                        'MinFinalSegmentLength' => 0,
                        'DirectoryStructure' => 'SINGLE_DIRECTORY',
                        'ProgramDateTime' => 'EXCLUDE',
                        'SegmentControl' => 'SEGMENTED_FILES',
                        'ManifestCompression' => 'NONE',
                        'ClientCache' => 'ENABLED',
                        'AudioOnlyHeader' => 'INCLUDE',
                        'StreamInfResolution' => 'INCLUDE',
                    ],
                ],
                'AutomatedEncodingSettings' => [
                    'AbrSettings' => [
                        'MaxAbrBitrate' => 8000000,
                        'MinAbrBitrate' => 600000,
                    ],
                ],
            ],

            // third mp4
            [
                'CustomName' => 'MP4',
                'Name' => 'File Group',
                'Outputs' => [
                    [
                        'ContainerSettings' => [
                            'Container' => 'MP4',
                            'Mp4Settings' => [
                                'CslgAtom' => 'INCLUDE',
                                'FreeSpaceBox' => 'EXCLUDE',
                                'MoovPlacement' => 'PROGRESSIVE_DOWNLOAD',
                            ],
                        ],
                        'VideoDescription' => [
                            'ScalingBehavior' => 'DEFAULT',
                            'TimecodeInsertion' => 'DISABLED',
                            'AntiAlias' => 'ENABLED',
                            'Sharpness' => 50,
                            'CodecSettings' => [
                                'Codec' => 'H_264',
                                'H264Settings' => [
                                    'InterlaceMode' => 'PROGRESSIVE',
                                    'NumberReferenceFrames' => 3,
                                    'Syntax' => 'DEFAULT',
                                    'Softness' => 0,
                                    'GopClosedCadence' => 1,
                                    'GopSize' => 90,
                                    'Slices' => 1,
                                    'GopBReference' => 'DISABLED',
                                    'MaxBitrate' => 8000000,
                                    'SlowPal' => 'DISABLED',
                                    'SpatialAdaptiveQuantization' => 'ENABLED',
                                    'TemporalAdaptiveQuantization' => 'ENABLED',
                                    'FlickerAdaptiveQuantization' => 'DISABLED',
                                    'EntropyEncoding' => 'CABAC',
                                    'FramerateControl' => 'INITIALIZE_FROM_SOURCE',
                                    'RateControlMode' => 'QVBR',
                                    'QvbrSettings' => [
                                        'QvbrQualityLevel' => 7,
                                        'QvbrQualityLevelFineTune' => 0,
                                    ],
                                    'CodecProfile' => 'MAIN',
                                    'Telecine' => 'NONE',
                                    'MinIInterval' => 0,
                                    'AdaptiveQuantization' => 'HIGH',
                                    'CodecLevel' => 'AUTO',
                                    'FieldEncoding' => 'PAFF',
                                    'SceneChangeDetect' => 'ENABLED',
                                    'QualityTuningLevel' => 'SINGLE_PASS',
                                    'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                    'UnregisteredSeiTimecode' => 'DISABLED',
                                    'GopSizeUnits' => 'FRAMES',
                                    'ParControl' => 'INITIALIZE_FROM_SOURCE',
                                    'NumberBFramesBetweenReferenceFrames' => 2,
                                    'RepeatPps' => 'DISABLED',
                                ],
                            ],
                            'AfdSignaling' => 'NONE',
                            'DropFrameTimecode' => 'ENABLED',
                            'RespondToAfd' => 'NONE',
                            'ColorMetadata' => 'INSERT',
                        ],
                        'AudioDescriptions' => [
                            [
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'CodecSettings' => [
                                    'Codec' => 'AAC',
                                    'AacSettings' => [
                                        'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                        'Bitrate' => 96000,
                                        'RateControlMode' => 'CBR',
                                        'CodecProfile' => 'LC',
                                        'CodingMode' => 'CODING_MODE_2_0',
                                        'RawFormat' => 'NONE',
                                        'SampleRate' => 48000,
                                        'Specification' => 'MPEG4',
                                    ],
                                ],
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                            ],
                        ],
                    ],
                ],
                'OutputGroupSettings' => [
                    'Type' => 'FILE_GROUP_SETTINGS',
                    'FileGroupSettings' => [
                        'Destination' => null, // to be set dynamically
                        'DestinationSettings' => [
                            'S3Settings' => [
                                'AccessControl' => [
                                    'CannedAcl' => 'PUBLIC_READ',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'AdAvailOffset' => 0,
        'Inputs' => [
            [
                'AudioSelectors' => [
                    'Audio Selector 1' => [
                        'Offset' => 0,
                        'DefaultSelection' => 'DEFAULT',
                        'ProgramSelection' => 1,
                    ],
                ],
                'VideoSelector' => [
                    'ColorSpace' => 'FOLLOW',
                ],
                'FilterEnable' => 'AUTO',
                'PsiControl' => 'USE_PSI',
                'FilterStrength' => 0,
                'DeblockFilter' => 'DISABLED',
                'DenoiseFilter' => 'DISABLED',
                'TimecodeSource' => 'EMBEDDED',
                'FileInput' => null, // to be set dynamically
            ],
        ],
    ],
];
