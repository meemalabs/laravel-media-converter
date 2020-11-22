<?php

return [
    /**
     * IAM Credentials from AWS.
     */
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID', ''),
        'secret' => env('AWS_SECRET_ACCESS_KEY', ''),
    ],

    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'version' => 'latest',

    'iam_arn' => env('AWS_IAM_ARN'),
    'queue_arn' => env('AWS_QUEUE_ARN'),
];
