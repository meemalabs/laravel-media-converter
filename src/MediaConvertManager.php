<?php

namespace Meema\MediaConvert;

use Aws\Credentials\Credentials;
use Aws\MediaConvert\MediaConvertClient;
use Exception;
use Illuminate\Support\Manager;
use Meema\MediaConvert\Converters\MediaConverter;

class MediaConvertManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param string|null $name
     * @return mixed
     */
    public function engine($name = null)
    {
        return $this->driver($name);
    }

    /**
     * Create an Amazon MediaConvert Converter instance.
     *
     * @return \Meema\MediaConvert\Converters\MediaConverter
     * @throws \Exception
     */
    public function createMediaConvertDriver(): MediaConverter
    {
        $this->ensureAwsSdkIsInstalled();

        $config = $this->config['media-convert'];

        $credentials = $this->getCredentials($config['credentials']);

        $client = $this->setMediaConvertClient($config, $credentials);

        return new MediaConverter($client);
    }

    /**
     * Sets the polly client.
     *
     * @param array $config
     * @param Credentials $credentials
     * @return \Aws\MediaConvert\MediaConvertClient
     */
    protected function setMediaConvertClient(array $config, Credentials $credentials): MediaConvertClient
    {
        return new MediaConvertClient([
            'version' => $config['version'],
            'region' => $config['region'],
            'credentials' => $credentials,
        ]);
    }

    /**
     * Get credentials of AWS.
     *
     * @param array $credentials
     * @return \Aws\Credentials\Credentials
     */
    protected function getCredentials(array $credentials): Credentials
    {
        return new Credentials($credentials['key'], $credentials['secret']);
    }

    /**
     * Ensure the AWS SDK is installed.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function ensureAwsSdkIsInstalled()
    {
        if (! class_exists(MediaConvertClient::class)) {
            throw new Exception('Please install the AWS SDK PHP using `composer require aws/aws-sdk-php`.');
        }
    }

    /**
     * Get the default Text to Speech driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return 'media-convert';
    }
}
