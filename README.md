# MediaConvert Package for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/meema/laravel-mediaconvert.svg?style=flat-square)](https://packagist.org/packages/Meema/laravel-mediaconvert)
[![Test](https://github.com/meemaio/laravel-mediaconvert/workflows/Test/badge.svg?branch=master)](https://github.com/ci-on/laravel-mediaconvert/actions)
[![StyleCI](https://github.styleci.io/repos/264578171/shield?branch=master)](https://github.styleci.io/repos/264578171)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ci-on/laravel-mediaconvert/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ci-on/laravel-mediaconvert/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/Meema/laravel-mediaconvert.svg?style=flat-square)](https://packagist.org/packages/Meema/laravel-mediaconvert)
[![License](https://img.shields.io/github/license/ci-on/laravel-mediaconvert.svg?style=flat-square)](https://github.com/ci-on/laravel-mediaconvert/blob/master/LICENSE.md)
<!-- [
[![Build Status](wip)](ghactions)
 -->

This is a wrapper package for AWS MediaConvert.

## Usage

``` php
use Meema\MediaConvert\Facades\MediaConvert;

// run any of the following MediaConvert methods:
$result = MediaConvert::cancelJob(string $id);
$result = MediaConvert::createJob(array $settings);
$result = MediaConvert::getJob(string $id);
$result = MediaConvert::listJobs(array $options);
```

## Installation

You can install the package via composer:

```bash
composer require meema/laravel-mediaconvert
```

The package will automatically register itself.

You may optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Meema\MediaConvert\Providers\MediaConvertServiceProvider" --tag="config"
```

Next, please add the following keys their values to your `.env` file.

```env
AWS_ACCESS_KEY_ID=xxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxx
AWS_DEFAULT_REGION=us-east-1
```

The following is the content of the published config file:

```php
return [
    /**
     * IAM Credentials from AWS.
     */
    'credentials' => [
        'key'     => env('AWS_ACCESS_KEY_ID', ''),
        'secret'  => env('AWS_SECRET_ACCESS_KEY', ''),
    ],

    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'version' => 'latest',
];
```

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
- [Folks at Meema](https://github.com/meemaio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Made with ❤️ by Meema, Inc.
