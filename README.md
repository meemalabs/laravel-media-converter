# Media Converter Package for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/meema/laravel-media-converter.svg?style=flat-square)](https://packagist.org/packages/meema/laravel-media-converter)
[![StyleCI](https://github.styleci.io/repos/314969855/shield?branch=main)](https://github.styleci.io/repos/314969855)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/meemalabs/laravel-media-converter/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/meemalabs/laravel-media-converter/?branch=main)
[![Total Downloads](https://img.shields.io/packagist/dt/meema/laravel-media-converter.svg?style=flat-square)](https://packagist.org/packages/meema/laravel-media-converter)
[![License](https://img.shields.io/github/license/meemalabs/laravel-media-converter.svg?style=flat-square)](https://github.com/meemalabs/laravel-media-converter/blob/main/LICENSE.md)
<!-- [[![Test](https://github.com/meemalabs/laravel-media-converter/workflows/Test/badge.svg?branch=main)](https://github.com/meemalabs/laravel-media-converter/actions) -->
<!-- [[![Build Status](wip)](ghactions) -->

This is a wrapper package for AWS MediaConvert. Additional drivers may be added.

![laravel-media-converter package image](https://banners.beyondco.de/Media%20Converter.png?theme=light&packageManager=composer+require&packageName=meema%2Flaravel-media-converter&pattern=floatingCogs&style=style_1&description=Easily+%26+quickly+integrate+your+application+with+AWS+MediaConvert.&md=1&showWatermark=1&fontSize=125px&images=video-camera&widths=350)

## Usage

``` php
use Meema\MediaConverter\Facades\MediaConvert;
use Meema\MediaConverter\Jobs\CreateVideoConversion;

# simple usage
MediaConvert::path('video.mkv') // the s3 path to the file inside the bucket defined in your config (filesystems.disks.s3.bucket) 
    ->optimizeForWeb() // will generate an optmized MP4 for you 
    ->withThumbnails(int $framerateNumerator, int $framerateDenominator, int $maxCaptures, $width = null, $nameModifier = null, $imageQuality = 80) // will generate thumbnails from the video for you, e.g. poster images
    ->saveTo('my-optimized-video.mp4'); // output file name
    ->createJob();

# advanced usage
$result = MediaConvert::cancelJob(string $id);
$result = MediaConvert::createJob(array $settings, array $metaData = [], int $priority = 0);
$result = MediaConvert::getJob(string $id);
$result = MediaConvert::listJobs(array $options);

# you may also dispatch a job to convert a video
dispatch(new CreateVideoConversion($jobSettings, $mediaId)); // $mediaId is optional & refers to the relating model's id
```

## Installation

You can install the package via composer:

```bash
composer require meema/laravel-media-converter
```

The package will automatically register itself.

Next, publish the config file with:

```bash
php artisan vendor:publish --provider="Meema\MediaConverter\Providers\MediaConvertServiceProvider" --tag="config"
```

Next, please add the following keys their values to your `.env` file.

```bash
AWS_ACCESS_KEY_ID=xxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxx
AWS_DEFAULT_REGION=us-east-1
AWS_MEDIACONVERT_ACCOUNT_URL=https://xxxxxxxxx.mediaconvert.us-east-1.amazonaws.com
AWS_IAM_ARN=arn:aws:iam::xxxxxxx:role/MediaConvert_Default_Role
AWS_QUEUE_ARN=arn:aws:mediaconvert:us-east-1:xxxxxxx:queues/Default
```

The following is the content of the published config file:

```php
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
     * This value indicates whether to track media conversions in your database.
     *
     * Note: in case you *do* want to track media conversions, you will need to execute the
     * migration included as part of the package.
     */
    'track_media_conversions' => true,

    /**
     * If track_media_conversions is set to true, you may specify the events you would like to fire/track.
     * By default, it will track all status updates.
     *
     * Read more about MediaConvert conversion statuses here:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/mediaconvert_cwe_events.html
     */
    'statuses_to_track' => ['complete', 'error', 'new_warning', 'progressing', 'input_information', 'queue_hop'],
];
```

## Preparing Your Media Model (optional)

This package includes a trait for your "Media model" that you may use to define the relationship of your media model with the tracked conversions.

Simply use it as follows:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Meema\MediaConverter\Traits\Convertable;

class Media extends Model
{
    use Convertable;

    // ...
}
```

### Set Up Webhooks (optional)

This package makes use of webhooks in order to communicate the status/progress of the MediaConvert job. Please follow the following steps to enable webhooks for yourself.

Please note, this is only optional, and you should only enable this if you want to track the MediaConvert job's progressions.

#### Setup Expose

First, let's use [Expose](https://beyondco.de/docs/expose/getting-started/installation) to "expose" / generate a URL for our local API. Follow the Expose documentation on how you can get started and generate a "live" & sharable URL for within your development environment.

It should be as simple as `cd my-laravel-api && expose`.

#### Setup AWS SNS Topic & Subscription

Second, let's create an AWS SNS Topic which will notify our "exposed" API endpoint:

1. Open the Amazon SNS console at https://console.aws.amazon.com/sns/v3/home
2. In the navigation pane, choose Topics, and then choose "Create new topic".
3. For Topic name, enter `MediaConvertJobUpdate`, and then choose "Create topic".

![AWS SNS Topic Creation Screenshot](https://i.imgur.com/wzVJFxZ.png)

4. Choose the topic ARN link for the topic that you just created. It looks something like this: `arn:aws:sns:region:123456789012:MediaConvertJobUpdate`.
5. On the Topic details: `MediaConvertJobUpdate` page, in the Subscriptions section, choose "Create subscription".
6. For Protocol, choose "HTTPS". For Endpoint, enter your "exposed" API URL which you generated in a previous step.

For example,
```
https://meema-api.sharedwithexpose.com/api/webhooks/media-converter
```

7. Choose "Create subscription".

#### Confirming Your Subscription

Finally, we need to confirm the subscription which is easily done by navigating to the `MediaConvertJobUpdate` Topic page. There, you should see the following section:

![AWS SNS Subscription Confirmation Screenshot](https://i.imgur.com/qzLZJAD.png)

By default, AWS will have sent a post request to URL you defined in your "Subscription" setup. You can view request in the Expose interface, by visiting the "Dashboard Url", which should be similar to: `http://127.0.0.1:4040`

Once you are in the Expose dashboard, you need to locate the `SubscribeURL` value. Once located, copy it and use it to confirm your SNS Topic Subscription.

![AWS SNS Subscription Confirmation Screenshot](https://i.imgur.com/ECGIBUY.png)

#### Create CloudWatch Event

1. Open the CloudWatch console at https://console.aws.amazon.com/cloudwatch/.
2. In the navigation pane, choose Events, and then choose "Create rule".
3. Make sure the inputs match the following screenshots:

![AWS CloudWatch Rule Creation Screenshot](https://i.imgur.com/2c8SEfN.png)

As you can see in above screenshot, we needed to select the "Service Name", "Event Type", and the "Target" which is referencing our SNS Topic we earlier created.

Lastly, the second & final step of the "Rule creation" prompts you to enter a name and an optional description. You may use any name, e.g. `mediaconvert-job-updates`.

Now, your API will receive webhooks!

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email chris@cion.agency instead of using the issue tracker.

## Credits

- [Chris Breuer](https://github.com/Chris1904)
- [Folks at Meema](https://github.com/meemalabs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Made with ❤️ by Meema, Inc.
